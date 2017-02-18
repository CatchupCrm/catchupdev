<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTeamAssignStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('team_assign_staff', function(Blueprint $table)
		{
			$table->foreign('staff_id', 'fk_team_assign_staff')->references('id')->on('staff')->onUpdate('NO ACTION')->onDelete('RESTRICT');
			$table->foreign('team_id', 'fk_team_assign_team')->references('id')->on('teams')->onUpdate('NO ACTION')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('team_assign_staff', function(Blueprint $table)
		{
			$table->dropForeign('fk_team_assign_staff');
			$table->dropForeign('fk_team_assign_team');
		});
	}

}
