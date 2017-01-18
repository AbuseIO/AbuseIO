<?php

use AbuseIO\Models\Contact;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContactRemoveAutonotify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'contacts',
            function (Blueprint $table) {
                // Indexes
                $table->dropIndex(['auto_notify']);
                // Columns
                $table->dropColumn(['auto_notify']);
            }
        );
        foreach(Contact::where('auto_notify', 1) as $contact) {
            $contact->addNotificationMethod(['method'=> 'Mail']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'contacts',
            function (Blueprint $table) {
                // Columns
                $table->boolean('auto_notify')->unsigned();
                // Indexes
                $table->index('auto_notify');
            }
        ); 
    }
}
