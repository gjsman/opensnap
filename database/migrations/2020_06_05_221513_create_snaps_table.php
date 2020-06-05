<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Snap;

class CreateSnapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snaps', function (Blueprint $table) {
            $table->id();
            $table->string('license');
            $table->string('name');
            $table->integer('publisher_id');
            $table->string('snap-id');
            $table->string('store-url');
            $table->string('summary');
            $table->string('default-track')->nullable();
            $table->string('title');
            $table->timestamps();
        });

        $snap = new Snap;
        $snap["license"] = "Proprietary";
        $snap["name"] = "vscode";
        $snap["publisher_id"] = 1;
        $snap["snap-id"] = "XPQdduIwHiDCZvPHRrmsqV7Nr6nQRuqM";
        $snap["store-url"] = "https://snapcraft.io/vscode";
        $snap["summary"] = "Code editing. Redefined.";
        $snap["title"] = "Visual Studio Code";
        $snap->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('snaps');
    }
}
