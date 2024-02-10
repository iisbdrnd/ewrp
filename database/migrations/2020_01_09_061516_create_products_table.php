<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->float('amount', 10, 2);
            $table->timestamps();
            $table->tinyInteger('valid')->default(1);
        });

        DB::table('products')->insert(array(
            array('id' => 1,  'name' => 'Apple', 'amount' => '100', 'valid' => 1),
            array('id' => 2,  'name' => 'Mango', 'amount' => '250', 'valid' => 1),
            array('id' => 3,  'name' => 'Banana', 'amount'   => '500', 'valid' => 1),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
