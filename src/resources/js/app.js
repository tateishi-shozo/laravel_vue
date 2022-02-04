require('./bootstrap');

import { createApp } from 'vue/dist/vue.esm-bundler'
import BookComponent from './components/BookComponent.vue'
import axios from 'axios'

createApp({
    components: {
        'book-component':BookComponent,
    },
}).mount('#app')