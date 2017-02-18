<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketsHelptopicsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tickets__helptopics', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('topic', 100)->index('topic');
			$table->string('parent_topic', 100);
			$table->integer('custom_form')->unsigned()->nullable();
			$table->integer('department_id')->unsigned()->nullable()->index('department');
			$table->integer('ticketstatus_id')->unsigned()->nullable()->index('ticketstatus');
			$table->integer('ticketpriority_id')->unsigned()->nullable()->index('ticketpriority');
			$table->integer('slaplan_id')->unsigned()->nullable()->index('slaplan');
			$table->string('thank_page');
			$table->string('ticket_num_format');
			$table->boolean('status')->default(1);
			$table->boolean('type')->default(0);
			$table->integer('auto_assign')->unsigned()->nullable()->index('auto_assign_2');
			$table->boolean('auto_response')->default(0);
			$table->string('internal_notes');
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
		Schema::drop('tickets__helptopics');
	}

}
