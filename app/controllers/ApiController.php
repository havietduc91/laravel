<?php

class ApiController extends BaseController {

    /*
     * Response code of API call
     */
    protected $code;

    /**
     * Response data of API call
     * @var array
     */
    protected $data = array();

    /**
     * The moment API is requested
     * @var type
     */
    protected $apiRequestAt = '';

    /**
     * List response code of API call
     * @var type
     */
    protected $codes = array (
        'invalid_oauth_params'  =>  -5,
        'token_expired'         =>  -6,
        'unknown_error'         =>  -2,
        'login_required'        =>  -1,
        'success'               =>  0,
        'missing_params'        =>  1,
        'validate_failed'       =>  2,
        'news_not_exist'        =>  8,
        'shop_not_exist'        =>  9,
        'mall_not_exist'        =>  10,
        'already_checked_in'    =>  11,
        'mark_as_read_fail'     =>  12,
        'event_not_exist'       =>  13,
        'coupon_not_exist'      =>  14,
        'stamp_not_exist'       =>  15,
        'no_mall_around'        =>  16,
        'already_exchanged'     =>  17,
        'not_enough_checkin'    =>  18,
        'already_used_coupon'   =>  19
    );

    /*
     * List of API's return codes from Nadia VN
     */
    protected $nadiaCodes = array(
        'invalid_oauth_params'          =>  -5,
        'token_expired'                 =>  -6,
        'account_not_activated'         =>  -7,
        'account_banned'                =>  -8,
        'user_not_found'                =>  -9,
        'aeon_account_not_registered'   =>  -10,
        'account_already_activated'     =>  -11,
        'duplicate_password'            =>  -12,
        'invalid_password'              =>  -13
    );

    /*
     * Init
     */
    public function __construct()
    {
        //Merge child class CODES with parent class CODES
        $parentVar = get_class_vars(__CLASS__);
        $this->codes = array_merge($parentVar['codes'], $this->codes);
        $this->apiRequestAt = date('Y-m-d H:i:s');
        $debugMode = Config::get('app.debug');

        if ($debugMode) {
           // $this->beforeFilter('@logApiBefore');
        }


        if ($debugMode) {
           // $this->afterFilter('@logApiAfter');
        }

    }

    public function showWelcome()
    {
            return Response::json(['status' => 200, 'stats' => 1]);

            //return View::make('hello');
    }

}