/* create vue application */

import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import App from './vue/Application';
import Index from './vue/pages/Index';
import renderVueComponentToString from 'vue-server-renderer/basic';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        strings: null
    },
    getters: {
        strings: state => state.strings
    },
    mutations: {
        SetStrings(state, {strings}) {
            state.strings = strings;
        }
    }
});

var routes = [
    {path: '/', name: 'home', component: Index}
];

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes
});

const app = new Vue({
    store,
    router,
    render: h => h(App)
});

app.$router.push(url);

app.$store.commit('SetStrings', {strings: strings});

renderVueComponentToString(app, (err, html) => {
    if (err) {
        throw new Error(err);
    }
    dispatch(html);
});
