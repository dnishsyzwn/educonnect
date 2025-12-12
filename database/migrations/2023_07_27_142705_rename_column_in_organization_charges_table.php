<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RenameColumnInOrganizationChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Rename column using raw SQL
        DB::statement('ALTER TABLE organization_charges CHANGE minimun_amount minimum_amount DECIMAL(10,2)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rename back
        DB::statement('ALTER TABLE organization_charges CHANGE minimum_amount minimun_amount DECIMAL(10,2)');
    }
}
