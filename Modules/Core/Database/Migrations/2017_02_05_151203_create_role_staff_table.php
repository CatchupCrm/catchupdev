<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoleStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('role_staff', function(Blueprint $table)
		{
			$table->integer('staff_id')->unsigned();
			$table->integer('role_id')->unsigned()->index('role_staff_role_id_foreign');
			$table->primary(['staff_id','role_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('role_staff');
	}

}
