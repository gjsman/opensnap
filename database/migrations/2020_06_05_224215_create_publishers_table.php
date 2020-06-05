<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Publisher;

class CreatePublishersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string("display-name");
            // TODO: EXPECTS id, BUT CANNOT USE REAL `id`
            $table->string("publisher-id");
            $table->string("username");
            $table->string("validation");
            $table->timestamps();
        });

        $publisher = new Publisher;
        $publisher["display-name"] = "Snapcrafters";
        $publisher["username"] = "snapcrafters";
        $publisher["validation"] = "unproven"; 
        $publisher["publisher-id"] = "eEoV9TnaNkCzfJBu9SRhr2678vzyYV43";
        $publisher->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publishers');
    }
}
