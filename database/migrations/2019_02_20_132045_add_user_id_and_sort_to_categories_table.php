<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdAndSortToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->index()->comment('所属用户');
            $table->unsignedInteger('sort')->default(1)->comment('排序');
            $table->unique(['user_id','id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('sort');
            $table->dropUnique(['user_id','id']);
        });
    }
}
