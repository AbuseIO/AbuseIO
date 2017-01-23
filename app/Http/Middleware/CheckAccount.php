<?php

namespace AbuseIO\Http\Middleware;

use AbuseIO\Models\Account;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Log;

/**
 * Class CheckAccount.
 */
class CheckAccount
{
    const ID_SEGMENT = 3;

    private $model_id;

    private $request;

    private $model;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param $model
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $model)
    {
        // add the full model path
        $model = sprintf("\\AbuseIO\\Models\\%s", $model);

        $this->request = $request;
        $this->model = $model;

        if ($this->checkModelIdValid()
            && $this->hasAccountAccessMethod()
            && !$model::checkAccountAccess($this->model_id, $this->getAccount())
        ) {
            return $this->getResponseForNoAccessToModel();
        }

        return $next($request);
    }

    private function hasAccountAccessMethod()
    {
        if (!method_exists($this->model, 'checkAccountAccess')) {
            Log::notice(
                "CheckAccount Middleware is called, with model_id [{$this->model_id}] for {$this->model}, " .
                "which doesn't have the 'checkAccountAccess' method"
            );

            return false;
        }

        return true;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    private function getResponseForNoAccessToModel()
    {
        // todo implement proper AJAX response;
        if ($this->request->ajax()) {
            //return
        }

        return back()->with('message', 'You are not allowed to access this object');
    }

    /**
     * @return Account
     */
    private function getAccount()
    {
        return $this->request->ajax() ? $this->request->api_account : Auth::user()->account;
    }

    /**
     * @param Request $request
     */
    private function resolveModelId($request)
    {
        $model_id = $request->segment(self::ID_SEGMENT);
        if (empty($model_id)) {
            $model_id = $request->input('id');
        }

        $this->model_id = $model_id;
    }

    /**
     * @return bool
     */
    private function checkModelIdValid()
    {
        $this->resolveModelId($this->request);

        if (!empty($this->model_id) && preg_match('/\d+/', $this->model_id)) {
            return true;
        }

        Log::notice(
            "CheckAccount Middleware is called, with model_id [{$this->model_id}] for {$this->model}, which doesn't match the model_id format"
        );

        return false;
    }
}
