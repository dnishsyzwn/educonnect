<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTablePromotions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Change discount column
        DB::statement('ALTER TABLE promotions MODIFY discount DECIMAL(5,2) DEFAULT 0');

        // 2. Drop foreign key constraint if exists
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'promotions'
            AND COLUMN_NAME = 'homestayid'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (!empty($foreignKeys)) {
            foreach ($foreignKeys as $fk) {
                DB::statement("ALTER TABLE promotions DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
            }
        }

        // 3. Drop homestayid column if exists
        if (Schema::hasColumn('promotions', 'homestayid')) {
            DB::statement('ALTER TABLE promotions DROP COLUMN homestayid');
        }

        // 4. Add promotion_type column
        if (!Schema::hasColumn('promotions', 'promotion_type')) {
            DB::statement("ALTER TABLE promotions ADD promotion_type ENUM('discount','increase') NULL");
        }

        // 5. Add increase column
        if (!Schema::hasColumn('promotions', 'increase')) {
            DB::statement('ALTER TABLE promotions ADD increase DECIMAL(5,2) DEFAULT 0');
        }

        // 6. Add timestamps if they don't exist
        if (!Schema::hasColumn('promotions', 'created_at')) {
            DB::statement('ALTER TABLE promotions ADD created_at TIMESTAMP NULL DEFAULT NULL');
        }

        if (!Schema::hasColumn('promotions', 'updated_at')) {
            DB::statement('ALTER TABLE promotions ADD updated_at TIMESTAMP NULL DEFAULT NULL');
        }

        // 7. Add soft deletes column
        if (!Schema::hasColumn('promotions', 'deleted_at')) {
            DB::statement('ALTER TABLE promotions ADD deleted_at TIMESTAMP NULL DEFAULT NULL');
        }

        // 8. Add homestay_id column
        if (!Schema::hasColumn('promotions', 'homestay_id')) {
            DB::statement('ALTER TABLE promotions ADD homestay_id BIGINT UNSIGNED NULL');
        }

        // 9. Add foreign key constraint
        // First, check if rooms table has roomid column
        $roomsColumns = DB::select("SHOW COLUMNS FROM rooms LIKE 'roomid'");
        if (!empty($roomsColumns)) {
            DB::statement('ALTER TABLE promotions ADD CONSTRAINT promotions_homestay_id_foreign FOREIGN KEY (homestay_id) REFERENCES rooms(roomid)');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 1. Reverse discount column (assuming original was different)
        // DB::statement('ALTER TABLE promotions MODIFY discount FLOAT'); // Adjust based on original

        // 2. Drop foreign key on homestay_id
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'promotions'
            AND COLUMN_NAME = 'homestay_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (!empty($foreignKeys)) {
            foreach ($foreignKeys as $fk) {
                DB::statement("ALTER TABLE promotions DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
            }
        }

        // 3. Drop homestay_id column
        if (Schema::hasColumn('promotions', 'homestay_id')) {
            DB::statement('ALTER TABLE promotions DROP COLUMN homestay_id');
        }

        // 4. Drop promotion_type column
        if (Schema::hasColumn('promotions', 'promotion_type')) {
            DB::statement('ALTER TABLE promotions DROP COLUMN promotion_type');
        }

        // 5. Drop increase column
        if (Schema::hasColumn('promotions', 'increase')) {
            DB::statement('ALTER TABLE promotions DROP COLUMN increase');
        }

        // 6. Drop timestamps
        if (Schema::hasColumn('promotions', 'created_at')) {
            DB::statement('ALTER TABLE promotions DROP COLUMN created_at');
        }

        if (Schema::hasColumn('promotions', 'updated_at')) {
            DB::statement('ALTER TABLE promotions DROP COLUMN updated_at');
        }

        // 7. Drop soft deletes
        if (Schema::hasColumn('promotions', 'deleted_at')) {
            DB::statement('ALTER TABLE promotions DROP COLUMN deleted_at');
        }

        // 8. Add back homestayid column (assuming original was BIGINT UNSIGNED)
        if (!Schema::hasColumn('promotions', 'homestayid')) {
            DB::statement('ALTER TABLE promotions ADD homestayid BIGINT UNSIGNED NULL');
        }

        // 9. Add back foreign key for homestayid (adjust if needed)
        // DB::statement('ALTER TABLE promotions ADD FOREIGN KEY (homestayid) REFERENCES some_table(some_column)');
    }
}
