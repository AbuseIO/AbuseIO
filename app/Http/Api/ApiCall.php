<?php

namespace AbuseIO\Http\Api;

abstract class ApiCall
{
    protected $errormsg = null;
    protected $errornum = 0;

    public function apiReturn($data)
    {
        return json_encode([
            'result' => $data,
            'error' => [
                'num' => $this->errornum,
                'msg' => $this->errormsg,
            ],
        ]);
    }

    protected function setError($num, $msg)
    {
        $this->errornum = $num;
        $this->errormsg = $msg;
    }
}
