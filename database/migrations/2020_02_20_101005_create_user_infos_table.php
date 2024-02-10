<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('emp_id',50);
            $table->integer('user_id')->comment = 'Foreign Key: users.id';
            $table->string('surname',50)->nullable();
            $table->integer('designation')->nullable()->comment = 'Foreign Key: user_designation.id';
            $table->text('about')->nullable();
            $table->text('address')->nullable();
            $table->string('mobile',33)->nullable();
            $table->string('office_phone',33)->nullable();
            $table->string('fax',33)->nullable();
            $table->string('age',33)->nullable();
            $table->tinyInteger('gender')->nullable()->comment = '1=Male, 2=Female';
            $table->string('image',50)->nullable();
            $table->integer('report_to')->nullable()->comment = 'Foreign Key: users.id';
            $table->integer('created_by')->nullable();
            $table->tinyInteger('created_by_type')->nullable()->comment = '1=Admin, 2=User';
            $table->integer('updated_by')->nullable();
            $table->tinyInteger('updated_by_type')->nullable()->comment = '1=Admin, 2=User';
            $table->integer('deleted_by')->nullable();
            $table->tinyInteger('deleted_by_type')->nullable()->comment = '1=Admin, 2=User';
            $table->integer('project_id')->nullable();
            
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
        Schema::dropIfExists('user_infos');
    }
}
