<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(strtolower('leads'), function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->index();
            $table->unsignedInteger('relation_id')->index()->nullable();

            $table->unsignedInteger('leadid')->index();


            $table->string('title')->nullable();
            $table->integer('status_id')->unsigned()->nullable();
            $table->string('email')->unique();
            $table->string('first_name');
            $table->string('last_name');

            $table->string('company')->nullable();
            $table->integer('num_of_employees')->unsigned()->nullable();
            $table->string('website')->nullable();
            $table->decimal('annual_revenue', 14, 0)->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('fax')->nullable();
            $table->boolean('do_not_call')->nullable();
            $table->boolean('do_not_email')->nullable();
            $table->boolean('do_not_fax')->nullable();
            $table->boolean('email_opt_out')->nullable();
            $table->boolean('fax_opt_out')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->integer('salutation_id')->unsigned()->nullable();
            $table->integer('lead_source_id')->unsigned()->nullable();


            $table->date('converted_at')->nullable();

            $table->text('description', 65535)->nullable();


            $table->unsignedInteger('user_id')->index();
            $table->boolean('read_by_owner')->nullable();
            $table->integer('owner_id')->unsigned()->nullable();
            $table->integer('adder_id')->unsigned()->nullable();
            $table->integer('modifier_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();


            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('relation_id')->references('id')->on('clients')->onDelete('cascade');

            $table->unique(['company_id', 'leadid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(strtolower('leads'));
    }
}
