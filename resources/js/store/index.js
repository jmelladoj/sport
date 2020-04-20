import Vue from 'vue'
import Vuex from 'vuex'

import usuario from './usuario'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        notificacion: {
            mensaje: ''
        },
        lugares: []
    },
    mutations: {
        msg_info(state, mensaje){
            state.notificacion.mensaje = mensaje

            Vue.$toast.open({
                message: mensaje,
                type: 'info',
                duration: 5000
            })

            state.notificacion.mensaje = ''
        },
        msg_success(state, accion, mensaje = ""){
            state.notificacion.mensaje = mensaje
            
            if(accion){
                switch (accion) {
                    case 1:
                        mensaje = "Registro agregado existosamente."
                        break;
                    case 2:
                        mensaje = "Registro actualizado existosamente."
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
        msg_warning(state, mensaje){
            state.notificacion.mensaje = mensaje

            Vue.$toast.open({
                message: mensaje,
                type: 'warning',
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
