<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateEmailNullableUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove unique constraint first if it exists (check your database)
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NULL');

        // If email has a unique constraint, you might need to drop it first
        // DB::statement('ALTER TABLE users DROP INDEX users_email_unique');
        // DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // First make it NOT NULL
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL');

        // Then add unique constraint if it was removed
        // DB::statement('ALTER TABLE users ADD UNIQUE users_email_unique (email)');
    }
}
