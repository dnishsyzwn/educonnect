<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableRooms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Change details column from string to text and nullable
        DB::statement('ALTER TABLE rooms MODIFY details TEXT NULL');

        // 2. Change price column from decimal(5,2) to decimal(8,2)
        DB::statement('ALTER TABLE rooms MODIFY price DECIMAL(8,2)');

        // 3. Add address column if it doesn't exist
        if (!Schema::hasColumn('rooms', 'address')) {
            DB::statement('ALTER TABLE rooms ADD address VARCHAR(255)');
        }

        // 4. Add timestamps if they don't exist
        if (!Schema::hasColumn('rooms', 'created_at')) {
            DB::statement('ALTER TABLE rooms ADD created_at TIMESTAMP NULL DEFAULT NULL');
        }

        if (!Schema::hasColumn('rooms', 'updated_at')) {
            DB::statement('ALTER TABLE rooms ADD updated_at TIMESTAMP NULL DEFAULT NULL');
        }

        // 5. Add soft deletes column if it doesn't exist
        if (!Schema::hasColumn('rooms', 'deleted_at')) {
            DB::statement('ALTER TABLE rooms ADD deleted_at TIMESTAMP NULL DEFAULT NULL');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 1. Change details column back to string (assuming original was VARCHAR(255))
        DB::statement('ALTER TABLE rooms MODIFY details VARCHAR(255) NOT NULL');

        // 2. Change price column back to decimal(5,2)
        DB::statement('ALTER TABLE rooms MODIFY price DECIMAL(5,2)');

        // 3. Drop address column if it exists
        if (Schema::hasColumn('rooms', 'address')) {
            DB::statement('ALTER TABLE rooms DROP COLUMN address');
        }

        // 4. Drop timestamps if they exist
        if (Schema::hasColumn('rooms', 'created_at')) {
            DB::statement('ALTER TABLE rooms DROP COLUMN created_at');
        }

        if (Schema::hasColumn('rooms', 'updated_at')) {
            DB::statement('ALTER TABLE rooms DROP COLUMN updated_at');
        }

        // 5. Drop soft deletes column if it exists
        if (Schema::hasColumn('rooms', 'deleted_at')) {
            DB::statement('ALTER TABLE rooms DROP COLUMN deleted_at');
        }
    }
}
