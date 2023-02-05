/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import { createApp } from 'vue/dist/vue.esm-bundler';
import BookComponent from './components/BookComponent.vue';
import CommentComponent from './components/CommentComponent.vue';
import LoginComponent from './components/LoginComponent.vue';
import RegisterComponent from './components/RegisterComponent.vue';

import axios from 'axios';
import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import '@babel/polyfill';

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 createApp(BookComponent).mount('#book');
 createApp(CommentComponent).mount('#comment');
 createApp(LoginComponent).mount('#login');
 createApp(RegisterComponent).mount('#register');
