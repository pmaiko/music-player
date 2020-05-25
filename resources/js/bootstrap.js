window.axios = require('axios');
//window.axios.defaults.headers.common['Authorization'] = 'Bearer null';
window.jsmediatags = require("jsmediatags");
window.$ = window.jQuery = require('jquery');

require('bootstrap');

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
