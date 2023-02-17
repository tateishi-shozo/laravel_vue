<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AuthPost;

class AuthPostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * カスタムリクエストのバリデーションテスト
     *
     * @param array 項目名の配列
     * @param array 値の配列
     * @param boolean 期待値(true:バリデーションOK、false:バリデーションNG)
     * @dataProvider dataAuthRegistration
     */

    public function testAuthRegistration(array $keys, array $values, bool $expect)
    {
        $dataList = array_combine($keys, $values);

        $request = new AuthPost();
        $rules = $request->rules();
        $validator = Validator::make($dataList, $rules);
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    public function dataAuthRegistration()
    {
        return [
            'OK' => [
                ['name', 'email', 'password'],
                ['名前入れた', 'メール入れた', 'パスワード入れた'],
                true
            ],
            '名前NG' => [
                ['name', 'email', 'password'],
                ['', 'メール入れた', 'パスワード入れた'],
                false
            ],
            'メールNG' => [
                ['name', 'email', 'password'],
                ['名前入れた', '', 'パスワード入れた'],
                false
            ],
            'パスワードNG' => [
                ['name', 'email', 'password'],
                ['名前入れた', 'メール入れた', ''],
                false
            ]
        ];
    }
}
