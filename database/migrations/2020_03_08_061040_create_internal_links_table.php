<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('internal_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('link_name', 50)->nullable();
            $table->string('route', 50)->nullable();
            $table->integer('menu_id')->nullable()->comment = 'Foreign key: admin_menu.id';
            $table->tinyInteger('resource')->nullable()->comment = '1=Yes, 0=No';

            $table->tinyInteger('status')->nullable()->comment = '1=Active, 0=Inactive';
            
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
        Schema::dropIfExists('internal_links');
    }
}
