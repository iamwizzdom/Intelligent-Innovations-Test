<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalkAttendeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talk_attendees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('talk_id');
            $table->foreign('talk_id')->references('id')
                ->on('talks')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('attendee_id');
            $table->foreign('attendee_id')->references('id')
                ->on('attendees')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['talk_id', 'attendee_id'], 'talk_attendee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('talk_attendees');
    }
}
