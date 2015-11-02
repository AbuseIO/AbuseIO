<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\DomainFormRequest;
use AbuseIO\Http\Controllers\Controller;
use AbuseIO\Models\Domain;
use AbuseIO\Models\Contact;
use Redirect;
use Input;

class DomainsController extends Controller
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
        $domains = Domain::with('contact')
            ->paginate(10);

        return view('domains.index')
            ->with('domains', $domains)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $contacts = Contact::lists('name', 'id');

        return view('domains.create')
            ->with('contact_selection', $contacts)
            ->with('selected', null)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Export listing to CSV format.
     * @return Response
     */
    public function export($format)
    {
        $domains = Domain::all();

        if ($format === 'csv') {
            $columns = [
                'contact'   => 'Contact',
                'domain'    => 'Domain name',
                'enabled'   => 'Status',
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

            return response(substr($output, 0, -1), 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="Domains.csv"');
        }

        return Redirect::route('admin.domains.index')
            ->with('message', "The requested format {$format} is not available for exports");
    }

    /**
     * Store a newly created resource in storage.
     * @return Response
     */
    public function store(DomainFormRequest $domain)
    {
        $input = Input::all();

        try {
            Domain::create($input);

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $message = 'Unknown error code: ' . $errorCode;

            if ($errorCode === 1062) {
                $message = 'Another domain with this name already exists';
            }

            return Redirect::back()
                ->with('message', $message);
        }

        return Redirect::route('admin.domains.index')
            ->with('message', 'Domain has been created');
    }

    /**
     * Display the specified resource.
     * @param Request $request
     * @param Domain $domain
     * @return Response
     * @internal param int $id
     */
    public function show(Domain $domain)
    {
        return view('domains.show')
            ->with('domain', $domain)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for editing the specified resource.
     * @param Domain $domain
     * @return Response
     * @internal param int $id
     */
    public function edit(Domain $domain)
    {
        $contacts = Contact::lists('name', 'id');

        return view('domains.edit')
            ->with('domain', $domain)
            ->with('contact_selection', $contacts)
            ->with('selected', $domain->contact_id)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     * @param Domain $domain
     * @return Response
     * @internal param int $id
     */
    public function update(Domain $domain)
    {
        $input = array_except(Input::all(), '_method');

        try {
            $domain->update($input);

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $message = 'Unknown error code: ' . $errorCode;

            if ($errorCode === 1062) {
                $message = 'Another domain with this name already exists';
            }

            return Redirect::back()
                ->with('message', $message);
        }

        return Redirect::route('admin.domains.show', $domain->id)
            ->with('message', 'Domain has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     * @param  int $id
     * @return Response
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();

        return Redirect::route('admin.domains.index')
            ->with('message', 'Domain has been deleted.');
    }
}
