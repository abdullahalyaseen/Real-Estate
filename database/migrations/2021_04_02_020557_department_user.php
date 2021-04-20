<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DepartmentUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_user',function (Blueprint $table){
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('user_id');

            $table->unique(['department_id','user_id']);
//            $table->foreign('user_id')->references('id')->on('users');
//            $table->foreign('department_id')->references('id')->on('department');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
