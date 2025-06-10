<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sets', function (Blueprint $table) {
            $table->foreignId('category_id')->after('user_id')->constrained()->onDelete('cascade');
        });
        Schema::table('tracks', function (Blueprint $table) {
            $table->foreignId('category_id')->after('user_id')->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('sets', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
