<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\ContactFormRequest;
use AbuseIO\Models\Contact;
use Form;
use Redirect;
use yajra\Datatables\Datatables;

/**
 * Class ContactsController.
 */
class ContactsController extends Controller
{
    /**
     * ContactsController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // is the logged in account allowed to execute an action on the Contact
        $this->middleware('checkaccount:Contact', ['except' => ['search', 'index', 'create', 'store', 'export']]);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $auth_account = $this->auth_user->account;
        if ($auth_account->isSystemAccount()) {
            $contacts = Contact::all();
        } else {
            $contacts = Contact::where('account_id', '=', $auth_account->id);
        }

        return Datatables::of($contacts)
            // Create the action buttons
            ->addColumn(
                'actions',
                function ($contact) {
                    $actions = Form::open(
                        [
                            'route'     => ['admin.contacts.destroy', $contact->id],
                            'method'    => 'DELETE',
                            'class'     => 'form-inline',
                        ]
                    );
                    $actions .= ' <a href="contacts/'.$contact->id.
                        '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> '.
                        trans('misc.button.show').'</a> ';
                    $actions .= ' <a href="contacts/'.$contact->id.
                        '/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> '.
                        trans('misc.button.edit').'</a> ';
                    $actions .= Form::button(
                        '<i class="glyphicon glyphicon-remove"></i> '.
                        trans('misc.button.delete'),
                        [
                            'type'  => 'submit',
                            'class' => 'btn btn-danger btn-xs',
                        ]
                    );
                    $actions .= Form::close();

                    return $actions;
                }
            )
            ->editColumn(
                'account_id',
                function ($contact) {
                    return $contact->account->name;
                }
            )
            // Replace auto_notify values for something readable.
            ->editColumn(
                'auto_notify',
                function ($contact) {
                    return empty($contact->auto_notify) ? trans('misc.manual') : trans('misc.automatic');
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
        return view('contacts.index')
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contacts.create')
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
            $contacts = Contact::all();
        } else {
            $contacts = Contact::select('contacts.*')
                ->leftJoin('accounts', 'accounts.id', '=', 'contacts.account_id')
                ->where('accounts.id', '=', $auth_account->id);
        }

        if ($format === 'csv') {
            $columns = [
                'reference'     => 'Reference',
                'contact'       => 'name',
                'enabled'       => 'Status',
                'email'         => 'E-Mail address',
                'api_host'      => 'RPC address',
                'auto_notify'   => 'Notifications',
            ];

            $output = '"'.implode('", "', $columns).'"'.PHP_EOL;

            foreach ($contacts as $contact) {
                $row = [
                    $contact->reference,
                    $contact->name,
                    $contact['enabled'] ? 'Enabled' : 'Disabled',
                    $contact['email'],
                    $contact['api_host'],
                    $contact['auto_notify'] ? 'Automatic' : 'Manual',
                ];

                $output .= '"'.implode('", "', $row).'"'.PHP_EOL;
            }

            return response(substr($output, 0, -1), 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="Contacts.csv"');
        }

        return Redirect::route('admin.contacts.index')
            ->with('message', "The requested format {$format} is not available for exports");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContactFormRequest $contactForm FormRequest
     * @param Contact            $contact     Contact
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ContactFormRequest $contactForm, Contact $contact)
    {
        $contact->create($contactForm->all());

        return Redirect::route('admin.contacts.index')
            ->with('message', 'Contact has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param Contact $contact
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return view('contacts.show')
            ->with('contact', $contact)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Contact $contact
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        return view('contacts.edit')
            ->with('contact', $contact)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ContactFormRequest $contactForm FormRequest
     * @param Contact            $contact     Contact
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ContactFormRequest $contactForm, Contact $contact)
    {
        $contact->update($contactForm->all());

        return Redirect::route('admin.contacts.show', $contact->id)
            ->with('message', 'Contact has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Contact $contact Contact
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        if ($contact->domains->count() > 0) {
            return Redirect::route('admin.contacts.index')->with(
                'message',
                'Contact could not be deleted because '.$contact->domains->count()
                .' domain(s) is stil pointing to this contact.'
            );
        }

        if ($contact->netblocks->count() > 0) {
            return Redirect::route('admin.contacts.index')->with(
                'message',
                'Contact could not be deleted because '.$contact->netblocks->count()
                .' netblock(s) is stil pointing to this contact.'
            );
        }

        $contact->delete();

        return Redirect::route('admin.contacts.index')
            ->with('message', 'Contact has been deleted.');
    }
}
