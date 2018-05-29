<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Ticket;
use AbuseIO\Traits\Api;
use Illuminate\Http\Request;
use League\Fractal\Manager;

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

    public function anonimize(Contact $contact)
    {
        //
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
        $randomness = sprintf('%d', time());

        try {
            $contacts = Contact::withTrashed()->where('email', '=', $email)->get();
            $tickets = Ticket::withTrashed()->where('ip_contact_email', '=', $email)->get();
            $tickets = $tickets->merge(Ticket::withTrashed()->where('domain_contact_email', '=', $email)->get());

            foreach ($contacts as $contact) {
                $contact->anonymize($randomness);
            }
            foreach ($tickets as $ticket) {
                $ticket->anonymize($email, $randomness);
            }
        } catch (\Exception $e) {
            return $this->errorInternalError($e->getMessage());
        }

        return $this->respondWithArray([
            'data'    => [],
            'message' => $this->getMessage('success', 200),
        ]);
    }
}
