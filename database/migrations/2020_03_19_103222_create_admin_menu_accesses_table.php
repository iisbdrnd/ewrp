<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminMenuAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menu_accesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('menu_id')->nullable()->comment = 'Foreign key: admin_menu.id';
            $table->integer('admin_id')->nullable()->comment = 'Foreign key: admin.id';
            
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
        Schema::dropIfExists('admin_menu_accesses');
    }
}
