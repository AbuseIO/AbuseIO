<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\ContactFormRequest;
use AbuseIO\Models\Account;
use AbuseIO\Models\Contact;
use AbuseIO\Services\NotificationService;
use AbuseIO\Traits\Api;
use AbuseIO\Transformers\ContactTransformer;
use Form;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Redirect;
use yajra\Datatables\Datatables;

/**
 * Class ContactsController.
 */
class ContactsController extends Controller
{
    use Api;

    private $error;

    /**
     * ContactsController constructor.
     *
     * @param Manager $fractal
     * @param Request $request
     */
    public function __construct(Manager $fractal, Request $request)
    {
        parent::__construct();

        // initialize the Api methods
        $this->apiInit($fractal, $request);
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
                            'route'  => ['admin.contacts.destroy', $contact->id],
                            'method' => 'DELETE',
                            'class'  => 'form-inline',
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
                    return empty($contact->account->name) ? null : $contact->account->name;
                }
            )
            // Replace auto_notify values for something readable.
            ->editColumn(
                'auto_notify',
                function ($contact) {
                    return empty($contact->auto_notify()) ? trans('misc.manual') : trans('misc.automatic');
                }
            )
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Return the contact which matches the given email.
     *
     * @param $email
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiSearch($email)
    {
        $contacts = Contact::withTrashed()->where('email', '=', $email)->get();

        return $this->respondWithCollection($contacts, new ContactTransformer());
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiIndex()
    {
        $contacts = Contact::all();

        return $this->respondWithCollection($contacts, new ContactTransformer());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contacts.create')
            ->with('auth_user', $this->auth_user)
            ->with('notificationService', new NotificationService())
            ->with('accounts', Account::pluck('name', 'id'))
            ->with('selectedAccount', $this->auth_user->account_id)
            ->with('contact', null);
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
                'reference'   => 'Reference',
                'contact'     => 'name',
                'enabled'     => 'Status',
                'email'       => 'E-Mail address',
                'api_host'    => 'RPC address',
                'auto_notify' => 'Notifications',
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
        $c = $contact->create($contactForm->all());

        $c->syncNotificationMethods($contactForm);

        return Redirect::route('admin.contacts.index')
            ->with('message', 'Contact has been created');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContactFormRequest $contactForm FormRequest
     * @param Contact            $contact     Contact
     *
     * @return \Illuminate\Http\Response
     */
    public function apiStore(ContactFormRequest $contactForm, Contact $contact)
    {
        $c = $contact->create($contactForm->all());

        return $this->respondWithItem($c, new ContactTransformer());
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
     * Display the specified resource.
     *
     * @param Contact $contact
     *
     * @return \Illuminate\Http\Response
     */
    public function apiShow(Contact $contact)
    {
        return $this->respondWithItem($contact, new ContactTransformer());
    }

    /**
     * Api method which anonymizes the specified Contact.
     *
     * @param Contact $contact
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiAnonymize(Contact $contact, $randomness)
    {
        $updated = $contact->anonymize($randomness);

        return $this->respondWithItem($updated, new ContactTransformer());
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
            ->with('auth_user', $this->auth_user)
            ->with('accounts', Account::pluck('name', 'id'))
            ->with('selectedAccount', $contact->account_id)
            ->with('notificationService', new NotificationService());
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

        $contact->syncNotificationMethods($contactForm);

        return Redirect::route('admin.contacts.show', $contact->id)
            ->with('message', 'Contact has been updated.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ContactFormRequest $contactForm FormRequest
     * @param Contact            $contact     Contact
     *
     * @return \Illuminate\Http\Response
     */
    public function apiUpdate(ContactFormRequest $contactForm, Contact $contact)
    {
        $contact->update(
            $contactForm->all()
        );

        $contact->syncNotificationMethods($contactForm);

        return $this->respondWithItem($contact, new ContactTransformer());
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
        if (!$this->handleDestroy($contact)) {
            return Redirect::route('admin.contacts.index')->with(
                'message',
                $this->getError()
            );
        }

        return Redirect::route('admin.contacts.index')
            ->with('message', 'Contact has been deleted.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Contact $contact Contact
     *
     * @return \Illuminate\Http\Response
     */
    public function apiDestroy(Contact $contact)
    {
        if (!$this->handleDestroy($contact)) {
            $this->respondWithValidationErrors($this->getError());
        }

        return $this->respondWithItem($contact, new ContactTransformer());
    }

    private function handleDestroy(Contact $contact)
    {
        if ($contact->domains->count() > 0) {
            $this->setError(
                sprintf(
                    'Contact could not be deleted because %d domain(s) is still pointing to this contact.',
                    $contact->domains->count()
                )
            );

            return false;
        }

        if ($contact->netblocks->count() > 0) {
            $this->setError(
                sprintf(
                    'Contact could not be deleted because %s netblock(s) is still pointing to this contact.',
                    $contact->netblocks->count()
                )
            );

            return false;
        }

        $contact->delete();

        return true;
    }

    private function setError($error)
    {
        $this->error = $error;
    }

    private function getError()
    {
        return $this->error;
    }
}
