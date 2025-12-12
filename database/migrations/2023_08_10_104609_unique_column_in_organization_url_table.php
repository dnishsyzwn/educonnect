<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UniqueColumnInOrganizationUrlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, check if column exists and its current state
        $result = DB::select("SHOW COLUMNS FROM organization_url WHERE Field = 'url_name'");

        if (empty($result)) {
            throw new RuntimeException("Column 'url_name' not found in organization_url table");
        }

        $column = $result[0];
        $columnType = $column->Type;
        $isNullable = $column->Null === 'YES' ? 'NULL' : 'NOT NULL';

        // Check for duplicates before adding unique constraint
        $duplicates = DB::select("
            SELECT url_name, COUNT(*) as count
            FROM organization_url
            WHERE url_name IS NOT NULL
            GROUP BY url_name
            HAVING COUNT(*) > 1
        ");

        if (!empty($duplicates)) {
            // Handle duplicates - you might want to make them unique
            foreach ($duplicates as $duplicate) {
                $counter = 1;
                $duplicateRows = DB::table('organization_url')
                    ->where('url_name', $duplicate->url_name)
                    ->orderBy('id')
                    ->get();

                // Keep first one as is, modify others
                foreach ($duplicateRows as $index => $row) {
                    if ($index > 0) { // Skip first row
                        $newName = $duplicate->url_name . '_' . $counter;
                        while (DB::table('organization_url')->where('url_name', $newName)->exists()) {
                            $counter++;
                            $newName = $duplicate->url_name . '_' . $counter;
                        }

                        DB::table('organization_url')
                            ->where('id', $row->id)
                            ->update(['url_name' => $newName]);

                        $counter++;
                    }
                }
            }
        }

        // Now add unique constraint
        DB::statement("ALTER TABLE organization_url ADD CONSTRAINT organization_url_url_name_unique UNIQUE (url_name)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove the unique constraint
        DB::statement('ALTER TABLE organization_url DROP INDEX organization_url_url_name_unique');
    }
}
