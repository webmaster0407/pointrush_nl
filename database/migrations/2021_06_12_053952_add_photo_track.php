<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotoTrack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('points', function (Blueprint $table) {
            $table->boolean('upload_photo')->default(1);
        });

        Schema::table('claims', function (Blueprint $table) {
            $table->string('photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('upload_photo');
        });
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
}
