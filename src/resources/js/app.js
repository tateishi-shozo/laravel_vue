import { createApp } from 'vue/dist/vue.esm-bundler.js'
import TestVue from './components/TestVue.vue'

const app = createApp({})
app.component('test-vue', TestVue);
app.mount('#app');