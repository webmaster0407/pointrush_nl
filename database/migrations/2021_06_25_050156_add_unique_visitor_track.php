<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueVisitorTrack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('visitors', function ($table) {
            $table->id();
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->unsignedBigInteger('track_id');

            $table->timestamps();

            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('cascade');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->unsignedBigInteger('visitor')->default(0);
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
            $table->dropColumn('visitor');
        });

        Schema::dropIfExists('visitors');
    }
}
