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
import Login from './components/login.vue'

//Rutas de reservas
import Escritorio from './components/intranet/escritorio/index.vue'
import Reservas_agregar_modificar from './components/intranet/escritorio/agregar_modificar.vue'
import Reagendar from './components/intranet/escritorio/reagendar.vue'
import Pagar from './components/intranet/escritorio/pago.vue'

//Rutas de especialidades
import Especialidades from './components/intranet/especialidades/index.vue'
import Especialidades_agregar_modificar from './components/intranet/especialidades/agregar_modificar.vue'

//Rutas de servicios
import Servicios from './components/intranet/servicios/index.vue'
import Servicios_agregar_modificar from './components/intranet/servicios/agregar_modificar.vue'

//Horarios clinica
import HorariosClinica from './components/intranet/horarios_clinica/index.vue'
import Horarios_clinica_agregar_modificar from './components/intranet/horarios_clinica/agregar_modificar.vue'

//Rutas de profesionales
import Profesionales from './components/intranet/profesionales/index.vue' 
import Profesionales_agregar_modificar from './components/intranet/profesionales/agregar_modificar.vue' 
import Profesionales_horarios_agregar_modificar from './components/intranet/profesionales/horarios.vue' 

//Rutas de clientes
import Clientes from './components/intranet/clientes/index.vue' 
import Clientes_agregar_modificar from './components/intranet/clientes/agregar_modificar.vue' 
import Reservas_cliente from './components/intranet/clientes/reservas.vue' 

//Rutas de ventas
import Ventas from './components/intranet/ventas/index.vue' 

//Rutas de usuaruios
import Usuarios from './components/intranet/usuarios/index.vue'
import Usuarios_agregar_modificar from './components/intranet/usuarios/gestionar.vue'

//Estilos
import 'vue-toast-notification/dist/theme-default.css';

const router = new VueRouter({ 
    mode: 'history',
    routes: [
        { path: '/', name: 'login', component: Login, alias: '/login' },
        {
            path: '/home', name: 'home', component: Home, children: [
                { path: '/home', name: 'escritorio', component: Escritorio },
                
                //Ruta de reservas
                { path: '/reserva/:id?', name: 'administrar_reservas', component: Reservas_agregar_modificar },
                { path: '/reserva/reagendar/:id?', name: 'reagendar', component: Reagendar },
                { path: '/reserva/pagar/:id', name: 'pagar', component: Pagar },

                //Rutas de especialidades
                { path: '/especialidades', name: 'especialidades', component: Especialidades },
                { path: '/especialidades/administrar/:id?', name: 'administrar_especialidades', component: Especialidades_agregar_modificar },

                //Rutas de servicios
                { path: '/especialidades/administrar/servicios/:id', name: 'servicios', component: Servicios},
                { path: '/servicios/administrar/:id_especialidad?/:id?', name: 'administrar_servicios', component: Servicios_agregar_modificar},

                //Rutas de horarios clínica
                { path: '/horarios/clinica', name: 'horarios_clinica', component: HorariosClinica},
                { path: '/horarios/clinica/administrar/:id?', name: 'administrar_horarios_clinica', component: Horarios_clinica_agregar_modificar},

                //Rutas de profesionales
                { path: '/profesionales', name: 'profesionales', component: Profesionales },
                { path: '/profesionales/administrar/:id?', name: 'administrar_profesionales', component: Profesionales_agregar_modificar},
                { path: '/profesionales/horarios/administrar/:id?', name: 'administrar_horarios_profesional', component: Profesionales_horarios_agregar_modificar},

                //Rutas de clientes
                { path: '/clientes', name: 'clientes', component: Clientes},
                { path: '/clientes/administrar/:id?', name: 'administrar_clientes', component: Clientes_agregar_modificar},
                { path: '/reservas/cliente/:id?', name: 'reservas_cliente', component: Reservas_cliente},

                //Rutas de ventas
                { path: '/ventas', name: 'ventas', component: Ventas},

                //Rutas de usuarios
                { path: '/usuarios', name: 'usuarios', component: Usuarios },
                { path: '/usuarios/administrar/:id?', name: 'administrar_usuarios', component: Usuarios_agregar_modificar },
            ]
        },
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
Vue.component('errores', require('./components/general/errores.vue').default);
Vue.component('reservas-cliente', require('./components/general/reservas-cliente.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    store
});
