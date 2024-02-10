<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoftwareInternalLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('software_internal_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('link_name',50);
            $table->string('route',50)->nullable();
            $table->integer('menu_id')->comment = 'Foreign key: software_menus.id';
            $table->tinyInteger('resource')->comment = '1=Yes, 0=No';
            $table->tinyInteger('crm_auto_access')->nullable()->comment = '1=Yes, 0=No';
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
        Schema::dropIfExists('software_internal_links');
    }
}
