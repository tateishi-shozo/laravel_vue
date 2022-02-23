<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    //viewのテスト
    public function testBasicTest()
    {
        // $a = factory(\App\Book::class)->create();
        // Log::debug($a);

        // $data = [
        //     'id' => 1,
        //     'title' => 'テスト用'
        // ];
        
        // $this->assertDatabaseHas('books',$data);
        
        //dd(env('APP_ENV'));
        //dd(env('DB_CONNECTION'));
        //dd(env('DB_DATABASE'));
        
        $response = $this->get('/index');
        $response->assertStatus(200);
    }

    //index()テスト
    public function testApiIndex(){
        $this->get('api/books')
        ->assertOk()
        ->assertJsonStructure([
            "*" =>[
                'id',
                'title',
                'category',
                'read_flg',
            ]
        ]);
    }

    //store()テスト
    public function testApiStore(){
        $data = [
                'title' => 'store()テスト',
                'category' => '文芸',
                'read_flg' => 0
            ];
        
        $this->post('api/books',$data)
        ->assertOk();
    }
}
