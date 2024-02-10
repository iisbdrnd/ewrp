<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminInternalLinkAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_internal_link_accesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('link_id')->nullable()->comment = 'Foreign key: admin_internal_link.id';
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
        Schema::dropIfExists('admin_internal_link_accesses');
    }
}
