<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project_id');
            $table->string('name');
            $table->integer('default_currency')->comment = 'Foregin Key: crm_currency.id';
            $table->tinyInteger('compile_status')->comment = '1=Compiled, 0=Not Compiled';
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('deleted_by')->nullable();
            $table->tinyInteger('valid');
            $table->softDeletes();
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
        Schema::dropIfExists('projects');
    }
}
