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
            $table->longText('post_content')->change();
            $table->foreignId('post_author')->change();
            $table->boolean('post_published')->default(false)->add();
            $table->string('post_image')->add();
            $table->string('post_category')->add();
            $table->dateTime('published_at')->nullable()->add();
        });

    }

};
