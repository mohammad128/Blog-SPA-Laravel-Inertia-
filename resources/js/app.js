require('./bootstrap');


// Import modules...
import Vue from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';

import PortalVue from 'portal-vue';
import 'boxicons/css/boxicons.min.css';
import 'vue-inner-image-zoom/lib/vue-inner-image-zoom.css';

import Vuesax from 'vuesax';
import 'vuesax/dist/vuesax.css';
import store from './store';

import VuePlyr from 'vue-plyr'
import 'vue-plyr/dist/vue-plyr.css'
Vue.use(VuePlyr, {
    plyr: {}
})

window.fileDownload = require('js-file-download');

import VueFileAgent from 'vue-file-agent';
import VueFileAgentStyles from 'vue-file-agent/dist/vue-file-agent.css';

Vue.use(VueFileAgent);

/*
* CSRF Token
* */
let __token = document.head.querySelector('meta[name="csrf-token"]');
let csrf_token = ''
if (__token) {
    csrf_token = __token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
window.csrf_token = csrf_token;


Vue.directive('click-outside', {

    bind: function(el, binding, vNode) {
        // Provided expression must evaluate to a function.
        if (typeof binding.value !== 'function') {
            const compName = vNode.context.name
            let warn = `[Vue-click-outside:] provided expression '${binding.expression}' is not a function, but has to be`
            if (compName) { warn += `Found in component '${compName}'` }

            console.warn(warn)
        }
        // Define Handler and cache it on the element
        const bubble = binding.modifiers.bubble
        const handler = (e) => {
            if (bubble || (!el.contains(e.target) && el !== e.target)) {
                binding.value(e)
            }
        }
        el.__vueClickOutside__ = handler

        // add Event Listeners
        document.addEventListener('click', handler)
    },

    unbind: function(el, binding) {
        // Remove Event Listeners
        document.removeEventListener('click', el.__vueClickOutside__)
        el.__vueClickOutside__ = null

    }
})

// Components
import RootLayout from "@/Pages/Layouts/RootLayout";
import Layout from "@/Pages/Layouts/Layout";
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";
import Nav from './Partials/Nav';
import { Head,Link } from '@inertiajs/inertia-vue';


import './helper';
import { InertiaProgress } from '@inertiajs/progress';
InertiaProgress.init({
    delay: 0,
    color: '#ff0000',
    includeCSS: true,
    showSpinner: true,
});

Vue.prototype.$route = route

Vue.mixin({ methods: { route } });

Vue.use(InertiaPlugin);
Vue.use(PortalVue);
Vue.use(Vuesax, {
    // colors: {
    //     primary:'#3800ff',
    //     success:'rgb(23, 201, 100)',
    //     danger:'rgb(242, 19, 93)',
    //     warning:'rgb(255, 130, 0)',
    //     dark:'rgb(36, 33, 69)'
    // }
});


Vue.component('Nav', Nav);
Vue.component('Head', Head);
Vue.component('InertiaLink', Link);
Vue.component('Link', Link);
Vue.component('Layout', Layout);
Vue.component('DashboardLayout', DashboardLayout);


import VueStripeMenu from 'vue-stripe-menu'
Vue.use(VueStripeMenu)
import 'vue-stripe-menu/dist/vue-stripe-menu.css'

const app = document.getElementById('app');

new Vue({
    store,
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => {
                    let page = require(`./Pages/${name}`).default;
                    // if(!page.layout)
                    //     page.layout = Layout;

                    page.layout ??= RootLayout;

                    return page;
                },
            },
        }),
}).$mount(app);
