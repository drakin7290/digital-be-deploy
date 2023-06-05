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
        Schema::create('medals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->longText('icon');
            $table->longText('description')->nullable();
            $table->string('status', 60)->default('published');
            $table->integer('numbers_owners')->default(0);
            $table->timestamps();
        });

        Schema::create('medals_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('medals_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'medals_id'], 'medals_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medals');
        Schema::dropIfExists('medals_translations');
    }
};
