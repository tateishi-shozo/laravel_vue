<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Book;
use Exception;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    use RefreshDatabase;

    //viewのテスト
    public function testBasicTest()
    {   
        $book = factory(\App\Book::class)->create();

        $response = $this->get('/index');
        $response->assertStatus(200);
    }

    //index()テスト
    public function testApiIndex()
    {
        $book = factory(\App\Book::class)->create();
        
        $this->get('api/books?page=1')
        ->assertOk()
        ->assertJsonStructure([
            "data" =>[
                "*" =>[
                    'id',
                    'title',
                    'category',
                    'read_flg',
                ]
            ]
        ]);
    }

    //index()例外処理テスト
    // public function testExceptionIndex()
    // {
    //     $this->expectException(\Exception::class);
    //     $this->get('api/books?page=1');
    // }

    //store()テスト
    public function testApiStore()
    {
        $data = [
                'title' => 'store()テスト',
                'category' => '文芸',
                'read_flg' => 0
            ];
        
        $this->post('api/books',$data)
        ->assertOk();
    }

    //store()例外処理テスト
    // public function testExceptionStore()
    // {
    //     $data = [
    //             'title' => 'store()テスト',
    //             'category' => '文芸',
    //             'read_flg' => 0
    //         ];
        
    //     $this->expectException(\Exception::class);
    //     $this->post('api/books',$data)->assertStatus(500);
    // }

    //destroy()テスト
    public function testApiDestroy()
    {
        $book = factory(\App\Book::class)->create();

        $data = $book -> toArray();
        $id = $book->id;
        $this->assertDatabaseHas('books',$data);

        $this->delete(action('BookController@destroy',$id));

        $this->assertDatabaseMissing('books',$data);
    }

    //update()テスト
    public function testApiUpdate()
    {
        $book = factory(\App\Book::class)->create();

        $data = $book -> toArray();
        $id = $book -> id;

        $this->assertDatabaseHas('books',$data);

        $update = [
            'title' => 'update',
            'category' => '実用書',
            'read_flg' => 0
        ];

        $this->put(action('BookController@update',$id),$update);
        
        $this->assertDatabaseHas('books',$update);
    }

}
