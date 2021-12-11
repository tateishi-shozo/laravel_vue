import { createApp } from 'vue';
import App from './App.vue'

const helloComponent = {
        template: '<p>hello!</p>'
}

const app = Vue.createApp({
    components: {
    'hello-component': helloComponent
    }
})
app.mount('#app')