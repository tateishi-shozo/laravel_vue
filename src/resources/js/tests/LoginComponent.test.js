import LoginComponent from "../components/LoginComponent";

const { mount } = require("@vue/test-utils");

describe("LoginComponent", () => {
    
    const login = jest.spyOn(LoginComponent.methods, 'login');
    const registerLink = jest.spyOn(LoginComponent.methods, 'registerLink');
    const indexLink = jest.spyOn(LoginComponent.methods, 'indexLink');

  let component;

  beforeEach(() => {
    component = mount(LoginComponent);
    component.setData({
        name:'name',
        email: 'test@test',
        password: 'testtest',
    })
  })

  //表示テスト
  it("表示テスト",() => {
    expect(component.text()).toContain("ログインせずに利用する")
    expect(component.text()).toContain("ログイン")
    expect(component.text()).toContain("新規登録の方はこちら")
  });
  
  //各ボタンのテスト
  it("ログインボタンをクリックしてメソッドが呼び出されているかテスト", () => {
    component.get('#login').trigger('click')
    expect(login).toHaveBeenCalled()
  });

  it("ログインせずに利用するボタンをクリックしてメソッドが呼び出されているかテスト", () => {
    component.get('#indexlink').trigger('click')
    expect(indexLink).toHaveBeenCalled()
  });

  it("新規登録の方はこちらボタンをクリックしてメソッドが呼び出されているかテスト", () => {
    component.get('#registerlink').trigger('click')
    expect(registerLink).toHaveBeenCalled()
  });

});
