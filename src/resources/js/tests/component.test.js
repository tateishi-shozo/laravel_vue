import ExampleComponent from "../components/BookComponent";

const { mount } = require("@vue/test-utils");

test("テストのためのテスト", () => {
  const component = mount(BookComponent);
  console.log("a");
});