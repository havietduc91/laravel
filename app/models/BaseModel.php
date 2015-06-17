<?php

/*
 * Provide additional function to Eloquent objects
 */

class BaseModel extends Eloquent {

    static $untrimFields = array();

    protected $rules = array();

    protected $errors = array();

    /*
     * Return date string instead of carbon object
     */
    public function getDates()
    {
        return array();
    }

    /**
     * Overrided save method of Model to validate before save
     *
     * @return boolean
     */
    public function save(array $options = Array())
    {
        if ($this->validate($this->attributes)) {
            $this->attributes = self::trimData($this->attributes);
            return parent::save($options);
        }

        return false;
    }

    /**
     * Overrided firstOrCreate method of Model to trim data
     *
     * @return object
     */
    public static function firstOrCreate(array $data = array())
    {
        $trimedData = self::trimData($data);
        return parent::firstOrCreate($trimedData);
    }

    /**
     * Overrided firstOrNew method of Model to trim data
     *
     * @return object
     */
    public static function firstOrNew(array $data = array())
    {
        $trimedData = self::trimData($data);
        return parent::firstOrNew($trimedData);
    }

    /**
     * Get errors message
     */
    public function errors()
    {
        return $this->errors;
    }

    /*
     * Get current rules
     */
    public function getRules()
    {
        return $this->rules;
    }

    /*
     * Update rules with new rules array
     */
    public function updateRules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * Update some rules of model with new rules
     *
     * @param type $updateRules
     */
    public function updateSomeRules($updateRules)
    {
        if (!empty($updateRules) && is_array($updateRules)) {
            $currentRules = $this->rules;

            foreach ($updateRules as $key => $value) {
                $currentRules[$key] = $value;
            }

            $this->updateRules($currentRules);
        }
    }

    /**
     * Clear all rules except some rules.
     *
     * @param array $keepRules
     */
    public function clearRulesWithExcept($keepRules = array())
    {
        $currentRules = $this->rules;

        foreach ($currentRules as $key => $val) {
            if (!empty($keepRules) && is_array($keepRules) && in_array($key, $keepRules)) {
                continue;
            }

            unset($currentRules[$key]);
        }

        $this->updateRules($currentRules);
    }

    /*
     * Validate data for API call
     */
    public static function apiValidateData($data, &$errors)
    {
        $model = new static();

        if (!$model->validate($data)) {
            $errors = $model->errors();
            return false;
        }

        return true;
    }

    /*
     * Add error not found
     */
    public static function addErrorNotFound(&$errors)
    {

        if(is_null($errors) || empty($errors)){
            $errors = new Illuminate\Support\MessageBag();
        }
        $errors->add('not_found', Lang::get('message.admin.mall.error.data_not_found'));
    }

    /***
     * @param $type (shop|mall)
     */
    public static function getUserForObject($type = 'shop')
    {
        $role = ($type == 'shop') ? Constants::ROLE_SHOP : Constants::ROLE_MALL;
        $user = User::create( array(
                        "login_id"              => Utility::getRandomLoginId($type),
                        "password"              => md5(Constants::PASSWORD_BLANK),
                        "init_password"         => md5(Constants::PASSWORD_DEFAULT),
                        "role"                  => $role
                    ));

        return $user;
    }

    /**
     * replace html entities
     * @param type $htmlEntities is array('&amp;' => '&', '&lt;' => '<', ...)
     * @param type $field (shop_name)
     * @return $replaceStr
     */
    public static function replaceHtmlEntities($htmlEntities, $field)
    {
        $replaceStr = '';
        if (!is_array($htmlEntities)) {
            return '';
        }

        if (count($htmlEntities) > 0)
        {
            $count = 0;
            foreach ($htmlEntities as $special => $character)
            {
                if (!empty($special) && !empty($character))
                {
                    if ($count == 0) {
                        $replaceStr = "REPLACE($field, '$special', '$character')";
                    } else {
                        $replaceStr = "REPLACE($replaceStr, '$special', '$character')";
                    }

                    $count ++;
                }
            }
        }

        return $replaceStr;
    }

    /**
     * Validate data before save model to DB
     *
     * @param array $input
     * @return boolean
     */
    protected function validate($input)
    {
        $data = self::trimData($input);

        $keyName = $this->getKeyName();
        if (empty($keyName) || is_array($keyName)) {
            $keyName = 'id';
        }

        $replace = isset($data[$keyName]) ? $data[$keyName] : '';

        foreach ($this->rules as $key => $rule) {
            $this->rules[$key] = str_replace(':id', $replace, $rule);
        }
        //replace all data field to value
        $this->addDynamicValuetoRule($data);

        $validator = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            $this->errors = $validator->errors();
            return false;
        }

        return true;
    }

    /**
     * Trim input data
     *
     * @param array $data
     * @return array $data
     */
    protected static function trimData($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        foreach ($data as $key => $value) {
            if (!in_array($key, static::$untrimFields) && is_string($value)) {
                $data[$key] = trim($value);
            }
        }

        return $data;
    }

    /*
     * Get current login user info to use in other model
     */
    protected static function getCurrentUser($role)
    {
        if (empty($role)) {
            return null;
        }

        return Auth::$role()->get();
    }

    /**
     * replace all parram
     *
     * @param type $data
     */
    private function addDynamicValuetoRule($data)
    {
         foreach ($this->rules as $key => $rule) {

            if (strpos($rule,'::') !== false) {
                foreach ($data as $field => $value) {
                    $keyReplace = '::'.$field;
                    if (strpos($rule, $keyReplace) !== false) {
                        $rule = str_replace($keyReplace, $value, $rule);
                    }
                }
                $this->rules[$key] = $rule;
            }

        }
    }
}
