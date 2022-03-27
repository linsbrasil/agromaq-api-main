<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jacto_id');
            $table->boolean('status');
            $table->string('name');
            $table->string('description');
            $table->string('image');
            $table->string('header_image');
            $table->string('gallery');
            $table->string('videos');
            $table->string('features');
            $table->string('specifications');
            $table->string('slug');
            $table->jsonb('related_jactoparts_title');
            $table->jsonb('related_jactoparts_url');
            $table->boolean('consortium');
            $table->boolean('prelaunch');
            $table->boolean('launch');
            $table->unsignedBigInteger('prelaunch_market_ids');
            $table->string('launch_market_ids');
            $table->boolean('show_in_comparator');
            $table->boolean('preview');
            $table->string('preview_market_ids');
            $table->string('markets_ids');
            $table->string('categories_ids');
            $table->string('scopes_by_market_id');
            $table->jsonb('files');
            $table->boolean('sended');
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
        Schema::dropIfExists('products');
    }
};
