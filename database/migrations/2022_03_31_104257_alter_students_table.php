<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove unique constraint first if it exists
        $sql = "SELECT COUNT(*) as count
                FROM information_schema.table_constraints
                WHERE table_schema = DATABASE()
                AND table_name = 'students'
                AND constraint_name = 'students_icno_unique'
                AND constraint_type = 'UNIQUE'";

        $result = DB::select($sql);

        if ($result[0]->count > 0) {
            DB::statement('ALTER TABLE students DROP INDEX students_icno_unique');
        }

        // Make icno nullable
        DB::statement('ALTER TABLE students MODIFY icno VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // First check if there are any NULL icno values
        $nullIcno = DB::table('students')->whereNull('icno')->count();

        if ($nullIcno > 0) {
            throw new RuntimeException("Cannot reverse migration: Found $nullIcno students with NULL icno. Update these records first.");
        }

        // Make icno NOT NULL
        DB::statement('ALTER TABLE students MODIFY icno VARCHAR(255) NOT NULL');

        // Add unique constraint back
        DB::statement('ALTER TABLE students ADD CONSTRAINT students_icno_unique UNIQUE (icno)');
    }
}
