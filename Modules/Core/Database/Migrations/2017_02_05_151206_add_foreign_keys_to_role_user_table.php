<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRoleUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('role_user', function(Blueprint $table)
		{
			$table->foreign('role_id', 'fk_roleuser_userrole')->references('id')->on('userroles')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('role_user', function(Blueprint $table)
		{
			$table->dropForeign('fk_roleuser_userrole');
		});
	}

}
