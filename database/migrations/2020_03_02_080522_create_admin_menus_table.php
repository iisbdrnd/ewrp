<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('menu_name',50)->nullable();
            $table->string('menu_icon',50)->nullable();
            $table->string('route',50)->nullable();
            $table->integer('parent_id')->nullable()->comment = 'Foreign key: self.id';
            $table->tinyInteger('resource')->nullable()->comment = '1=Yes, 0=No';
            $table->integer('sl_no')->default(0);
            $table->tinyInteger('status')->nullable();
            
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('admin_menus');
    }
}
