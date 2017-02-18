<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('staff', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('username')->unique('staff_staffname_unique');
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
			$table->boolean('enabled')->default(0);
			$table->string('auth_type')->nullable();
			$table->boolean('confirmed')->default(0);
			$table->string('confirmation_code')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('staff');
	}

}
