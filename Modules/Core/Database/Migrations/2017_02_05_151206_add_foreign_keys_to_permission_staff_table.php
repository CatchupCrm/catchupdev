<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPermissionStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('permission_staff', function(Blueprint $table)
		{
			$table->foreign('permission_id')->references('id')->on('permissions')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('staff_id')->references('id')->on('staff')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('permission_staff', function(Blueprint $table)
		{
			$table->dropForeign('permission_staff_permission_id_foreign');
			$table->dropForeign('permission_staff_staff_id_foreign');
		});
	}

}
