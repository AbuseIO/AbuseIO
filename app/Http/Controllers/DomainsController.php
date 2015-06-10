<?php namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Models\Domain;
use AbuseIO\Models\Contact;
use Input;
use Redirect;

class DomainsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $domains = Domain::with('contact')->paginate(10);

        return view('domains.index')->with('domains', $domains);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $contacts = Contact::lists('name', 'id');

        return view('domains.create')->with('contact_selection', $contacts)->with('selected', null);
    }

    /**
     * Export listing to CSV format.
     *
     * @return Response
     */
    public function export()
    {
        $domains = Domain::all();
        $columns = [
            'contact' => 'Contact',
            'domain' => 'Domain name',
            'enabled' => 'Status',
        ];

        $output = '"' . implode('","', $columns) . '"' . PHP_EOL;
        foreach ($domains as $domain) {
            $row = [
                $domain->contact->name . ' (' . $domain->contact->reference . ')',
                $domain['name'],
                $domain['enabled'] ? 'Enabled' : 'Disabled',
            ];
            $output .= '"' . implode('","', $row) . '"' . PHP_EOL;
        }

        return response(substr($output, 0, -1), 200)->header('Content-Type', 'text/csv')->header('Content-Disposition', 'attachment; filename="Domains.csv"');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        Domain::create($input);

        return Redirect::route('admin.domains.index')->with('message', 'Domain has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Domain $domain)
    {
        return view('domains.show')->with('domain', $domain);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Domain $domain)
    {
        $contacts = Contact::lists('name', 'id');

        return view('domains.edit')->with('domain', $domain)->with('contact_selection', $contacts)->with('selected', $domain->contact_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Domain $domain)
    {
        $input = array_except(Input::all(), '_method');
        $domain->update($input);

        return Redirect::route('admin.domains.show', $domain->id)->with('message', 'Domain has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();

        return Redirect::route('admin.domains.index')->with('message', 'Domain has been deleted.');
    }

}
