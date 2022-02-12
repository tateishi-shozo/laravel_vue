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

    //@indexテスト
    public function testApiIndex(){
        $this->get('api/books')
        ->assertStatus(200)
        ->assertJsonStructure([
            "*" =>[
                'id',
                'title',
                'category',
                'read_flg',
            ]

        ]);
    }
}
