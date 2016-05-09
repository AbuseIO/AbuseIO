<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\NetblockFormRequest;
use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
use AbuseIO\Traits\Api;
use AbuseIO\Transformers\NetblockTransformer;
use Form;
use League\Fractal\Manager;
use Redirect;
use yajra\Datatables\Datatables;

/**
 * Class NetblocksController.
 */
class NetblocksController extends Controller
{
    use Api;

    /**
     * NetblocksController constructor.
     */
    public function __construct(Manager $fractal)
    {
        parent::__construct();

        $this->apiInit($fractal);

        // is the logged in account allowed to execute an action on the Domain
        $this->middleware('checkaccount:Netblock', ['except' => ['search', 'index', 'create', 'store', 'export']]);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $auth_account = $this->auth_user->account;

        $netblocks = Netblock::select('netblocks.*', 'contacts.name as contacts_name')
            ->leftJoin('contacts', 'contacts.id', '=', 'netblocks.contact_id');

        if (!$auth_account->isSystemAccount()) {
            $netblocks = $netblocks
                ->leftJoin('accounts', 'accounts.id', '=', 'contacts.account_id')
                ->where('accounts.id', '=', $auth_account->id);
        }

        return Datatables::of($netblocks)
            ->addColumn(
                'actions',
                function ($netblock) {
                    $actions = Form::open(
                        [
                            'route'  => ['admin.netblocks.destroy', $netblock->id],
                            'method' => 'DELETE',
                            'class'  => 'form-inline',
                        ]
                    );
                    $actions .= ' <a href="netblocks/'.$netblock->id.
                        '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-eye-open"></span> '.
                        trans('misc.button.show').'</a> ';
                    $actions .= ' <a href="netblocks/'.$netblock->id.
                        '/edit" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-edit"></span> '.
                        trans('misc.button.edit').'</a> ';
                    $actions .= Form::button(
                        '<span class="glyphicon glyphicon-remove"></span> '.trans('misc.button.delete'),
                        [
                            'type'  => 'submit',
                            'class' => 'btn btn-danger btn-xs',
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
        return view('netblocks.index')
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiIndex()
    {
        $netblocks = Netblock::all();

        if (count($netblocks) === 0) {

            return $this->errorNotFound('No netblocks where found in this system');
        }
        
        return $this->respondWithCollection($netblocks, new NetblockTransformer());
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
                ->get()->lists('name', 'id');
        } else {
            $contacts = Contact::lists('name', 'id');
        }

        return view('netblocks.create')
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
            $netblocks = Netblock::all();
        } else {
            $netblocks = Netblock::select('netblocks.*')
                ->leftJoin('contacts', 'contacts.id', '=', 'netblocks.contact_id')
                ->leftJoin('accounts', 'accounts.id', '=', 'contacts.account_id')
                ->where('accounts.id', '=', $auth_account->id);
        }

        if ($format === 'csv') {
            $columns = [
                'contact'   => 'Contact',
                'enabled'   => 'Status',
                'first_ip'  => 'First IP',
                'last_ip'   => 'Last IP',
            ];

            $output = '"'.implode('", "', $columns).'"'.PHP_EOL;

            foreach ($netblocks as $netblock) {
                $row = [
                    $netblock->contact->name.' ('.$netblock->contact->reference.')',
                    $netblock['first_ip'],
                    $netblock['last_ip'],
                    $netblock['enabled'] ? 'Enabled' : 'Disabled',
                ];

                $output .= '"'.implode('", "', $row).'"'.PHP_EOL;
            }

            return response(substr($output, 0, -1), 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="Netblocks.csv"');
        }

        return Redirect::route('admin.contacts.index')
            ->with('message', "The requested format {$format} is not available for exports");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NetblockFormRequest $netblockForm
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NetblockFormRequest $netblockForm)
    {
        Netblock::create($netblockForm->all());

        return Redirect::route('admin.netblocks.index')
            ->with('message', trans('netblocks.msg.added'));
    }

    /**
     * Display the specified resource.
     *
     * @param Netblock $netblock
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Netblock $netblock)
    {
        return view('netblocks.show')
            ->with('netblock', $netblock)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Display the specified resource.
     *
     * @param Netblock $netblock
     *
     * @return \Illuminate\Http\Response
     */
    public function apiShow(Netblock $netblock)
    {
        return $this->respondWithItem($netblock, new NetblockTransformer());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Netblock $netblock
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Netblock $netblock)
    {
        $auth_account = $this->auth_user->account;

        if (!$auth_account->isSystemAccount()) {
            $contacts = Contact::select('contacts.*')
                ->where('account_id', $auth_account->id)
                ->get()->lists('name', 'id');
        } else {
            $contacts = Contact::lists('name', 'id');
        }

        return view('netblocks.edit')
            ->with('netblock', $netblock)
            ->with('contact_selection', $contacts)
            ->with('selected', $netblock->contact_id)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NetblockFormRequest $netblockForm
     * @param Netblock            $netblock
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(NetblockFormRequest $netblockForm, Netblock $netblock)
    {
        $netblock->update($netblockForm->all());

        return Redirect::route('admin.netblocks.show', $netblock->id)
            ->with('message', trans('netblocks.msg.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Netblock $netblock
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Netblock $netblock)
    {
        $netblock->delete();

        return Redirect::route('admin.netblocks.index')
            ->with('message', trans('netblocks.msg.deleted'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Netblock $netblock
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apiDestroy(Netblock $netblock)
    {
        //$netblock->delete();

        return $this->respondWithItem($netblock, new NetblockTransformer());

    }
}
