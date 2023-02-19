import BookComponent from "../components/BookComponent";

const { mount } = require("@vue/test-utils");

describe("BookComponent", () => {
    
    const addBook = jest.spyOn(BookComponent.methods, 'addBook');
    const updateForm = jest.spyOn(BookComponent.methods, 'updateForm');
    const deleteBook = jest.spyOn(BookComponent.methods, 'deleteBook');
    const updateBook = jest.spyOn(BookComponent.methods, 'updateBook');

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
      user_name: "立石"
    })
  })

  //表示テスト
  it("表示テスト",() => {
    expect(component.text()).toContain("新規投稿")
  });

  it("本一覧を表示", async() => {
    expect(component.text()).toContain("テスト本")
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

});
