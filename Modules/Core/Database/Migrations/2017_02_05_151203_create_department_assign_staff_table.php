<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDepartmentAssignStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('department_assign_staff', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('department_id')->unsigned()->index('depstaff_dept');
			$table->integer('staff_id')->unsigned()->index('depstaff_staff');
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
		Schema::drop('department_assign_staff');
	}

}
