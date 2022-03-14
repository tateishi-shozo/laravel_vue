import BookComponent from "../components/BookComponent";
import { axios } from 'axios';
import { nextTick } from "vue";

const { mount } = require("@vue/test-utils");

describe("テスト", () => {
  let component;

  beforeEach(() => {
    component = mount(BookComponent);
    component.setData({
      books: [
        {id: 1, title: "テスト本", category:"文芸"}
      ]
    })
  })

  //表示テスト
  it("表示テスト",() => {
    expect(component.text()).toContain("新規登録")
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
  it("レコードを削除する", async() => {
    await component.get('#delete').trigger('click')

    expect(component.text()).not.toContain("テスト本")
  });

  it("新規作成テスト", async() => {
    component.get('#newtitle').setValue('テスト本2')
    const category = component.get('input[value="文芸"]')
    await category.setChecked()
    await component.get('#add').trigger('click')
    
    console.log(component.html())
  });


});
