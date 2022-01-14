<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsClaimToPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('points', function (Blueprint $table) {
            $table->boolean('showclaim')->nullable()->default(0);
            $table->string('code')->nullable()->default('');
            $table->string('next_point')->nullable();
            $table->boolean('showrequest')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('points', function (Blueprint $table) {
            $table->dropColumn('showclaim');
            $table->dropColumn('code');
            $table->dropColumn('next_point');
            $table->dropColumn('showrequest');
        });
    }
}
