<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHobbiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_hobbies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id")->unsigned()->nullable()->index();
            $table->bigInteger("hobbie_id")->unsigned()->nullable()->index();
            $table->timestamps();

            $table->foreign("user_id")->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign("hobbie_id")->references('id')->on('hobbies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_hobbies');
    }
}
