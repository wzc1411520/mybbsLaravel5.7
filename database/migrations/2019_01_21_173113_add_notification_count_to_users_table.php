<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationCountToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('notification_count')->unsigned()->default(0);
            $table->integer('reply_notification_count')->unsigned()->default(0);
            $table->integer('topic_notification_count')->unsigned()->default(0);
            $table->timestamp('last_actived_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_actived_at');
            $table->dropColumn('topic_notification_count');
            $table->dropColumn('reply_notification_count');
            $table->dropColumn('notification_count');
        });
    }
}
