<?php

use Illuminate\Auth\UserInterface;

class User extends BaseModel implements UserInterface{

    protected $guarded = array('user_id');

    protected $primaryKey = 'user_id';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    protected $fillable = array('login_id', 'login_type', 'password', 'is_reseted_password', 'role','date_created_password', 'rememberToken', 'init_password');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return Hash::make($this->password);
        // return Hash::make(Crypt::decrypt($this->password));
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->RememberToken;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->RememberToken = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'RememberToken';
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->Email;
    }

    /*
     * Relationship between User and Shop
     */
    public function shop()
    {
        return $this->hasOne('Shop');
    }

    /*
     * Relationship between User and Mall
     */
    public function mall()
    {
        return $this->hasOne('Mall');
    }

    /**
     * Get login number for user
     * @param type $userId
     * @param type $startDate
     * @param type $endDate
     * @return login number
     */
    public static function getLoginNumber($userId, $startDate, $endDate)
    {
        $number = self::where('user_id', '=', $userId)
                        ->where('created_at', '>=', $startDate)
                        ->where('created_at', '<=', $endDate)
                        ->count();

        return $number;
    }

    /**
     * set init $errorMsg and return msg error
     * @param type $viewKey
     * @param type $msgKey
     * @return boolean
     */
    private static function loginFailMsg($viewKey, $msgKey, &$errorMsg)
    {
        $errorMsg = new Illuminate\Support\MessageBag();
        $errorMsg->add($viewKey, Lang::get($msgKey));
        return false;
    }
}
