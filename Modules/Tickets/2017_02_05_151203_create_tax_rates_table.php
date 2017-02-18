<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaxRatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tax_rates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index('tax_rates_company_id_index');
			$table->string('name');
			$table->integer('user_id')->unsigned()->index('tax_rates_user_id_foreign');
			$table->integer('public_id')->unsigned();
			$table->decimal('rate', 13, 3);
			$table->boolean('is_inclusive')->default(0);
			$table->timestamps();
			$table->softDeletes();
			$table->unique(['company_id','public_id'], 'tax_rates_company_id_public_id_unique');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tax_rates');
	}

}
