<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->bigIncrements('user_id');
                        $table->string('login_id',255 );
                        $table->string('password', 255);
                        $table->tinyInteger('is_reseted_password');
                        $table->tinyInteger('role');
                        $table->bigInteger('role_id'); 
                        $table->date('date_created_password');
                         
                        $table->integer('login_count');
                        $table->string('sc_code', 255);
                        $table->string('rememberToken',255 );
                        $table->string('init_password',255 );
                        
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
