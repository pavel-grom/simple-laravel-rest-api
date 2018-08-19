
require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';

import ItemsComponent from './components/ItemsComponent.vue';
import LoginComponent from './components/LoginComponent.vue';
import RegisterComponent from './components/RegisterComponent.vue';

window.Vue.use(VueRouter);
window.Vue.use(VueAxios, axios);

const routes = [
    {
        path: '/',
        name: 'tree',
        component: ItemsComponent
    },
    {
        path: '/register',
        name: 'register',
        component: RegisterComponent,
        meta: {
            auth: false
        }
    },
    {
        path: '/login',
        name: 'login',
        component: LoginComponent,
        meta: {
            auth: false
        }
    }
];

const router = new VueRouter({ routes, mode: 'history' });

window.Vue.router = router;

window.Vue.use(require('@websanova/vue-auth'), {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
    fetchData: {url: '/api/user', method: 'GET', enabled: true},
    refreshData: {
        url: '/api/refresh',
        success: function (response) {
            axios.defaults.headers.Authorization = 'Bearer ' + response.data.data.access_token;
        }
    }
});

const app = new Vue({ router }).$mount('#app');
