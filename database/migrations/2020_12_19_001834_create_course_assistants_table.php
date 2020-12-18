<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseAssistantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_assistants', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->unsigned();
            $table->mediumInteger('student_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            // MySQL InnoDB foreign key 不需要另外建立 index
            $table->foreign('course_id')
                ->on('courses')
                ->references('id')
                ->cascadeOnDelete();
            $table->foreign('student_id')
                ->on('students')
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
        Schema::dropIfExists('course_assistants');
    }
}
