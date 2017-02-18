<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(strtolower('products'), function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('company_id')->index('products_account_id_index');

            $table->unsignedInteger('productid')->unsigned()->index();
      			$table->string('product_key');

      			$table->decimal('cost', 13);
      			$table->decimal('qty', 13)->nullable();

      			$table->integer('default_tax_rate_id')->unsigned()->nullable();


            $table->unsignedInteger('vendor_id')->index()->nullable();


      			$table->text('notes', 65535);


            $table->unsignedInteger('user_id')->index('products_user_id_foreign');
            $table->softDeletes();
            $table->boolean('is_deleted')->default(false);


            $table->timestamps();


            $table->foreign('company_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');


            $table->unique( ['company_id', 'public_id'], 'products_account_id_public_id_unique' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(strtolower('products'));
    }
}
