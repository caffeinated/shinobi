<?php

use Illuminate\Database\Migrations\Migration;

class AddSpecialRoleColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $name = config('shinobi.tables.roles');

        Schema::table($name, function ($table) {
            $table->enum('special', ['all-access', 'no-access'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $name = config('shinobi.tables.roles');

        Schema::table($name, function ($table) {
            $table->dropColumn('special');
        });
    }
}
