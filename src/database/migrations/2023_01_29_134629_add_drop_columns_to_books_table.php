<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDropColumnsToBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('read_flg');
            $table->dropColumn('evaluation');
            $table->dropColumn('conclude');
            $table->dropColumn('image');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('read_flg');
            $table->integer('evaluation')->nullable();
            $table->string('conclude')->nullable();
            $table->string('image')->nullable();
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign('books_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
}
