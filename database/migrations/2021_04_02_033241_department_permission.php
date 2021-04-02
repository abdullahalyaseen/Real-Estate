<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DepartmentPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_permission', function (Blueprint $table) {
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('permission_id');

            $table->unique(['department_id', 'permission_id']);
//            $table->foreign('department_id')->references('id')->on('department');
//            $table->foreign('permission_id')->references('id')->on('permission');
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
