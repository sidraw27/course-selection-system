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
            $table->integerIncrements('id')->unsigned();
            $table->char('short_id', 6)->unique();
            // course 的 teacher_id 不設 foreign key 考量為若是需要記錄課程的授課教師時，
            // 但該老師已離職等狀況，但課程處於 pending，等待更換其他師資等情況，
            // 可能的情境較多，在 spec 沒有清楚定義的狀況下，先以彈性並可讓 application 控制為主
            $table->smallInteger('teacher_id')->unsigned()->nullable();
            $table->string('name', 30);
            $table->timestamps();
            $table->softDeletes();

            $table->index('short_id');
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
