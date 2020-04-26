import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import createPersistedState from "vuex-persistedstate";

import usuario from './usuario'

Vue.use(Vuex)

export default new Vuex.Store({
    plugins: [createPersistedState()],
    state: {
        notificacion: {
            mensaje: ''
        },
        access_token: ''
    },
    mutations: {
        obtener_token(state, token){
            state.access_token = token
            
        },
        cambiar_token(state){
            axios.defaults.headers.common.Authorization = `Bearer ${state.access_token}`
        },
        eliminar_token(state){
            state.access_token = ''
        },
        msg_success(state, accion = 0, mensaje = ""){
            state.notificacion.mensaje = axios.defaults.headers.common.Authorization

            if(accion){
                switch (accion) {
                    case 1:
                        mensaje = "Registro agregado existosamente."
                        break;
                    case 2:
                        mensaje = "Registro actualizado existosamente."
                        break;
                    case 3: 
                        mensaje = "Registro eliminado existosamente."
                            break;
                    case 4: 
                        mensaje = "Registro restaurado existosamente."
                            break;
                }
            }

            Vue.$toast.open({
                message: mensaje,
                type: 'success',
                duration: 5000
            })

            state.notificacion.mensaje = ''
        },
        msg_error(state, mensaje = "Ha ocurrido un error al ingresar el registro."){
            state.notificacion.mensaje = mensaje

            Vue.$toast.open({
                message: state.notificacion.mensaje,
                type: 'error',
                duration: 5000
            })

            state.notificacion.mensaje = ''
        }
    },
    modules: {
        usuario
    }
})
