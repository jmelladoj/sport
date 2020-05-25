<template>
    <div>
        <titulo-pagina titulo="Regendar reserva" :accion="true">
            <template slot="btn_accion">
                <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="reagendar_reserva"><i class="fa fa-pencil"></i>Actualizar registro</b-button>
            </template>
        </titulo-pagina>

        <section class="main-content">
            <b-card header-text-variant="white" header="Información sobre el servicio" header-bg-variant="primary">
                <b-form>
                    <b-row>
                        <b-col md="6">                    
                            <b-form-group label="Fecha">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        type="date"
                                        v-model="$v.formulario.reserva.fecha.$model" 
                                        :state="$v.formulario.reserva.fecha.$dirty ? !$v.formulario.reserva.fecha.$error : null"
                                        @change="obtener_horarios">
                                        
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                        <b-col md=6>
                            <b-form-group label="Hora">
                                <b-input-group prepend="123" class="mt-0 mb-0">
                                    <b-form-select
                                        id="hora"
                                        v-model="$v.formulario.reserva.hora_clinicas_id.$model"
                                        :state="$v.formulario.reserva.hora_clinicas_id.$dirty ? !$v.formulario.reserva.hora_clinicas_id.$error : null"
                                        disabled
                                        class="mt-0 mb-0">
                                    </b-form-select>
                                </b-input-group>             
                            </b-form-group>
                        </b-col>
                    </b-row>
                </b-form>
            </b-card>

            <b-row class="mb-5">
                <b-col lg="12" class="text-right">
                    <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="reagendar_reserva"><i class="fa fa-pencil"></i>Actualizar registro</b-button>
                    <b-button variant="danger" class="box-shadow btn-icon btn-rounded" @click="$router.go(-1)"><i class="fa fa-long-arrow-left"></i> Volver atrás</b-button>
                </b-col>
            </b-row>
        </section>
    </div>
</template>

<script>
    import { required } from 'vuelidate/lib/validators'
    import { mapMutations } from 'vuex'

    export default {
        data(){
            return {
                formulario: {
                    reserva: {
                        fecha: '',
                        hora_clinicas_id: null,
                        profesional_id: null
                    }
                }
            }
        },
        validations: {
            formulario: {
                reserva: {
                    fecha: {
                        required
                    },
                    hora_clinicas_id: {
                        required
                    }
                },
            }
        },
        methods: {
            listar_reserva(){
                if(!this.$route.params.id){
                    return false
                }

                let me = this

                axios.get('/api/detalle/reserva/'+ this.$route.params.id).then(function (response) {
                    me.formulario.reserva.fecha = response.data.fecha_servicio
                    me.formulario.reserva.profesional_id = response.data.reserva.profesional_id

                    me.obtener_horarios()
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            obtener_horarios(index){
                let me = this

                $("#hora").children().remove().end()
                $("#hora").prop('disabled', true)

                axios.post('/api/disponibilidad/profesional',{
                    'fecha': me.formulario.reserva.fecha,
                    'profesional_id': me.formulario.reserva.profesional_id
                }).then(function (response) {
                    $("#hora").append(new Option("Selecciona una opción ...", null))

                    response.data.forEach(function(h) {
                        $("#hora").append(new Option(h.hora, h.hora_clinicas_id))
                    })

                    if(response.data.length > 0){
                        $("#hora").prop('disabled', false)
                    } else {
                        $("#hora").children().remove().end()
                        $("#hora").append(new Option("Sin horarios de atención", null))
                    }
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })

            },
            reagendar_reserva(){
                if(this.$v.formulario.$invalid){
                    this.$v.formulario.$touch()
                    this.$store.commit('msg_error', 'Recuerda llenar correctamente el formulario.')
                    return
                }

                let me = this

                axios({
                    method: 'PUT',
                    url: '/api/detalle/reserva/' + this.$route.params.id,
                    data: {
                        'fecha_servicio': me.formulario.reserva.fecha,
                        'hora_clinicas_id': me.formulario.reserva.hora_clinicas_id
                    }
                }).then(function (response) {
                    me.$router.go(-1)
                    me.$store.commit('msg_success', 1)
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            }
        },
        mounted() {
            this.listar_reserva()
        }
    }
</script>