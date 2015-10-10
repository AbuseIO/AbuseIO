<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesCommands, ValidatesRequests;

    public function __construct($resourceAcl = false)
    {
        if ($resourceAcl !== false) {
            /*
             * Apply ACL restrictions to resource controller
             */
            $this->middleware("acl:admin_{$resourceAcl}_view", ['only' => ['index', 'show']]);
            $this->middleware("acl:admin_{$resourceAcl}_create", ['only' => ['create', 'store']]);
            $this->middleware("acl:admin_{$resourceAcl}_export", ['only' => ['export']]);
            $this->middleware("acl:admin_{$resourceAcl}_delete", ['only' => ['destroy']]);
            $this->middleware("acl:admin_{$resourceAcl}_edit", ['only' => ['edit', 'update']]);
        }
    }
}
