<template>
    <div>
        <titulo-pagina titulo="Horarios clínica" :accion="true">
            <template slot="btn_accion">
                <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="agregar_modificar_horario"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
            </template>
        </titulo-pagina>

        <section class="main-content">
            <b-card header-text-variant="white" header="Información sobre el horario" header-bg-variant="primary">
                <b-form>
                    <b-row>
                        <b-col md="6">                    
                            <b-form-group label="Hora">
                                <b-input-group prepend="00:00">
                                    <b-form-input
                                        type="time"
                                        v-model="$v.formulario.horario_clinica.hora.$model" 
                                        :state="$v.formulario.horario_clinica.hora.$dirty ? !$v.formulario.horario_clinica.hora.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.horario_clinica.hora.$dirty ? !$v.formulario.horario_clinica.hora.$error : null">
                                <errores :errores="errores_horario_clinica_hora"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col md="6">                    
                            <b-form-group label="Tipo de hora">
                                <b-input-group prepend="123">
                                    <b-form-select
                                        v-model="$v.formulario.horario_clinica.tipo_hora.$model"
                                        :state="$v.formulario.horario_clinica.tipo_hora.$dirty ? !$v.formulario.horario_clinica.tipo_hora.$error : null"
                                        :options="opciones_hora">
                                    </b-form-select>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.horario_clinica.tipo_hora.$dirty ? !$v.formulario.horario_clinica.tipo_hora.$error : null">
                                <errores :errores="errores_horario_clinica_tipo_hora"></errores>
                            </b-form-invalid-feedback>
                        </b-col>
                    </b-row>
                </b-form>
            </b-card>

            <b-row class="mb-5">
                <b-col lg="12" class="text-right">
                    <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="agregar_modificar_horario"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
                    <b-button variant="danger" class="box-shadow btn-icon btn-rounded" @click="$router.go(-1)"><i class="fa fa-long-arrow-left"></i> Volver atrás</b-button>
                </b-col>
            </b-row>
        </section>
    </div>
</template>

<script>
    import { required, requiredIf } from 'vuelidate/lib/validators'
    import { mapMutations } from 'vuex'

    export default {
        data() {
            return {
                opciones_hora: [
                    { value: null, text: 'Selecciona una opción ...'},
                    { value: 1, text: 'Normal' },
                    { value: 2, text: 'Sobrecupo' }
                ],
                modifica: false,
                formulario:{
                    horario_clinica: {
                        id: null,
                        hora: '',
                        tipo_hora: null
                    }
                },
            }
        },
        validations:{
            formulario: {
                horario_clinica: {
                    hora: {
                        required
                    },
                    tipo_hora: {
                        required
                    },
                }
            }
        },
        computed: {
            errores_horario_clinica_hora () {
                const errores = []
                if (!this.$v.formulario.horario_clinica.hora.$dirty) return errores
                !this.$v.formulario.horario_clinica.hora.required && errores.push('El campo es requerido.')
                return errores
            },
            errores_horario_clinica_tipo_hora () {
                const errores = []
                if (!this.$v.formulario.horario_clinica.tipo_hora.$dirty) return errores
                !this.$v.formulario.horario_clinica.tipo_hora.required && errores.push('El campo es requerido.')
                return errores
            },
        },
        methods: {
            listar_horario_clinica(){
                if(!this.$route.params.id){
                    return false
                }

                let me = this

                axios.get('/api/horarios/clinica/'+ this.$route.params.id).then(function (response) {
                    me.formulario.horario_clinica.id = response.data.id
                    me.formulario.horario_clinica.hora = response.data.hora
                    me.formulario.horario_clinica.tipo_hora = response.data.tipo_hora

                    me.modifica = true
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            agregar_modificar_horario() {
                if(this.$v.formulario.$invalid){
                    this.$v.formulario.$touch()
                    this.$store.commit('msg_error', 'Recuerda llenar correctamente el formulario.')
                    return
                }

                let me = this

                var ruta = this.modifica ? '/api/horarios/clinica/' + this.formulario.horario_clinica.id : '/api/horarios/clinica'
                var method = this.modifica ? 'PUT' : 'POST'

                axios({
                    method: method,
                    url: ruta,
                    data: {
                        'id'        : me.formulario.horario_clinica.id,
                        'hora'      : me.formulario.horario_clinica.hora,
                        'tipo_hora' : me.formulario.horario_clinica.tipo_hora
                    }
                }).then(function (response) {
                    me.limpiar_datos()
                    me.$store.commit('msg_success', me.modifica ? 2 : 1)
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            limpiar_datos(){
                if(!this.modifica){
                    this.formulario.horario_clinica.id = null
                    this.formulario.horario_clinica.hora = ''
                    this.formulario.horario_clinica.tipo_hora = null
                } else {
                    this.$router.go(-1)
                }

            }
        },
        mounted() {
            this.listar_horario_clinica()
        }
    }
</script>
