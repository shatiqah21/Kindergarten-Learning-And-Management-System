<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_timetables', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('teacher_id');   // refer ke users.id
            $table->unsignedInteger('my_class_id');
            $table->unsignedInteger('subject_id');
            $table->string('day', 20);  // Monday, Tuesday, etc.
            $table->time('time_start');
            $table->time('time_end');
            $table->timestamps();

            // Foreign keys
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_timetables');
    }
}
