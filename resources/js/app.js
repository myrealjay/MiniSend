require('./bootstrap');
import Vue from 'vue';
import App from './App.vue';

import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import axios from 'axios';
import { routes } from './routes';
import { initialize } from './helpers/intercept.js';
import datatable from './components//datatables/datatable.vue';
import pagination from './components/datatables/Pagination.vue';
import vSelect from 'vue-select'

Vue.use(VueRouter);
Vue.use(VueAxios, axios);

const router = new VueRouter({
    routes,
    mode: 'history'
});

initialize(router);

Vue.component("datatable", datatable);
Vue.component("pagination", pagination);
Vue.component('v-select', vSelect)

const app = new Vue({
    el: '#app',
    router: router,
    components: {
        App,
    }
});