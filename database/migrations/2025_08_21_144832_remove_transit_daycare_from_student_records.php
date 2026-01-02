<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTransitDaycareFromStudentRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('student_records', function (Blueprint $table) {
            // drop foreign keys dulu
            $table->dropForeign(['transit_id']);
            $table->dropForeign(['daycare_id']);

            // baru drop columns
            $table->dropColumn(['transit_id', 'daycare_id']);
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
            $table->unsignedBigInteger('transit_id')->nullable();
            $table->unsignedBigInteger('daycare_id')->nullable();

            // recreate balik foreign key kalau nak rollback
            $table->foreign('transit_id')->references('id')->on('transits')->onDelete('set null');
            $table->foreign('daycare_id')->references('id')->on('daycares')->onDelete('set null');
        });
    }
}
