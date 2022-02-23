<?php

use Illuminate\Database\Seeder;

class TestBooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('books')->insert([
            'title' => 'テスト用',
            'category' => '文芸',
            'read_flg' => 0 
        ]);
    }
}
