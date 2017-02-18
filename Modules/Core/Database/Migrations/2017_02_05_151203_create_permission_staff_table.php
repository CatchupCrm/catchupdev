<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permission_staff', function(Blueprint $table)
		{
			$table->integer('permission_id')->unsigned();
			$table->integer('staff_id')->unsigned()->index('permission_staff_staff_id_foreign');
			$table->primary(['permission_id','staff_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('permission_staff');
	}

}
