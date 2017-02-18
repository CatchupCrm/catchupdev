<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDepartmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('departments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('departmenttype', 50)->default('public')->index('departments2');
			$table->boolean('isdefault')->default(0);
			$table->integer('slaplan_id')->unsigned()->nullable()->index('slaplan');
			$table->integer('manager_id')->unsigned()->nullable()->index('deptmngr');
			$table->string('department_signature');
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
		Schema::drop('departments');
	}

}
