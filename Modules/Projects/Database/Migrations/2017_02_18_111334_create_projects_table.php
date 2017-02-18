<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(strtolower('projects'), function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('company_id')->index();
            $table->unsignedInteger('relaton_id')->index()->nullable();

            $table->unsignedInteger('projectid')->index();

            $table->string('name')->nullable();

            $table->unsignedInteger('user_id')->index();


            $table->softDeletes();
            $table->boolean('is_deleted')->default(false);


            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('relaton_id')->references('id')->on('clients')->onDelete('cascade');


            $table->unique(['company_id', 'public_id'], 'projects_company_id_public_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(strtolower('projects'));
    }
}
