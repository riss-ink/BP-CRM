window._ = require('lodash');
try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {
}

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
import Form from './utils/form'
window.Form = Form;
import Vue from 'vue';
import router from './routes.js'
import store from './store.js'
// Modernizr
//import 'modernizr'
// Bootstrap Vue
import 'bootstrap-vue/dist/bootstrap-vue.css'
// Whirl
import 'whirl/dist/whirl.css';
// Font Awesome
import '@fortawesome/fontawesome-free/css/brands.css';
import '@fortawesome/fontawesome-free/css/regular.css';
import '@fortawesome/fontawesome-free/css/solid.css';
import '@fortawesome/fontawesome-free/css/fontawesome.css';
// Animate.CSS
import 'animate.css/animate.min.css';
// Simple line icons
import 'simple-line-icons/css/simple-line-icons.css';
// Weather Icons
import 'weather-icons/css/weather-icons.min.css';
import 'weather-icons/css/weather-icons-wind.min.css';
import BootstrapVue from 'bootstrap-vue'
import VueI18Next from '@panter/vue-i18next';
window.axios = require('axios');
axios.interceptors.response.use(function (response) {
    return response
}, function (error) {

    if (error.message.includes('401')) {
        store.dispatch('logoutUser')
        window.location.href = '/login'
    }
    return Promise.reject(error)
})
Vue.use(BootstrapVue);
Vue.use(VueI18Next);
Vue.filter('capitalize', function (value) {
    if (!value) return ''
    value = value.toString()
    value = value.split(' ')
    if(Array.isArray(value)){
        for (let s in value){
            value[s] = value[s].charAt(0).toUpperCase() + value[s].slice(1)
        }
        return value.join(' ')
    }
    return value.charAt(0).toUpperCase() + value.slice(1)
})
new Vue({
    router,
    store
}).$mount('#app')

