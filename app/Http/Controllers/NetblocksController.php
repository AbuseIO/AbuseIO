<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\NetblockFormRequest;
use AbuseIO\Http\Controllers\Controller;
use AbuseIO\Models\Netblock;
use AbuseIO\Models\Contact;
use Redirect;
use Input;
use ICF;

class NetblocksController extends Controller
{


    /*
     * Call the parent constructor to generate a base ACL
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $netblocks = Netblock::with('contact')
                        ->paginate(10);

        return view('netblocks.index')
            ->with('netblocks', $netblocks)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $contacts = Contact::lists('name', 'id');

        return view('netblocks.create')
            ->with('contact_selection', $contacts)
            ->with('selected', null)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Export listing to CSV format.
     * @return Response
     */
    public function export()
    {
        $netblocks  = Netblock::all();

        $columns = [
            'contact'   => 'Contact',
            'enabled'   => 'Status',
            'first_ip'  => 'First IP',
            'last_ip'   => 'Last IP'
        ];

        $output = '"' . implode('", "', $columns) . '"' . PHP_EOL;

        foreach ($netblocks as $netblock) {
            $row = [
                $netblock->contact->name . ' (' .$netblock->contact->reference . ')',
                ICF::inetItop($netblock['first_ip']),
                ICF::inetItop($netblock['last_ip']),
                $netblock['enabled'] ? 'Enabled' : 'Disabled',
            ];

            $output .= '"' . implode('", "', $row) . '"' . PHP_EOL;
        }

        return response(substr($output, 0, -1), 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="Netblocks.csv"');
    }

    /**
     * Store a newly created resource in storage.
     * @return Response
     */
    public function store(NetblockFormRequest $netblock)
    {
        $input = Input::all();
        $input['first_ip'] =  ICF::inetPtoi($input['first_ip']);
        $input['last_ip']  =  ICF::inetPtoi($input['last_ip']);

        Netblock::create($input);

        return Redirect::route('admin.netblocks.index')
            ->with('message', 'Netblock has been created');
    }

    /**
     * Display the specified resource.
     * @param Netblock $netblock
     * @return Response
     * @internal param int $id
     */
    public function show(Netblock $netblock)
    {
        return view('netblocks.show')
            ->with('netblock', $netblock)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for editing the specified resource.
     * @param Netblock $netblock
     * @return Response
     * @internal param int $id
     */
    public function edit(Netblock $netblock)
    {
        $contacts = Contact::lists('name', 'id');

        $netblock->first_ip = ICF::inetItop($netblock->first_ip);
        $netblock->last_ip  = ICF::inetItop($netblock->last_ip);

        return view('netblocks.edit')
            ->with('netblock', $netblock)
            ->with('contact_selection', $contacts)
            ->with('selected', $netblock->contact_id)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     * @param  int  $id
     * @return Response
     */
    public function update(Netblock $netblock)
    {
        $input = array_except(Input::all(), '_method');
        $input['first_ip'] =  ICF::inetPtoi($input['first_ip']);
        $input['last_ip']  =  ICF::inetPtoi($input['last_ip']);

        $netblock->update($input);

        return Redirect::route('admin.netblocks.show', $netblock->id)
            ->with('message', 'Netblock has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return Response
     */
    public function destroy(Netblock $netblock)
    {
        $netblock->delete();

        return Redirect::route('admin.netblocks.index')
            ->with('message', 'Netblock has been deleted.');
    }
}
