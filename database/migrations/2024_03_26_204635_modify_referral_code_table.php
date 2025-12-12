<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyReferralCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Make 'transaction_id' column nullable
        DB::statement('ALTER TABLE point_history MODIFY transaction_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Make 'transaction_id' column NOT NULL again
        // First, handle any NULL values
        $nullCount = DB::table('point_history')->whereNull('transaction_id')->count();

        if ($nullCount > 0) {
            // You need to decide what to do with NULL values
            // Option 1: Set them to 0 (but 0 might not be a valid transaction ID)
            // Option 2: Set them to a default valid transaction ID
            // Option 3: Delete those records (dangerous!)

            // For safety, we'll throw an exception if there are NULLs
            throw new \Exception("Cannot reverse migration: Found $nullCount records with NULL transaction_id. Update these records first.");
        }

        DB::statement('ALTER TABLE point_history MODIFY transaction_id BIGINT UNSIGNED NOT NULL');
    }
}
