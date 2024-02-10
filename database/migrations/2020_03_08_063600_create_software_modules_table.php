<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoftwareModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('software_modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('module_name', 50);
            $table->string('sort_name', 50)->nullable();
            $table->string('module_icon', 50)->nullable();
            $table->string('url_prefix', 50);
            $table->string('route_prefix', 50);
            $table->integer('folder_id')->comment = 'Foreign key: software_folders.id';
            $table->tinyInteger('sl_no')->nullable();
            $table->tinyInteger('status')->default(0)->comment = '1=Active, 0=Inactive';
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('software_modules');
    }
}
