<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyDescColumnInProductItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Change desc column to VARCHAR(2000) and make it nullable
        DB::statement('ALTER TABLE product_item MODIFY `desc` VARCHAR(2000) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverse: make desc NOT NULL and original length (assuming original was VARCHAR(255))
        DB::statement('ALTER TABLE product_item MODIFY `desc` VARCHAR(255) NOT NULL');
    }
}
