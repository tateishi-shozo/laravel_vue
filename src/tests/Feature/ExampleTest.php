<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Book;
use App\User;
use App\BookUser;
use Exception;
use Illuminate\Support\Facades\DB;
use \Mockery\Adapter\Phpunit\MockeryTestCase;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    use RefreshDatabase;
    
    private $accessToken = null;

    //テストの事前準備　認証用のユーザー生成
    public function setUp():void
    {
        parent::setUp();

        $test_user = [
            'name' => 'sample user',
            'email' => 'sample@sankosc.co.jp',
            'password' => 'sample123'
        ];

        $this->post('/api/register', $test_user);

        $response = $this->post('/api/login', $test_user);

        $response->assertOk();
        
        $this->accessToken = $response->decodeResponseJson('access_token');
        $this->user_id = $response->decodeResponseJson('user_id');
    }

    //viewのテスト　一覧ページ
    public function testViewIndex()
    {   
        $response = $this->get('/index');
        $response->assertStatus(200);
    }

    //viewのテスト　コメントページ
    public function testViewComment()
    {   
        $response = $this->get('/comment');
        $response->assertStatus(200);
    }

    //viewのテスト　ログインページ
    public function testViewLogin()
    {   
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    //viewのテスト　ユーザー登録ページ
    public function testViewRegister()
    {   
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    //ログインテスト
    public function testLogin(){
        $response = $this->post('/api/login', [
            'name' => 'sample user',
            'email' => 'sample@sankosc.co.jp',
            'password' => 'sample123'
        ]);

        $response
        ->assertOk()
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'user_id',
            'user_name'
        ]);
    }

    //登録していないユーザーがログインテスト
    public function testNotLogin(){
        $response = $this->post('/api/login', [
            'name' => 'noregister',
            'email' => 'noregister@sankosc.co.jp',
            'password' => 'noregister'
        ]);

        $response
        ->assertStatus(400)
        ->assertJsonStructure([
            'email'
        ]);
    }

    //登録テスト
    public function testRegister(){
        $register_user = [
            'name' => 'test_register',
            'email' => 'test_register@sankosc.co.jp',
            'password' => 'test_register'
        ];
        $this->post('/api/register', $register_user);

        $register_user = [
            'name' => 'test_register',
            'email' => 'test_register@sankosc.co.jp',
        ];

        $this->assertDatabaseHas('users',$register_user);
    }

    //すでに登録済ユーザーテスト
    public function testNotRegister(){

        $duplicate_user = [
            'name' => 'sample user',
            'email' => 'sample@sankosc.co.jp',
            'password' => 'sample123'
        ];
        
        $response = $this->post('/api/register', $duplicate_user);

        $response
        ->assertStatus(400)
        ->assertJsonStructure([
            'email'
        ]);
    }

    //ログアウト
    public function testLogout(){

        $response = $this -> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ]) -> get('api/logout');
        
        $response->assertOk();
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
                    'user_id'
                ]
            ]
        ]);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    //index()レコード見つからないテスト
    public function testExceptionIndex()
    {
        $books = \Mockery::mock('alias:'.\App\Book::class);
        $books->shouldReceive('paginate')->andReturn(false);
        
        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);
        $response = $this->get('api/books?page=1');

    }

    //新規投稿テストstore()テスト
    public function testApiStore()
    {
        $data = [
                'title' => 'store()テスト',
                'category' => '文芸',
                'user_id' => $this->user_id
            ];
        
        $response = $this-> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])
        ->post('api/books',$data);

        $this->assertDatabaseHas('books',$data);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    //store()DB保存できないテスト
    public function testSaveFailStore()
    {
        DB::shouldReceive('saveOrFail')->andReturn(false);

        $data = [
            'title' => 'store例外()テスト',
            'category' => '文芸',
            'user_id' => $this->user_id
        ];

        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);
        $this-> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])->post('api/books',$data);

        $this->assertDatabaseMissing('books',$data);
    }


    //編集（update()）テスト
    public function testApiUpdate()
    {
        $book = factory(\App\Book::class)->create();

        $data = $book -> toArray();
        $id = $book -> id;

        $this->assertDatabaseHas('books',$data);

        $update = [
            'title' => 'update',
            'category' => '実用書'
        ];

        $this -> withHeaders([
                    'Authorization' => 'Bearer '.$this->accessToken
                ])
                ->put('api/books/'.$id , $update);
        $this->assertDatabaseHas('books',$update);
    }

    //update()該当idなしテスト
    public function testNodataUpdate()
    {
        $id = 0;
        $update = [
            'title' => 'update',
            'category' => '実用書',
        ];

        $this->withoutExceptionHandling();
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this -> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])
        ->put('api/books/'.$id , $update);
    }
        
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    //update()保存失敗テスト
    public function testSaveFailUpdate()
    {
        DB::shouldReceive('saveOrFail')->andReturn(false);

        $id = 1;
        $update = [
            'title' => 'update失敗',
            'category' => '実用書',
        ];

        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);
        $this -> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])
        ->put('api/books/'.$id , $update);
        $this->assertDatabaseMissing('books',$update);
    }

    //destroy()テスト
    public function testApiDestroy()
    {
        $book = factory(\App\Book::class)->create();

        $data = $book -> toArray();
        $id = $book->id;

        $this->assertDatabaseHas('books',$data);

        $this -> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])
        ->delete('api/books/'.$id , $data);

        $this->assertDatabaseMissing('books',$data);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    //destroy()削除失敗テスト
    public function testFailDestroy()
    {
        $book = factory(\App\Book::class)->create();

        $data = $book -> toArray();
        $id = $book->id;

        DB::shouldReceive('destroy')->andReturn(false);

        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);
        $this -> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])
        ->delete('api/books/'.$id , $data);

        $this->assertDatabaseMissing('books',$data);
    }

    //１冊の本の情報を取得するshow()テスト
    public function testShow(){
        $book = factory(\App\Book::class)->create();

        $data = $book -> toArray();
        $id = $book -> id;

        $this -> assertDatabaseHas('books',$data);

        $response = $this -> get('api/book/'.$id);
        $response -> assertJson([
            'id' => $book ->id,
            'title' => $book -> title,
            'category' => $book -> category,
        ]);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    //１冊の本の情報を取得失敗show()テスト
    public function testNotShow(){
        $id = 5;

        $books = \Mockery::mock('alias:'.\App\Book::class);
        $books->shouldReceive('find')->andReturn(false);

        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);

        $this -> get('api/book/'.$id);
    }

    //本の検索result()テスト
    public function testResult(){
        $book = factory(\App\Book::class)->create();

        $data = $book -> toArray();
        $id = $book -> id;

        $this -> assertDatabaseHas('books',$data);

        $response = $this -> post('api/books/search',[
            'search' => $book -> title
        ]);
        $response -> assertJson([
            "data" =>[
                [
                    'id' => $book ->id,
                    'title' => $book -> title,
                    'category' => $book -> category,
                ]
            ]
        ]);
    }
    
    //コメントの登録create()テスト
    public function testCommentCreate(){
        $book = factory(\App\Book::class)->create();

        $comment = [
            'book_id' => $book -> id,
            'user_id' => $this -> user_id,
            'comment' => 'テスト'
        ];
        
        $this -> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])
        -> post('api/book/comment/',$comment);

        $this->assertDatabaseHas('book_user',$comment);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    //コメントの登録失敗create()テスト
    public function testCommentNotCreate(){
        $id = 1;

        $books = \Mockery::mock('alias:'.\App\Book::class);
        $books->shouldReceive('find')->andReturn(false);

        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);

        $this -> get('api/book/'.$id);
    }

    //コメントの取得show()テスト
    public function testCommentShow(){
        $book = factory(\App\Book::class)->create();

        $comment = [
            'book_id' => $book -> id,
            'user_id' => $this -> user_id,
            'comment' => 'テスト'
        ];
        
        $this -> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])
        -> post('api/book/comment/',$comment);

        $response = $this -> get('api/book/comment/'.$book -> id);

        $response -> assertJson([[
            "pivot" => $comment
        ]]);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    //コメントの取得失敗show()テスト
    public function testCommentNotShow(){
        $id = 5;

        $books = \Mockery::mock('alias:'.\App\Book::class);
        $books->shouldReceive('find')->andReturn(false);

        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);

        $this -> get('api/book/comment/'.$id);
    }
    
    //コメントの削除destroy()テスト
    public function testCommentDestroy(){

        $book = factory(\App\Book::class)->create();

        $comment = [
            'book_id' => $book -> id,
            'user_id' => $this -> user_id,
            'comment' => 'テスト'
        ];
        
        $this -> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])
        -> post('api/book/comment/',$comment);

        $this->assertDatabaseHas('book_user',$comment);

        $response = $this -> get('api/book/comment/'.$book -> id);
        $comment_id = $response[0]["pivot"]["id"];

        $this -> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])
        ->delete('api/book/comment/'.$comment_id);

        $this->assertDatabaseMissing('book_user',$comment);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    //コメントの削除失敗destroy()テスト
    public function testCommenNottDestroy(): void
    {

        $data = [
            'title' => 'testCommenNottDestroy()テスト',
            'category' => '文芸',
            'user_id' => $this->user_id
        ];
    
        $response = $this-> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])
        -> post('api/books',$data);

        $comment = [
            'book_id' => 1,
            'user_id' => $this -> user_id,
            'comment' => 'テスト'
        ];
        
        $this -> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])
        -> post('api/book/comment/',$comment);

        $this->assertDatabaseHas('book_user',$comment);

        $response = $this -> get('api/book/comment/1');
        $comment_id = $response[0]["pivot"]["id"];

        $books = \Mockery::mock('alias:'.\App\BookUser::class);
        $books -> shouldReceive('destroy')->andReturn(false);

        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);

        $this -> withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken
        ])
        ->delete('api/book/comment/'.$comment_id);
    }
}
