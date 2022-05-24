require('./bootstrap');
// Import modules...
//import JQuery from 'jquery-ui/themes/base/all.css';
import Vue from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';
import { InertiaProgress } from '@inertiajs/progress'

import PortalVue from 'portal-vue';
//add these two line
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

//import  i18n from  '@/plugins/vue-i18n'
/*import 'jquery-ui/external/jquery-3.6.0/jquery.js'
import JQuery from "jquery/src/jquery.js"
global.$ = JQuery;
*/

InertiaProgress.init()
Vue.mixin({ methods: { route } });
Vue.use(InertiaPlugin);
Vue.use(PortalVue);
//also add this line
Vue.use(Vuetify);
const app = document.getElementById('app');

new Vue({
    vuetify: new Vuetify({ treeShake: true}),
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);