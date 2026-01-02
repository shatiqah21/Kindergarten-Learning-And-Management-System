<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 100);
        $table->string('slug', 100);
        $table->unsignedInteger('my_class_id');
        $table->unsignedInteger('teacher_id')->nullable(); // tambah foreign key
        $table->timestamps();

        $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
            });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjects');
    }
}
