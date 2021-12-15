require('./bootstrap');

// Import modules...
import Vue from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';
import { Head,Link } from '@inertiajs/inertia-vue';

import PortalVue from 'portal-vue';
import Nav from './Partials/Nav';
import 'boxicons/css/boxicons.min.css';
import Vuesax from 'vuesax';
import 'vuesax/dist/vuesax.css';
import RootLayout from "@/Pages/Layouts/RootLayout";
import Layout from "@/Pages/Layouts/Layout";
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";

import './helper';
import { InertiaProgress } from '@inertiajs/progress';
InertiaProgress.init({
    // The delay after which the progress bar will
    // appear during navigation, in milliseconds.
    delay: 250,

    // The color of the progress bar.
    color: '#29d',

    // Whether to include the default NProgress styles.
    includeCSS: true,

    // Whether the NProgress spinner will be shown.
    showSpinner: true,
});

Vue.prototype.$route = route

Vue.mixin({ methods: { route } });
Vue.use(InertiaPlugin);
Vue.use(PortalVue);

Vue.use(Vuesax, {
    // options here
});

Vue.component('Nav', Nav);
Vue.component('Head', Head);
Vue.component('InertiaLink', Link);
Vue.component('Link', Link);
Vue.component('Layout', Layout);
Vue.component('DashboardLayout', DashboardLayout);


const app = document.getElementById('app');

new Vue({
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
