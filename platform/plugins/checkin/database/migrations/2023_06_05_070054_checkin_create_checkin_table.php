<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->date("date");
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('checkins_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('checkins_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'checkins_id'], 'checkins_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkins');
        Schema::dropIfExists('checkins_translations');
    }
};