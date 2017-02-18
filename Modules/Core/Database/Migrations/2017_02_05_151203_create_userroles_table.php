<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserrolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('userroles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->unique('roles_name_unique');
			$table->string('display_name')->nullable();
			$table->string('description')->nullable();
			$table->timestamps();
			$table->boolean('enabled')->default(0);
			$table->boolean('resync_on_login')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('userroles');
	}

}
