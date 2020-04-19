<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectricityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('electricities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('region_id');
            $table->decimal('hours', 10, 2);
            $table->date('date');
            $table->text('comment');
            $table->string('day_period');
            $table->string('night_period');
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
        Schema::dropIfExists('electricities');
    }
}
