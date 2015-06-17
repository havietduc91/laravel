<?php

class UserSeeder extends DatabaseSeeder {
    public function run()
    {
        DB::table('users')->truncate();

        //admin user
        $user = array (
            "login_id"              => "admin",
            //"password"              => md5(Constants::PASSWORD_DEFAULT),
            //"init_password"         => md5(Constants::PASSWORD_DEFAULT),
            "password"              => '123',
            "role"                  => 1,
            'date_created_password' => date('Y-m-d H:i:s')
        );

        $model = new User();
        $model->fill($user);

        // Clear rules to avoid fail
        $model->updateRules(array());

        if (!$model->save()) {
            dd($model->errors());
        }
    }

}
