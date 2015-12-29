<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\DomainFormRequest;
use AbuseIO\Models\Domain;
use AbuseIO\Models\Contact;
use yajra\Datatables\Datatables;
use Redirect;
use Form;

/**
 * Class DomainsController
 * @package AbuseIO\Http\Controllers
 */
class DomainsController extends Controller
{

    /**
     * DomainsController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $domains = Domain::select('domains.*', 'contacts.name as contacts_name')
            ->leftJoin('contacts', 'contacts.id', '=', 'domains.contact_id');

        return Datatables::of($domains)
            ->addColumn(
                'actions',
                function ($domain) {
                    $actions = Form::open(
                        [
                            'route' => ['admin.domains.destroy', $domain->id],
                            'method' => 'DELETE',
                            'class' => 'form-inline'
                        ]
                    );
                    $actions .= ' <a href="domains/' . $domain->id .
                        '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> '.
                        trans('misc.button.show').'</a> ';
                    $actions .= ' <a href="domains/' . $domain->id .
                        '/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> '.
                        trans('misc.button.edit').'</a> ';
                    $actions .= Form::button(
                        '<i class="glyphicon glyphicon-remove"></i> '. trans('misc.button.delete'),
                        [
                            'type' => 'submit',
                            'class' => 'btn btn-danger btn-xs'
                        ]
                    );
                    $actions .= Form::close();
                    return $actions;
                }
            )
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('domains.index')
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
     *
     * @param  string $format
     * @return \Illuminate\Http\Response
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
     *
     * @param DomainFormRequest $domainForm
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DomainFormRequest $domainForm)
    {
        Domain::create($domainForm->all());

        return Redirect::route('admin.domains.index')
            ->with('message', 'Domain has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param Domain $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        return view('domains.show')
            ->with('domain', $domain)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Domain $domain
     * @return \Illuminate\Http\Response
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
     *
     * @param DomainFormRequest $domainForm
     * @param Domain $domain
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DomainFormRequest $domainForm, Domain $domain)
    {
        $domain->update($domainForm->all());

        return Redirect::route('admin.domains.show', $domain->id)
            ->with('message', 'Domain has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Domain $domain
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();

        return Redirect::route('admin.domains.index')
            ->with('message', 'Domain has been deleted.');
    }
}
