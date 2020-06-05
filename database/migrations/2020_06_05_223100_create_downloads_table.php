<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Download;

class CreateDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('sha3-384');
            $table->integer('size');
            $table->string('url');
            $table->integer('channel_id');
            $table->string('deltas')->nullable();
            $table->timestamps();
        });

        $download = new Download;
        $download["sha3-384"] = "f8e1d4aec1daac3e63c1f4f6535a325945ff5e51287d4471fe2ea60e9d4288b12c64eaae98f6e1ae00e9c00f335271aa";
        $download["size"] = 130367488;
        $download["channel_id"] = 1;
        $download["url"] = "https://api.snapcraft.io/api/v1/snaps/download/XPQdduIwHiDCZvPHRrmsqV7Nr6nQRuqM_93.snap";
        $download->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('downloads');
    }
}
