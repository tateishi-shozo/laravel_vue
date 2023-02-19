import RegisterComponent from "../components/RegisterComponent";

const { mount } = require("@vue/test-utils");

describe("RegisterComponent", () => {
    
    const register = jest.spyOn(RegisterComponent.methods, 'register');
    const loginLink = jest.spyOn(RegisterComponent.methods, 'loginLink');
    const indexLink = jest.spyOn(RegisterComponent.methods, 'indexLink');

  let component;

  beforeEach(() => {
    component = mount(RegisterComponent);
    component.setData({
        name: 'name',
        email: 'test@test',
        password: 'testtest',
    })
  })

  //表示テスト
  it("表示テスト",() => {
    expect(component.text()).toContain("ログインせずに利用する")
    expect(component.text()).toContain("登録")
    expect(component.text()).toContain("ログインへ戻る")
  });
  
  //各ボタンのテスト
  it("登録ボタンをクリックしてメソッドが呼び出されているかテスト", () => {
    component.get('#register').trigger('click')
    expect(register).toHaveBeenCalled()
  });

  it("ログインせずに利用するボタンをクリックしてメソッドが呼び出されているかテスト", () => {
    component.get('#indexlink').trigger('click')
    expect(indexLink).toHaveBeenCalled()
  });

  it("新規登録の方はこちらボタンをクリックしてメソッドが呼び出されているかテスト", () => {
    component.get('#loginlink').trigger('click')
    expect(loginLink).toHaveBeenCalled()
  });

});
