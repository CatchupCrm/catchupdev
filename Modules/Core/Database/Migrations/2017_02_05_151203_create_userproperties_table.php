<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserpropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('userproperties', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('userid')->default(0);
			$table->string('keyname')->default('');
			$table->string('keyvalue')->default('');
			$table->timestamps();
			$table->index(['userid','keyname'], 'userproperties1');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('userproperties');
	}

}
