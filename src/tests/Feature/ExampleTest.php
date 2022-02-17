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
                'title' => 'テスト用',
                'category' => '文芸',
                'read_flg' => 0
            ];
        
        $this->post('api/books',$data)
        ->assertOk();
    }
}
