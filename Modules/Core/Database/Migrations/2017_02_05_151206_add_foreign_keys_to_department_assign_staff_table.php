<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDepartmentAssignStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('department_assign_staff', function(Blueprint $table)
		{
			$table->foreign('department_id', 'depstaff_dept')->references('id')->on('departments')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('staff_id', 'depstaff_staff')->references('id')->on('staff')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('department_assign_staff', function(Blueprint $table)
		{
			$table->dropForeign('depstaff_dept');
			$table->dropForeign('depstaff_staff');
		});
	}

}
