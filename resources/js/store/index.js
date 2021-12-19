import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import dashboard from './dashboard'

export default new Vuex.Store({
    state: {
    },
    mutations: {
    },
    actions: {
    },
    modules: {
        dashboard: dashboard,
    }
})



