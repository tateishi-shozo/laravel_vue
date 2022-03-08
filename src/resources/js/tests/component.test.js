import BookComponent from "../components/BookComponent";
import { axios } from 'axios';

const { mount } = require("@vue/test-utils");

test("テストのためのテスト", () => {
  const component = mount(BookComponent);
  console.log("a");
});
