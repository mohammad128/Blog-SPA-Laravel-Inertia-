require('./bootstrap');

// Import modules...
import Vue from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';

import PortalVue from 'portal-vue';
import 'boxicons/css/boxicons.min.css';
import Vuesax from 'vuesax';
import 'vuesax/dist/vuesax.css';
import store from './store';

// Components
import RootLayout from "@/Pages/Layouts/RootLayout";
import Layout from "@/Pages/Layouts/Layout";
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";
import Nav from './Partials/Nav';
import { Head,Link } from '@inertiajs/inertia-vue';

import './helper';
import { InertiaProgress } from '@inertiajs/progress';
InertiaProgress.init({
    delay: 250,
    color: '#29d',
    includeCSS: true,
    showSpinner: true,
});

Vue.prototype.$route = route

Vue.mixin({ methods: { route } });

Vue.use(InertiaPlugin);
Vue.use(PortalVue);
Vue.use(Vuesax, {
});


Vue.component('Nav', Nav);
Vue.component('Head', Head);
Vue.component('InertiaLink', Link);
Vue.component('Link', Link);
Vue.component('Layout', Layout);
Vue.component('DashboardLayout', DashboardLayout);


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
