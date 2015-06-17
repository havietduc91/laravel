<?php

class ApiUserController extends ApiController {

    protected $module = 'member';

    protected $codes = array(
        'email_exist'       =>  3,
        'wrong_passcode'    =>  4,
        'passcode_expired'  =>  5,
        'wrong_credential'  =>  6,
        'account_disabled'  =>  7
    );

    /**
     * Get new access token for member
     *
     * @param type $token
     * @return type
     */
    public function hello()
    {
        return View::make('hello');
    }
}