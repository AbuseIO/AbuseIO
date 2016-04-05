<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tickets',
            function (Blueprint $table) {
                // Columns
                $table->increments('id');
                $table->string('ip', 45);
                $table->string('domain');
                $table->string('class_id', 100);
                $table->enum('type_id', ['INFO', 'ABUSE', 'ESCALATION']);
                $table->enum('status_id', ['OPEN', 'CLOSED', 'ESCALATED', 'IGNORED', 'RESOLVED']);
                $table->enum('contact_status_id', ['OPEN', 'IGNORED', 'RESOLVED'])->default('OPEN');
                $table->integer('last_notify_count')->unsigned();
                $table->integer('last_notify_timestamp');
                $table->integer('ip_contact_account_id')->unsigned();
                $table->string('ip_contact_reference');
                $table->string('ip_contact_name');
                $table->string('ip_contact_email');
                $table->string('ip_contact_api_host');
                $table->integer('ip_contact_notified_count')->unsigned();
                $table->boolean('ip_contact_auto_notify')->unsigned();
                $table->integer('domain_contact_account_id')->unsigned();
                $table->string('domain_contact_reference');
                $table->string('domain_contact_name');
                $table->string('domain_contact_email');
                $table->string('domain_contact_api_host');
                $table->integer('domain_contact_notified_count')->unsigned();
                $table->boolean('domain_contact_auto_notify')->unsigned();
                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->index('ip');
                $table->index('domain');
                $table->index('class_id');
                $table->index('type_id');
                $table->index('ip_contact_reference');
                $table->index('domain_contact_reference');
                $table->index('status_id');
                $table->index('ip_contact_account_id');
                $table->index('domain_contact_account_id');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tickets');
    }
}
