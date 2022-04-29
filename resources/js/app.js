require('./bootstrap');
// Import modules...
//import JQuery from 'jquery-ui/themes/base/all.css';
import Vue from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';
import PortalVue from 'portal-vue';
//add these two line
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

//import  i18n from  '@/plugins/vue-i18n'
/*import 'jquery-ui/external/jquery-3.6.0/jquery.js'
import JQuery from "jquery/src/jquery.js"
global.$ = JQuery;
*/

Vue.mixin({ methods: { route } });
Vue.use(InertiaPlugin);
Vue.use(PortalVue);
//also add this line
Vue.use(Vuetify);
const app = document.getElementById('app');

new Vue({
    //finally add this line
    //i18n,
    vuetify: new Vuetify(),
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);