<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\StoreDomainRequest;
use AbuseIO\Http\Requests\UpdateDomainRequest;
use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
use AbuseIO\Traits\Api;
use AbuseIO\Transformers\DomainTransformer;
use Form;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Redirect;
use yajra\Datatables\Datatables;

/**
 * Class DomainsController.
 */
class DomainsController extends Controller
{
    use Api;

    /**
     * DomainsController constructor.
     *
     * @param Manager $fractal
     * @param Request $request
     */
    public function __construct(Manager $fractal, Request $request)
    {
        parent::__construct();
        $this->apiInit($fractal, $request);

        // Rely on route-level permission middleware for create/store
        $this->middleware(\AbuseIO\Http\Middleware\CheckAccount::class.':Domain', ['except' => ['index', 'create', 'store', 'apiIndex', 'apiStore', 'apiShow', 'apiUpdate', 'apiDestroy']]);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $auth_account = $this->auth_user->account;

        $domains = Domain::select('domains.*', 'contacts.name as contacts_name')
            ->leftJoin('contacts', 'contacts.id', '=', 'domains.contact_id');

        if (!$auth_account->isSystemAccount()) {
            $domains = $domains
                ->leftJoin('accounts', 'accounts.id', '=', 'contacts.account_id')
                ->where('accounts.id', '=', $auth_account->id);
        }

        return Datatables::of($domains)
            ->addColumn(
                'actions',
                function ($domain) {
                    $deleteAction = route('admin.domains.destroy', $domain->id);
                    $actions = '<form method="POST" action="'.$deleteAction.'" class="form-inline">'.csrf_field().method_field('DELETE');
                    $actions .= ' <a href="domains/'.$domain->id.
                        '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> '.
                        trans('misc.button.show').'</a> ';
                    $actions .= ' <a href="domains/'.$domain->id.
                        '/edit" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> '.
                        trans('misc.button.edit').'</a> ';
                    $actions .= '<button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> '.
                        trans('misc.button.delete').'</button>';
                    $actions .= '</form>';

                    return $actions;
                }
            )
            ->rawColumns(['actions'])
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        $domains = Domain::with('contact')->get();

        return $this->respondWithCollection($domains, new DomainTransformer());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auth_account = $this->auth_user->account;

        if (!$auth_account->isSystemAccount()) {
            $contacts = Contact::select('contacts.*')
                ->where('account_id', $auth_account->id)
                ->get()->pluck('name', 'id');
        } else {
            $contacts = Contact::pluck('name', 'id');
        }

        return view('domains.create')
            ->with('contact_selection', $contacts)
            ->with('selected', null)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Export listing to CSV format.
     *
     * @param string $format
     *
     * @return \Illuminate\Http\Response
     */
    public function export($format)
    {
        $auth_account = $this->auth_user->account;

        if ($auth_account->isSystemAccount()) {
            $domains = Domain::all();
        } else {
            $domains = Domain::select('domains.*')
                ->leftJoin('contacts', 'contacts.id', '=', 'domains.contact_id')
                ->leftJoin('accounts', 'accounts.id', '=', 'contacts.account_id')
                ->where('accounts.id', '=', $auth_account->id);
        }

        if ($format === 'csv') {
            $columns = [
                'contact' => 'Contact',
                'domain'  => 'Domain name',
                'enabled' => 'Status',
            ];

            $output = '"'.implode('","', $columns).'"'.PHP_EOL;

            foreach ($domains as $domain) {
                $row = [
                    $domain->contact->name.' ('.$domain->contact->reference.')',
                    $domain['name'],
                    $domain['enabled'] ? 'Enabled' : 'Disabled',
                ];

                $output .= '"'.implode('","', $row).'"'.PHP_EOL;
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
     * @param StoreDomainRequest $domainForm
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreDomainRequest $domainForm)
    {
        Domain::create($domainForm->all());

        return Redirect::route('admin.domains.index')
            ->with('message', 'Domain has been created');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDomainRequest $domainForm
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(StoreDomainRequest $domainForm)
    {
        $domain = Domain::create($domainForm->all());

        return $this->respondWithItem($domain, new DomainTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param Domain $domain
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        return view('domains.show')
            ->with('domain', $domain)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Display the specified resource.
     *
     * @param Domain $domain
     *
     * @return \Illuminate\Http\Response
     */
    public function apiShow(Domain $domain)
    {
        return $this->respondWithItem($domain, new DomainTransformer());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Domain $domain
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Domain $domain)
    {
        $auth_account = $this->auth_user->account;

        if (!$auth_account->isSystemAccount()) {
            $contacts = Contact::select('contacts.*')
                ->where('account_id', $auth_account->id)
                ->get()->pluck('name', 'id');
        } else {
            $contacts = Contact::pluck('name', 'id');
        }

        return view('domains.edit')
            ->with('domain', $domain)
            ->with('contact_selection', $contacts)
            ->with('selected', $domain->contact_id)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDomainRequest $domainForm
     * @param Domain            $domain
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateDomainRequest $domainForm, Domain $domain)
    {
        $domain->update($domainForm->all());

        return Redirect::route('admin.domains.show', $domain->id)
            ->with('message', 'Domain has been updated.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDomainRequest $domainForm
     * @param Domain            $domain
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apiUpdate(UpdateDomainRequest $domainForm, Domain $domain)
    {
        $domain->update($domainForm->all());

        return $this->respondWithItem($domain, new DomainTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Domain $domain
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();

        return Redirect::route('admin.domains.index')
            ->with('message', 'Domain has been deleted.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Domain $domain
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apiDestroy(Domain $domain)
    {
        $domain->delete();

        return $this->respondWithItem($domain, new DomainTransformer());
    }
}
