import CommentComponent from "../components/CommentComponent";

const { mount } = require("@vue/test-utils");

describe("CommentComponent", () => {
    
    const getBook = jest.spyOn(CommentComponent.methods, 'getBook');
    const getComments = jest.spyOn(CommentComponent.methods, 'getComments');
    const addComment = jest.spyOn(CommentComponent.methods, 'addComment');
    const deleteComment = jest.spyOn(CommentComponent.methods, 'deleteComment');
    const returnIndex = jest.spyOn(CommentComponent.methods, 'returnIndex');

  let component;

  beforeEach(() => {
    component = mount(CommentComponent);
    component.setData({
      book: 
        {
            id: 1,
            category: "コミック/雑誌",
            title: "テスト本",
            created_at: "2023-01-30 12:12:16",
            updated_at: "2023-01-30 12:12:16",
            user_id: 1,
            user_name: "立石"
        },
      token: "Bearer 19|glKmBgcgxUPvJPL7WPX0P7LmQzfyE8eDnEqZGNsa",
      user_id: 1,
      user_name: "立石",
      comments:[
        {
            "id": 2,
            "name": "テスト2",
            "email": "test2@test2",
            "email_verified_at": null,
            "created_at": "2023-01-30 03:10:37",
            "updated_at": "2023-01-30 03:10:37",
            "pivot": {
                "book_id": 1,
                "user_id": 1,
                "id": 18,
                "comment": "コメントです"
            }
        }
      ]
    })
  })

  //表示テスト
  it("表示テスト",() => {
    expect(component.text()).toContain("コメント")
    expect(component.text()).toContain("戻る")
    expect(component.text()).toContain("削除")
  });

  it("コメントを表示", async() => {
    expect(component.text()).toContain("コメントです")
  });

  it("最初はコメントフォームが非表示", () => {
    const edit = component.find('input-form')
    expect(edit.exists()).toBe(false)
  });

  it("コメントボタンでコメントフォームを表示", async() => {
    await component.get('#addform').trigger('click')
    expect(component.text()).toContain("投稿")
  });

  it("コメントフォームをキャンセルした時非表示に戻る", async() => {
    await component.get('#addform').trigger('click')
    component.get('#cancel').trigger('click')
    const edit = component.find('input-form')
    expect(edit.exists()).toBe(false)
  });
  
  //各ボタンのテスト
  it("投稿ボタンをクリックしてメソッドが呼び出されているかテスト", async() => {
    await component.get('#addform').trigger('click')
    component.get('#add').trigger('click')
    expect(addComment).toHaveBeenCalled()
  });

  it("削除ボタンをクリックしてメソッドが呼び出されているかテスト", () => {
    component.get('#delete').trigger('click')
    expect(deleteComment).toHaveBeenCalled()
  });

  it("戻るボタンをクリックしてメソッドが呼び出されているかテスト", () => {
    component.get('#return').trigger('click')
    expect(returnIndex).toHaveBeenCalled()
  });

});
