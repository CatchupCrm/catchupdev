<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(strtolower('tickets'), function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('company_id')->index();
            $table->unsignedInteger('client_id')->index()->nullable();


            $table->unsignedInteger('ticketid')->index();

            $table->string('ticket_number');

            $table->integer('user_id')->unsigned()->nullable()->index('user_id');
            $table->integer('dept_id')->unsigned()->nullable()->index('dept_id');
            $table->integer('team_id')->unsigned()->nullable()->index('team_id');
            $table->integer('priority_id')->unsigned()->nullable()->index('priority_id');
            $table->integer('sla')->unsigned()->nullable()->index('sla');
            $table->integer('help_topic_id')->unsigned()->nullable()->index('help_topic_id');
            $table->integer('status')->unsigned()->nullable()->index('status');
            $table->boolean('rating');
            $table->boolean('ratingreply');
            $table->integer('flags');
            $table->integer('ip_address');
            $table->integer('assigned_to')->unsigned()->nullable()->index('assigned_to');
            $table->integer('lock_by');
            $table->dateTime('lock_at')->nullable();
            $table->integer('source')->unsigned()->nullable()->index('source');
            $table->boolean('isoverdue');
            $table->boolean('isreopened');
            $table->boolean('isanswered');
            $table->boolean('ishtml');
            $table->boolean('isclosed');
            $table->boolean('istransferred');
            $table->dateTime('transferred_at');
            $table->dateTime('reopened_at')->nullable();
            $table->dateTime('duedate')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->dateTime('last_message_at')->nullable();
            $table->dateTime('last_response_at')->nullable();
            $table->integer('approval');
            $table->integer('follow_up');




            $table->softDeletes();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();


            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');


            $table->unique( ['company_id', 'ticketid'] );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(strtolower('tickets'));
    }
}
