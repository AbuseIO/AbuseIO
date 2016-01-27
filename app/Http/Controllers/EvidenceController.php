<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Models\Event;
use AbuseIO\Models\Evidence;

/**
 * Class EvidenceController
 * @package AbuseIO\Http\Controllers
 */
class EvidenceController extends Controller
{
    /**
     * Display the specified evidence.
     *
     * @param \AbuseIO\Models\Evidence $evidence
     * @return \Illuminate\Http\Response
     */
    public function show(Evidence $evidence)
    {
        return view('evidence.show')
            ->with('evidence', $evidence)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Download eml evidence
     *
     * @param  \AbuseIO\Models\Evidence $evidence
     * @return \Illuminate\Http\Response
     */
    public function download(Evidence $evidence)
    {
        $eml = $evidence->eml;

        if ($eml) {
            return response($eml, 200)
                ->header('Content-Type', 'message/rfc822')
                ->header('Content-Transfer-Encoding', 'Binary')
                ->header('Content-Disposition', "attachment; filename=\"abuseio_evidence_{$evidence->id}.eml\"");
        } else {
            return abort(404);
        }
    }

    /**
     * Download a specific attachment
     *
     * @param  \AbuseIO\Models\Evidence $evidence
     * @param  string                   $filename [description]
     * @return \Illuminate\Http\Response
     */
    public function attachment(Evidence $evidence, $filename)
    {
        if ($attachment = $evidence->getAttachment($filename)) {
            return response($attachment->getContent(), 200)
                ->header('Content-Type', $attachment->getContentType())
                ->header('Content-Transfer-Encoding', 'Binary')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
        } else {
            return abort(404);
        }
    }
}
