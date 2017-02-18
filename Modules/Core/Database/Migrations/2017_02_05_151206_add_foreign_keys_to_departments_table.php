<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDepartmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('departments', function(Blueprint $table)
		{
			$table->foreign('manager_id', 'fk_department_manager')->references('id')->on('staff')->onUpdate('NO ACTION')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('departments', function(Blueprint $table)
		{
			$table->dropForeign('fk_department_manager');
		});
	}

}
