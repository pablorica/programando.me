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
        Schema::table('posts', function (Blueprint $table) {
            $table->longText('post_content')->nullable()->change();
            $table->text('post_excerpt')->nullable()->change();
            $table->foreignId('post_author')->nullable()->change();
            $table->string('post_image')->nullable()->change();
            $table->string('post_category')->nullable()->change();
            $table->string('post_slug')->nullable()->change();
        });
    }
};
