<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreign('account_id')->references('id')->on('accounts');
        });

        Schema::table('netblocks', function (Blueprint $table) {
            $table->foreign('contact_id')->references('id')->on('contacts');
        });

        Schema::table('domains', function (Blueprint $table) {
            $table->foreign('contact_id')->references('id')->on('contacts');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign('ip_contact_account_id')->references('id')->on('accounts');
            $table->foreign('domain_contact_account_id')->references('id')->on('accounts');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->foreign('evidence_id')->references('id')->on('evidences');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->foreign('ticket_id')->references('id')->on('tickets');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('account_id')->references('id')->on('accounts');
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brands');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('accounts');
        });
    }

    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropForeign('brands_creator_id_foreign');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign('accounts_brand_id_foreign');
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->dropForeign('permission_role_role_id_foreign');
            $table->dropForeign('permission_role_permission_id_foreign');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_account_id_foreign');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign('notes_ticket_id_foreign');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('events_evidence_id_foreign');
            $table->dropForeign('events_ticket_id_foreign');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign('tickets_domain_contact_account_id_foreign');
            $table->dropForeign('tickets_ip_contact_account_id_foreign');
        });

        Schema::table('domains', function (Blueprint $table) {
            $table->dropForeign('domains_contact_id_foreign');
        });

        Schema::table('netblocks', function (Blueprint $table) {
            $table->dropForeign('netblocks_contact_id_foreign');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign('contacts_account_id_foreign');
        });
    }
}
