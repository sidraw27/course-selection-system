<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->mediumIncrements('id')->unsigned();
            $table->string('name', 20);
            // 可用於前台顯示用，也可以 uuid 替代
            $table->char('short_id', 6)->unique();
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
        Schema::dropIfExists('students');
    }
}
