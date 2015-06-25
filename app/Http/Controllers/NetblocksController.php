<?php namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Models\Netblock;
use AbuseIO\Models\Contact;
use Input;
use Redirect;
use ICF;

class NetblocksController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $netblocks = Netblock::with('contact')
            ->paginate(10);

        return view('netblocks.index')
            ->with('netblocks', $netblocks);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        $contacts = Contact::lists('name', 'id');

        return view('netblocks.create')
            ->with('contact_selection', $contacts)
            ->with('selected', null);

    }

    /**
     * Export listing to CSV format.
     *
     * @return Response
     */
    public function export()
    {

        $netblocks  = Netblock::all();

        $columns    =
            [
                'contact'   => 'Contact',
                'enabled'   => 'Status',
                'first_ip'  => 'First IP',
                'last_ip'   => 'Last IP'
            ];

        $output     = '"' . implode('", "', $columns) . '"' . PHP_EOL;

        foreach ($netblocks as $netblock) {

            $row =
                [
                    $netblock->contact->name . ' (' .$netblock->contact->reference . ')',
                    ICF::inet_itop($netblock['first_ip']),
                    ICF::inet_itop($netblock['last_ip']),
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
     *
     * @return Response
     */
    public function store()
    {

        $input = Input::all();

        $input['first_ip'] =  ICF::inet_ptoi($input['first_ip']);
        $input['last_ip']  =  ICF::inet_ptoi($input['last_ip']);

        Netblock::create($input);

        return Redirect::route('admin.netblocks.index')
            ->with('message', 'Netblock has been created');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Netblock $netblock)
    {

        return view('netblocks.show')
            ->with('netblock', $netblock);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Netblock $netblock)
    {

        $contacts = Contact::lists('name', 'id');

        $netblock->first_ip = ICF::inet_itop($netblock->first_ip);
        $netblock->last_ip  = ICF::inet_itop($netblock->last_ip);

        return view('netblocks.edit')
            ->with('netblock', $netblock)
            ->with('contact_selection', $contacts)
            ->with('selected', $netblock->contact_id);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Netblock $netblock)
    {

        $input = array_except(Input::all(), '_method');

        $input['first_ip'] =  ICF::inet_ptoi($input['first_ip']);
        $input['last_ip']  =  ICF::inet_ptoi($input['last_ip']);

        $netblock->update($input);

        return Redirect::route('admin.netblocks.show', $netblock->id)
            ->with('message', 'Netblock has been updated.');

    }

    /**
     * Remove the specified resource from storage.
     *
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
