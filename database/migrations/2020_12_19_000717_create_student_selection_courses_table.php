<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentSelectionCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_selection_courses', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('student_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            // MySQL InnoDB foreign key 不需要另外建立 index
            $table->foreign('student_id')
                ->on('students')
                ->references('id')
                ->cascadeOnDelete();
            $table->foreign('course_id')
                ->on('courses')
                ->references('id')
                ->cascadeOnDelete();
            $table->unique(['student_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_selection_courses');
    }
}
