<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create(config('bluestar.module.table_name'), function (Blueprint $table) {
            $table->increments('id');

            $table->string('title')->comment('Module Title');

            $table->string('name')->comment('Module Name');

            $table->string('path', 20)->comment('Module Directory');

            $table->string('description')->comment('Module Description');

            $table->string('keywords')->comment('Module Keywords');

            $table->string('version', 20)->comment('Module Version')->default('1.0.0');

            $table->boolean('status')->comment('Module Status')->default(1);

            $table->unsignedInteger('created_at')->comment('Created Time')->default(0);

            $table->unsignedInteger('updated_at')->comment('Updated Time')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists(config('bluestar.module.table_name'));
    }
};
