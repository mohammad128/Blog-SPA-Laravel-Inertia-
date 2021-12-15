import Vue from 'vue';

Vue.mixin({
    methods: {
        link(e) {
            let url = e.currentTarget.getAttribute('url');
            let method = e.currentTarget.getAttribute('method');
            if(method === '') method = 'get';
            this.$inertia.visit(url, { method: method })
        }
    }
});
