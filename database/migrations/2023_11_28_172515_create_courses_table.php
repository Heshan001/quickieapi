<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('courseName',255);
            $table->string('courseOverview',255);
            $table->string('courseContent',255);
            $table->string('minimumResult',255);
            $table->string('subjectStream',255);
            $table->decimal('zCore',10,4);
            $table->text('image')->default("abcd.jpg");
            $table->unsignedBigInteger('institute_id');
            $table->foreign('institute_id')->references('id')->on('institutes')->onDelete('cascade');
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
        Schema::dropIfExists('courses');
    }
}
