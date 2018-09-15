<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 256);
            $table->enum('status', ['pending','accepted','rejected'])->default('pending');
            $table->integer('years_exp');
            $table->dateTime('date_applied');
            $table->boolean('reviewed')->default(false);
            $table->mediumText('description');
            $table->timestamps();

            $table->index('status');
            $table->index('reviewed');
            $table->index('date_applied');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidates');
    }
}
