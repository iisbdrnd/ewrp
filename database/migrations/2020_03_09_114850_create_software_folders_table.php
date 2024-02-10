<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoftwareFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('software_folders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('folder_name', 50);
        });

        DB::table('software_folders')->insert(array(
                array('id' => 1, 'folder_name' => 'General'),
                array('id' => 2, 'folder_name' => 'Admin')
            )
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('software_folders');
    }
}
