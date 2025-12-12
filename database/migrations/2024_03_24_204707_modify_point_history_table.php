<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyPointHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Make 'fromSubline' column nullable
        DB::statement('ALTER TABLE point_history MODIFY fromSubline TINYINT(1) NULL');

        // 2. Add new 'desc' column if it doesn't exist
        if (!Schema::hasColumn('point_history', 'desc')) {
            DB::statement('ALTER TABLE point_history ADD `desc` VARCHAR(255) NULL');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 1. Drop 'desc' column if it exists
        if (Schema::hasColumn('point_history', 'desc')) {
            DB::statement('ALTER TABLE point_history DROP COLUMN `desc`');
        }

        // 2. Change 'fromSubline' column back to non-nullable
        // First, update any NULL values to a default (e.g., 0)
        DB::table('point_history')
            ->whereNull('fromSubline')
            ->update(['fromSubline' => 0]);

        // Then make it NOT NULL
        DB::statement('ALTER TABLE point_history MODIFY fromSubline TINYINT(1) NOT NULL');
    }
}
