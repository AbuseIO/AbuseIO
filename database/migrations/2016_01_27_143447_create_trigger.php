<?php

use Illuminate\Database\Migrations\Migration;

class CreateTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // systembrand triggers
        DB::unprepared(
            'CREATE TRIGGER systembrand_insert BEFORE INSERT ON brands '.
            '   FOR EACH ROW '.
            '   BEGIN '.
            '       IF NEW.`systembrand` = true AND EXISTS(select * from brands where systembrand = true) THEN '.
            "           signal sqlstate '45000' set message_text = 'There is already a systembrand defined'; ".
            '       END IF; '.
            'END;'
        );
        DB::unprepared(
            'CREATE TRIGGER systembrand_update BEFORE UPDATE ON brands '.
            '   FOR EACH ROW '.
            '   BEGIN '.
            '       IF NEW.`systembrand` = true AND NOT OLD.`systembrand` = true AND EXISTS(select * from brands where systembrand = true) THEN '.
            "           signal sqlstate '45000' set message_text = 'There is already a systembrand defined'; ".
            '       END IF; '.
            'END;'
        );

        //systemaccount triggers
        DB::unprepared(
            'CREATE TRIGGER systemaccount_insert BEFORE INSERT ON accounts '.
            '   FOR EACH ROW '.
            '   BEGIN '.
            '       IF NEW.`systemaccount` = true AND EXISTS(select * from accounts where systemaccount = true) THEN '.
            "           signal sqlstate '45000' set message_text = 'There is already a systemaccount defined'; ".
            '       END IF; '.
            'END;'
        );
        DB::unprepared(
            'CREATE TRIGGER systemaccount_update BEFORE UPDATE ON accounts '.
            '   FOR EACH ROW '.
            '   BEGIN '.
            '       IF NEW.`systemaccount` = true AND NOT OLD.`systemaccount` = true AND EXISTS(select * from accounts where systemaccount = true) THEN '.
            "           signal sqlstate '45000' set message_text = 'There is already a systemaccount defined'; ".
            '       END IF; '.
            'END;'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER systembrand_insert');
        DB::unprepared('DROP TRIGGER systembrand_update');
        DB::unprepared('DROP TRIGGER systemaccount_insert');
        DB::unprepared('DROP TRIGGER systemaccount_update');
    }
}
