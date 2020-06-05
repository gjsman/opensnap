<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Channel;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('architecture');
            $table->string('name');
            $table->string('released-at');
            $table->string('risk');
            $table->integer('revision');
            $table->integer('snap_id');
            $table->string('track');
            $table->string('type');
            $table->string('version');
            $table->timestamps();
        });

        $channel = new Channel;
        $channel["architecture"] = "amd64";
        $channel["name"] = "stable";
        $channel["released-at"] = "2019-04-11T15:27:09.763080+00:00";
        $channel["risk"] = "stable";
        $channel["revision"] = 93;
        $channel["snap_id"] = 1;
        $channel["track"] = "latest";
        $channel["type"] = "app";
        $channel["version"] = "1.33.0-1554390824";
        $channel->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
