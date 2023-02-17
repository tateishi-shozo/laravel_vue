<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\BookPost;


class BookPostRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * カスタムリクエストのバリデーションテスト
     *
     * @param array 項目名の配列
     * @param array 値の配列
     * @param boolean 期待値(true:バリデーションOK、false:バリデーションNG)
     * @dataProvider dataBookRegistration
     */

    public function testBookRegistration(array $keys, array $values, bool $expect)
    {
        $dataList = array_combine($keys, $values);

        $request = new BookPost();
        $rules = $request->rules();
        $validator = Validator::make($dataList, $rules);
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    public function dataBookRegistration()
    {
        return [
            'OK' => [
                ['title', 'category'],
                ['タイトル', '文芸'],
                true
            ],
            'タイトルNG' => [
                ['title', 'category'],
                ['', '文芸'],
                false
            ],
            'カテゴリーNG' => [
                ['title', 'category'],
                ['タイトル', ''],
                false
            ]
        ];
    }
}
