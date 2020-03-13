/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

window.Vue = require('vue')
window.swal = require('sweetalert2')

import BootstrapVue from 'bootstrap-vue'
import VueRouter from 'vue-router'
import Vuelidate from 'vuelidate'
import VueToast from 'vue-toast-notification'
import { rutInputDirective } from 'vue-dni'
import VueCurrencyFilter from 'vue-currency-filter'
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'

Vue.use(BootstrapVue)
Vue.use(VueRouter)
Vue.use(Vuelidate)
Vue.use(VueToast)

Vue.use(VueCurrencyFilter,{
    symbol : '$',
    thousandsSeparator: '.',
    fractionCount: 0,
    fractionSeparator: ',',
    symbolPosition: 'front',
    symbolSpacing: true
})

Vue.directive('rut', rutInputDirective)
Vue.component('vue-bootstrap-typeahead', VueBootstrapTypeahead)

//Utilidades
import store from './store/index.js'

//Componentes vue router
import Home from './components/home.vue'
import Escritorio from './components/intranet/escritorio.vue'
import Clientes from './components/intranet/clientes.vue'
import Especialidades from './components/intranet/especialidades.vue'
import HorariosClinica from './components/intranet/horarios_clinica.vue'
import Profesionales from './components/intranet/profesionales.vue'
import Servicios from './components/intranet/servicios.vue'

//Estilos
import 'vue-toast-notification/dist/index.css';

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/home',
            name: 'home',
            component: Escritorio
        },
        {
            path: '/clientes',
            name: 'clientes',
            component: Clientes
        },
        {
            path: '/especialidades',
            name: 'especialidades',
            component: Especialidades
        },
        {
            path: '/bloques',
            name: 'bloques',
            component: HorariosClinica
        },
        {
            path: '/profesionales',
            name: 'profesionales',
            component: Profesionales
        },
        {
            path: '/servicios',
            name: 'servicios',
            component: Servicios
        }
    ],
});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Componentes generales
Vue.component('titulo-pagina', require('./components/general/titulo.vue').default);

Vue.component('iniciar-sesion', require('./components/login.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: { Home },
    router,
    store
});
