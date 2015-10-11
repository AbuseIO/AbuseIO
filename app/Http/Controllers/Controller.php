<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use URL;

abstract class Controller extends BaseController
{
    use DispatchesCommands, ValidatesRequests;

    public function __construct($createAcl = false)
    {
        if ($createAcl !== false) {
            /*
             * Apply ACL restrictions to resource controller
             *
             * This is based on the main route e.g. /admin/contacts/1/delete were we just need 'contacts' to
             * determine the ACL to be applied. With the Request::path returning / we need to parse the URL ourselves
             *
             */
            $resourceAcl = parse_url(URL::current());
            $resourceAcl = explode('/', $resourceAcl['path']);
            $resourceAcl = $resourceAcl[2];

            $this->middleware("acl:admin_{$resourceAcl}_view", ['only' => ['index', 'show']]);
            $this->middleware("acl:admin_{$resourceAcl}_create", ['only' => ['create', 'store']]);
            $this->middleware("acl:admin_{$resourceAcl}_export", ['only' => ['export']]);
            $this->middleware("acl:admin_{$resourceAcl}_delete", ['only' => ['destroy']]);
            $this->middleware("acl:admin_{$resourceAcl}_edit", ['only' => ['edit', 'update']]);
        }
    }
}
