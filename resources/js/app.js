/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

    window.Vue = require('vue');
    Vue.component(
        'passport-clients',
        require('./components/passport/Clients.vue').default
    );

    Vue.component(
        'passport-authorized-clients',
        require('./components/passport/AuthorizedClients.vue').default
    );

    Vue.component(
        'passport-personal-access-tokens',
        require('./components/passport/PersonalAccessTokens.vue').default
    );

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import App from './components/App';
import VueRouter from 'vue-router';
import router from './utils/routes';
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import Loading from 'vue-loading-overlay';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { ClientTable,ServerTable, Event } from 'vue-tables-2';
Vue.use(VueRouter);
Vue.component('v-select', vSelect);
Vue.use(Loading, {
    color: '#653F90',
    loader: 'dots'
});

const optionsSweetalert2 = {
    confirmButtonColor: '#653F90',
};
Vue.use(VueSweetalert2, optionsSweetalert2);

Vue.use(ClientTable, {
    perPage: 15,
    texts: {
        count: "Montrando del {from} al {to} de {count} Registros|{count} Registros|1 Registro",
        first: "First",
        last: "Last",
        filter: "",
        records:"......",
        filterPlaceholder: "Buscar registro",
        limit: "",
        page: "Page:",
        noResults: "Sin resultados",
        filterBy: "{column}",
        loading: "Cargando...",
        defaultOption: "Todas",
        columns: "Columns"
    }

});
Vue.use(ServerTable,{
    perPage: 15,
    texts: {
        count: "Montrando del {from} al {to} de {count} Registros|{count} Registros|1 Registro",
        first: "First",
        last: "Last",
        filter: "",
        records:"......",
        filterPlaceholder: "Buscar registro",
        limit: "",
        page: "Page:",
        noResults: "Sin resultados",
        filterBy: "{column}",
        loading: "Cargando...",
        defaultOption: "Todos",
        columns: "Columnas"
    }

})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components:{ App },
    router,
});
