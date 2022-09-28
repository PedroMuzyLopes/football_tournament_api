<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->timestamp("start_time");
            $table->timestamp("end_time");
            $table->foreignId('team1')
                ->constrained('teams')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('team1_score');
            $table->foreignId('team2')
                ->constrained('teams')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('team2_score');
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
        Schema::dropIfExists('games');
    }
};
