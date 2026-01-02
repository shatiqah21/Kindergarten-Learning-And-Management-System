<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransitDaycareToStudentRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        {
            Schema::table('student_records', function (Blueprint $table) {
                $table->unsignedBigInteger('transit_id')->nullable()->after('section_id');
                $table->unsignedBigInteger('daycare_id')->nullable()->after('transit_id');

                $table->foreign('transit_id')->references('id')->on('transits')->onDelete('set null');
                $table->foreign('daycare_id')->references('id')->on('daycares')->onDelete('set null');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('student_records', function (Blueprint $table) {
            // buang foreign key dulu
            $table->dropForeign(['transit_id']);
            $table->dropForeign(['daycare_id']);

            // buang column
            $table->dropColumn(['transit_id', 'daycare_id']);
        });
    }
}
