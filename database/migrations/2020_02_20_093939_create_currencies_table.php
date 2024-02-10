<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('currency_name',50);
            $table->string('html_code',50);

            $table->timestamps();
        });

        DB::table('currencies')->insert(array(
            array('id' => 1,  'currency_name' => 'BDT', 'html_code' => '&#2547;'),
            array('id' => 2,  'currency_name' => 'Dollar', 'html_code' => '&#36;'),
            array('id' => 3,  'currency_name' => 'Pound', 'html_code' => '&#163;'),
            array('id' => 4,  'currency_name' => 'Euro', 'html_code' => '&#128;'),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
