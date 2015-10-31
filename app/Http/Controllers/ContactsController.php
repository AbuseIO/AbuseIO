<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\ContactFormRequest;
use AbuseIO\Models\Contact;
use Redirect;
use Input;

class ContactsController extends Controller
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
        $contacts = Contact::paginate(10);

        return view('contacts.index')
            ->with('contacts', $contacts)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('contacts.create')
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Export listing to CSV format.
     * @return Response
     */
    public function export()
    {
        $contacts  = Contact::all();

        $columns = [
            'reference'     => 'Reference',
            'contact'       => 'name',
            'enabled'       => 'Status',
            'email'         => 'E-Mail address',
            'rpc_host'      => 'RPC address',
            'rpc_key'       => 'RPC key',
            'auto_notify'   => 'Notifications',
        ];

        $output = '"' . implode('", "', $columns) . '"' . PHP_EOL;

        foreach ($contacts as $contact) {
            $row = [
                $contact->reference,
                $contact->name,
                $contact['enabled'] ? 'Enabled' : 'Disabled',
                $contact['email'],
                $contact['rpc_host'],
                $contact['rpc_key'],
                $contact['auto_notify'] ? 'Automatic' : 'Manual',
            ];

            $output .= '"' . implode('", "', $row) . '"' . PHP_EOL;
        }

        return response(substr($output, 0, -1), 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="Contacts.csv"');
    }

    /**
     * Store a newly created resource in storage.
     * @return Response
     */
    public function store(ContactFormRequest $contact)
    {
        $account = $this->auth_user->account;
        $input = Input::all();
        $input['account_id'] = $account->id;

        Contact::create($input);

        return Redirect::route('admin.contacts.index')
            ->with('message', 'Contact has been created');
    }

    /**
     * Display the specified resource.
     * @param Contact $contact
     * @return Response
     * @internal param int $id
     */
    public function show(Contact $contact)
    {
        return view('contacts.show')
            ->with('contact', $contact)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for editing the specified resource.
     * @param Contact $contact
     * @return Response
     * @internal param int $id
     */
    public function edit(Contact $contact)
    {
        return view('contacts.edit')
            ->with('contact', $contact)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     * @param  int  $id
     * @return Response
     */
    public function update(Contact $contact)
    {
        $account = $this->auth_user->account;
        $input = array_except(Input::all(), '_method');
        $input['account_id'] = $account->id;

        $contact->update($input);

        return Redirect::route('admin.contacts.show', $contact->id)
            ->with('message', 'Contact has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return Response
     */
    public function destroy(Contact $contact)
    {
        if ($contact->domains->count() > 0) {
            return Redirect::route('admin.contacts.index')->with(
                'message',
                "Contact could not be deleted because ".  $contact->domains->count()
                . " domain(s) is stil pointing to this contact."
            );
        }

        if ($contact->netblocks->count() > 0) {
            return Redirect::route('admin.contacts.index')->with(
                'message',
                "Contact could not be deleted because ".  $contact->netblocks->count()
                . " netblock(s) is stil pointing to this contact."
            );
        }

        $contact->delete();

        return Redirect::route('admin.contacts.index')
            ->with('message', 'Contact has been deleted.');
    }
}
