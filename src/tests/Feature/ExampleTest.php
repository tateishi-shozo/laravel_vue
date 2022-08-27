<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Book;
use Exception;
use Illuminate\Support\Facades\DB;

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

    //index()レコード見つからないテスト
    public function testExceptionIndex()
    {
        DB::shouldReceive('paginate')->andReturn(false);

        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);
        $this->get('api/books?page=1');
    }

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

    //store()バリデーションテスト
    // public function testExceptionStore()
    // {
    //     $data = [
    //         'title' => 'store()テスト',
    //         'category' => '',
    //         'read_flg' => 0
    //     ];
        
    //     $this->withoutExceptionHandling();
    //     $this->expectException(\Throwable::class);
    //     $this->post('api/books',$data);
    // }

    //store()DB保存できないテスト
    public function testSaveFailStore()
    {
        DB::shouldReceive('saveOrFail')->andReturn(false);

        $data = [
            'title' => 'store例外()テスト',
            'category' => '文芸',
            'read_flg' => 0
        ];

        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);
        $this->post('api/books',$data);
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

    //update()該当idなしテスト
    public function testNodataUpdate()
    {
        $id = 0;
        $update = [
            'title' => 'update',
            'category' => '実用書',
            'read_flg' => 0
        ];

        $this->withoutExceptionHandling();
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->put(action('BookController@update',$id),$update);
    }

    //update()保存失敗テスト
    public function testSaveFailUpdate()
    {
        DB::shouldReceive('saveOrFail')->andReturn(false);

        $id = 1;
        $update = [
            'title' => 'update失敗',
            'category' => '実用書',
            'read_flg' => 0
        ];

        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);
        $this->put(action('BookController@update',$id),$update);
        $this->assertDatabaseMissing('books',$data);
    }

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

    //destroy()削除失敗テスト
    public function testFailDestroy()
    {
        $book = factory(\App\Book::class)->create();

        $data = $book -> toArray();
        $id = $book->id;

        DB::shouldReceive('destroy')->andReturn(false);

        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);
        $this->delete(action('BookController@destroy',$id));
        $this->assertDatabaseMissing('books',$data);
    }
}
