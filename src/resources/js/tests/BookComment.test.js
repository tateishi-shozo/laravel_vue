import BookComponent from "../components/BookComponent";

const { mount } = require("@vue/test-utils");

describe("BookComponent", () => {
    
    const addBook = jest.spyOn(BookComponent.methods, 'addBook');
    const updateForm = jest.spyOn(BookComponent.methods, 'updateForm');
    const deleteBook = jest.spyOn(BookComponent.methods, 'deleteBook');
    const updateBook = jest.spyOn(BookComponent.methods, 'updateBook');
    const logout = jest.spyOn(BookComponent.methods, 'logout');
    const  getComment = jest.spyOn(BookComponent.methods, 'getComment');
    const searchBook = jest.spyOn(BookComponent.methods, 'searchBook');
    const nextPage = jest.spyOn(BookComponent.methods, 'nextPage');

  let component;

  beforeEach(() => {
    component = mount(BookComponent);
    component.setData({
      books: [
        {
            id: 1,
            category: "コミック/雑誌",
            title: "テスト本",
            created_at: "2023-01-30 12:12:16",
            updated_at: "2023-01-30 12:12:16",
            user_id: 1,
            comments_count: 0,
            user_name: "立石"
        },
      ],
      token: "Bearer 19|glKmBgcgxUPvJPL7WPX0P7LmQzfyE8eDnEqZGNsa",
      user_id: 1,
      user_name: "立石",
      current_page: 1,
      prev_page_url: '',
      next_page_url: "http://localhost/api/books?page=2",
    })
  })

  //表示テスト
  it("表示テスト",() => {
    expect(component.text()).toContain("新規投稿")
    expect(component.text()).toContain("ログアウト")
    expect(component.text()).toContain("コメント")
    expect(component.text()).toContain("次へ")
  });

  it("本一覧を表示", async() => {
    expect(component.text()).toContain("テスト本")
  });

  it("最初は新規投稿フォームが非表示", () => {
    const edit = component.find('input-form')
    expect(edit.exists()).toBe(false)
  });

  it("新規投稿ボタンで新規投稿フォームを表示", async() => {
    await component.get('#addform').trigger('click')
    expect(component.text()).toContain("新規登録")
  });

  it("新規投稿フォームをキャンセルした時非表示に戻る", async() => {
    await component.get('#addform').trigger('click')
    component.get('#cancel').trigger('click')
    const edit = component.find('input-form')
    expect(edit.exists()).toBe(false)
  });
  

  it("最初は編集フォームが非表示", () => {
    const edit = component.find('edit-form')
    expect(edit.exists()).toBe(false)
  });

  it("編集ボタンで編集フォームを表示", async() => {
    await component.get('#edit').trigger('click')
    expect(component.text()).toContain("編集フォーム")
  });

  it("編集フォームをキャンセルした時非表示に戻る", async() => {
    await component.get('#edit').trigger('click')
    component.get('#cancel').trigger('click')
    const edit = component.find('edit-form')
    expect(edit.exists()).toBe(false)
  });
  
  //各ボタンのテスト
  it("追加ボタンをクリックしてメソッドが呼び出されているかテスト", async() => {
    await component.get('#addform').trigger('click')
    component.get('#add').trigger('click')
    expect(addBook).toHaveBeenCalled()
  });

  it("編集ボタンをクリックしてメソッドが呼び出されているかテスト", () => {
    component.get('#edit').trigger('click')
    expect(updateForm).toHaveBeenCalled()
  });

  it("削除ボタンをクリックしてメソッドが呼び出されているかテスト", () => {
    component.get('#delete').trigger('click')
    expect(deleteBook).toHaveBeenCalled()
  });

  it("編集ボタンをクリックしてメソッドが呼び出されているかテスト", async() => {
    await component.get('#edit').trigger('click')
    component.get('#update').trigger('click')
    expect(updateBook).toHaveBeenCalled()
  });

  it("ログアウトボタンをクリックしてメソッドが呼び出されているかテスト", async() => {
    await component.get('#logout').trigger('click')
    expect(logout).toHaveBeenCalled()
  });

  it("検索ボタンをクリックしてメソッドが呼び出されているかテスト", async() => {
    await component.get('#search').trigger('click')
    expect(searchBook).toHaveBeenCalled()
  });

  it("次へボタンをクリックしてメソッドが呼び出されているかテスト", async() => {
    await component.get('#next').trigger('click')
    expect(nextPage).toHaveBeenCalled()
  });

  it("コメントボタンをクリックしてメソッドが呼び出されているかテスト", async() => {
    await component.get('#comment').trigger('click')
    expect(getComment).toHaveBeenCalled()
  });

});
