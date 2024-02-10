<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoftwareMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('software_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('menu_name',50);
            $table->string('menu_icon',50)->nullable();
            $table->string('route',50)->nullable();
            $table->integer('parent_id')->nullable()->comment = 'Foreign key: self.id';
            $table->integer('folder_id')->nullable()->comment = 'Foreign key: software_folders.id';
            $table->integer('module_id')->nullable()->comment = 'Foreign key: software_modules.id';
            $table->tinyInteger('resource')->comment = '1=Yes, 0=No';
            $table->tinyInteger('crm_auto_access')->nullable()->comment = '1=Yes, 0=No';
            $table->integer('sl_no')->nullable();
            $table->tinyInteger('status')->default(0)->comment = '1=Active, 0=Inactive';
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->tinyInteger('valid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('software_menus');
    }
}
