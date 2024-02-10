<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_designations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name',50);
            $table->string('grade',50)->nullable();
            
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
        Schema::dropIfExists('user_designations');
    }
}
