<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id');
            $table->string('company_name',100);
            $table->string('logo',50)->nullable();
            $table->text('address')->nullable();
            $table->string('street',100)->nullable();
            $table->string('city',100)->nullable();
            $table->string('state',100)->nullable();
            $table->string('post_code',20)->nullable();
            $table->integer('country')->comment = 'Foreign Key: en_country.id';
            $table->string('mobile',50);
            $table->string('office_phone',50)->nullable();
            $table->string('fax',100)->nullable();
            $table->string('email',100);
            $table->string('website',100)->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('deleted_by')->nullable();
            $table->tinyInteger('valid')->default(1);
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
        Schema::dropIfExists('project_infos');
    }
}
