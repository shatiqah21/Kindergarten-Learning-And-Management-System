<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddOnToStudentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_records', function (Blueprint $table) {
            // Elak duplicate column
            if (!Schema::hasColumn('student_records', 'add_on')) {
                $table->string('add_on', 100)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_records', function (Blueprint $table) {
            if (Schema::hasColumn('student_records', 'add_on')) {
                $table->dropColumn('add_on');
            }
        });
    }
}
