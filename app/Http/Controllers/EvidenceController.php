<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Models\Evidence;

/**
 * Class EvidenceController.
 */
class EvidenceController extends Controller
{
    /**
     * Evidence does not have an index list; provide a clean 404.
     */
    public function index()
    {
        return abort(404);
    }

    /**
     * EvidenceController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware(\AbuseIO\Http\Middleware\CheckAccount::class.':Evidence');
    }

    /**
     * Display the specified evidence.
     *
     * @param \AbuseIO\Models\Evidence $evidence
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Evidence $evidence)
    {
        return view('evidence.show')
            ->with('evidence', $evidence)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Download eml evidence.
     *
     * @param \AbuseIO\Models\Evidence $evidence
     *
     * @return \Illuminate\Http\Response
     */
    public function download(Evidence $evidence)
    {
        $fullPath = storage_path()."/{$evidence->filename}";

        // If the file exists but is not readable by the web server, report a permission issue
        if (is_string($evidence->filename) && $evidence->filename !== '' && file_exists($fullPath) && !is_readable($fullPath)) {
            \Log::warning(get_class($this).': Evidence file exists but is not readable: '.$fullPath);

            return response()->view('errors.403', ['message' => 'Evidence file exists but is not readable by the web server.'], 403);
        }

        $eml = $evidence->eml;

        if ($eml !== false) {
            return response($eml, 200)
                ->header('Content-Type', 'message/rfc822')
                ->header('Content-Transfer-Encoding', 'Binary')
                ->header('Content-Disposition', "attachment; filename=\"abuseio_evidence_{$evidence->id}.eml\"");
        }

        // Distinguish not found vs. unreadable
        if (file_exists($fullPath)) {
            \Log::warning(get_class($this).': Evidence file exists but reading failed (likely permission): '.$fullPath);

            return response()->view('errors.403', ['message' => 'Evidence file exists but cannot be read due to permissions.'], 403);
        }

        return abort(404);
    }

    /**
     * Download a specific attachment.
     *
     * @param \AbuseIO\Models\Evidence $evidence
     * @param string                   $filename [description]
     *
     * @return \Illuminate\Http\Response
     */
    public function attachment(Evidence $evidence, $filename)
    {
        $fullPath = storage_path()."/{$evidence->filename}";

        // If the file exists but is not readable by the web server, report a permission issue
        if (is_string($evidence->filename) && $evidence->filename !== '' && file_exists($fullPath) && !is_readable($fullPath)) {
            \Log::warning(get_class($this).': Evidence file exists but is not readable: '.$fullPath);

            return response()->view('errors.403', ['message' => 'Evidence file exists but is not readable by the web server.'], 403);
        }

        if ($attachment = $evidence->getAttachment($filename)) {
            // sanitize filename for header injection and path traversal
            $safeFilename = basename(preg_replace("/[\r\n\"]/", '', (string) $filename));

            return response($attachment->getContent(), 200)
                ->header('Content-Type', $attachment->getContentType())
                ->header('Content-Transfer-Encoding', 'Binary')
                ->header('Content-Disposition', "attachment; filename=\"{$safeFilename}\"");
        } else {
            // Distinguish not found vs. unreadable
            if (file_exists($fullPath)) {
                \Log::warning(get_class($this).': Attachment read failed, evidence file exists: '.$fullPath);

                return response()->view('errors.403', ['message' => 'Evidence file exists but cannot be read due to permissions.'], 403);
            }

            return abort(404);
        }
    }
}
