import axios from 'axios';
import collapse from '@alpinejs/collapse'
import './functions.js';

Alpine.plugin(collapse);
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
