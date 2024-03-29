<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugtoStoriesAndGrammarItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stories', function(Blueprint $table){
            $table->string('slug');
        });

        Schema::table('grammar_learning_path', function(Blueprint $table){
            $table->string('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stories', function(Blueprint $table){
            $table->dropColumn('slug');
        });

        Schema::table('grammar_learning_path', function(Blueprint $table){
            $table->dropColumn('slug');
        });
    }
}
