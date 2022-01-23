<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookTableSeeder extends Seeder
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
            'read_flg' => '0',
            'title' => '少年ジャンプ',
        ];
        DB::table('books')->insert($param);

        $param = [
            'category' => '専門書',
            'read_flg' => '1',
            'title' => '少年マガジン',
        ];
        DB::table('books')->insert($param);

    }
}
