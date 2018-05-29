<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Ticket;
use AbuseIO\Traits\Api;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Redirect;

class GdprController extends Controller
{
    use Api;

    /**
     * GdprController constructor.
     */
    public function __construct(Manager $fractal, Request $request)
    {
        parent::__construct();

        // initialize the api
        $this->apiInit($fractal, $request);
    }

    /**
     * Method to call the anonymization function from within the UI.
     *
     * @param Contact $contact
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function anonymize(Contact $contact)
    {
        try {
            $this->anonymizeData($contact->email);
            $message = 'Contact successfully anonymized.';
        } catch (\Exception $e) {
            $message = 'There was a problem anonymizing the contact. (Error: '.$e->getMessage().')';
        }

        return Redirect::route('admin.contacts.index')
                       ->with('message', $message);
    }

    /**
     * api method to anonymize all personal data related to the given email address.
     *
     * @param $email
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiAnonymize($email)
    {
        try {
            $this->anonymizeData($email);
        } catch (\Exception $e) {
            return $this->errorInternalError($e->getMessage());
        }

        return $this->respondWithArray([
            'data'    => [],
            'message' => $this->getMessage('success', 200),
        ]);
    }

    /**
     * Anonymize the contact data and all related tickets.
     *
     * @param $email
     */
    protected function anonymizeData($email)
    {
        $randomness = sprintf('%d', time());

        $contacts = Contact::withTrashed()->where('email', '=', $email)->get();
        $tickets = Ticket::withTrashed()->where('ip_contact_email', '=', $email)->get();
        $tickets = $tickets->merge(Ticket::withTrashed()->where('domain_contact_email', '=', $email)->get());

        foreach ($contacts as $contact) {
            $contact->anonymize($randomness);
        }
        foreach ($tickets as $ticket) {
            $ticket->anonymize($email, $randomness);
        }
    }
}
