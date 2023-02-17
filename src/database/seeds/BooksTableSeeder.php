<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'category' => 'コミック/雑誌',
            'title' => '少年ジャンプ',
        ];
        DB::table('books')->insert($param);

        $param = [
            'category' => '専門書',
            'title' => '少年マガジン',
        ];
        DB::table('books')->insert($param);
    }
}
