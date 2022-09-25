<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_books', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('f_name');
            $table->string('l_name');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->refernces('id')->on('users')->onDelete('cascade');
            $table->bigInteger('phone_no');
            $table->string('city');
            $table->string('state');
            $table->string('address_1');
            $table->string('address_2')->nullable();
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
        Schema::dropIfExists('address_books');
    }
}
