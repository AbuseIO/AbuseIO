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
        parent::__construct('createDynamicACL');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $netblocks = Netblock::with('contact')
                        ->paginate(10);

        return view('netblocks.index')
            ->with('netblocks', $netblocks)
            ->with('user', $request->user());
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $contacts = Contact::lists('name', 'id');

        return view('netblocks.create')
            ->with('contact_selection', $contacts)
            ->with('selected', null)
            ->with('user', $request->user());
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
     * @param Request $request
     * @param Netblock $netblock
     * @return Response
     * @internal param int $id
     */
    public function show(Request $request, Netblock $netblock)
    {
        return view('netblocks.show')
            ->with('netblock', $netblock)
            ->with('user',$request->user());
    }

    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param Netblock $netblock
     * @return Response
     * @internal param int $id
     */
    public function edit(Request $request, Netblock $netblock)
    {
        $contacts = Contact::lists('name', 'id');

        $netblock->first_ip = ICF::inetItop($netblock->first_ip);
        $netblock->last_ip  = ICF::inetItop($netblock->last_ip);

        return view('netblocks.edit')
            ->with('netblock', $netblock)
            ->with('contact_selection', $contacts)
            ->with('selected', $netblock->contact_id)
            ->with('user', $request->user());
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
