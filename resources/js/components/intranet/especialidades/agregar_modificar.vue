<template>
    <div>
        <titulo-pagina titulo="Especialidades" :accion="true">
            <template slot="btn_accion">
                <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="agregar_modificar_servicio"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
            </template>
        </titulo-pagina>

        <section class="main-content">
            <b-card header-text-variant="white" header="Información sobre el servicio" header-bg-variant="primary">
                <b-form>
                    <b-row>
                        <b-col lg="12">                    
                            <b-form-group label="Nombre">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        placeholder="Ej: Kinesiología ..."
                                        v-model="$v.formulario.especialidad.nombre.$model" 
                                        :state="$v.formulario.especialidad.nombre.$dirty ? !$v.formulario.especialidad.nombre.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.especialidad.nombre.$dirty ? !$v.formulario.especialidad.nombre.$error : null">
                                <errores :errores="errores_especialidad_nombre"></errores>
                            </b-form-invalid-feedback>
                        </b-col>
                    </b-row>
                </b-form>
            </b-card>

            <b-row class="mb-5">
                <b-col lg="12" class="text-right">
                    <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="agregar_modificar_servicio"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
                    <b-button variant="danger" class="box-shadow btn-icon btn-rounded" @click="$router.go(-1)"><i class="fa fa-long-arrow-left"></i> Volver atrás</b-button>
                </b-col>
            </b-row>
        </section>
    </div>
</template>

<script>
    import { required, minLength, numeric, minValue, requiredIf } from 'vuelidate/lib/validators'
    import { mapMutations } from 'vuex'

    export default {
        data() {
            return {
                modifica: false,
                formulario:{
                    especialidad: {
                        id: null,
                        nombre: ''
                    }
                },
            }
        },
        validations:{
            formulario: {
                especialidad: {
                    nombre: {
                        required,
                        minLength: minLength(3)
                    }
                }
            }
        },
        computed: {
            errores_especialidad_nombre () {
                const errores = []
                if (!this.$v.formulario.especialidad.nombre.$dirty) return errores
                !this.$v.formulario.especialidad.nombre.required && errores.push('El campo es requerido.')
                !this.$v.formulario.especialidad.nombre.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            }
        },
        methods: {
            listar_especialidad(){
                if(!this.$route.params.id){
                    return false
                }

                let me = this

                axios.get('/api/especialidades/'+ this.$route.params.id).then(function (response) {
                    me.formulario.especialidad.id = response.data.area_id
                    
                    me.formulario.especialidad.id = response.data.id
                    me.formulario.especialidad.nombre = response.data.nombre

                    me.modifica = true
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            agregar_modificar_servicio() {
                if(this.$v.formulario.$invalid){
                    this.$v.formulario.$touch()
                    this.$store.commit('msg_error', 'Recuerda llenar correctamente el formulario.')
                    return
                }

                let me = this

                var ruta = this.modifica ? '/api/especialidades/' + this.formulario.especialidad.id : '/api/especialidades'
                var method = this.modifica ? 'PUT' : 'POST'

                axios({
                    method: method,
                    url: ruta,
                    data: {
                        'id'          : me.formulario.especialidad.id,
                        'nombre'      : me.formulario.especialidad.nombre,
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
                    this.formulario.especialidad.id = null
                    this.formulario.especialidad.nombre = ''
                    
                    this.formulario.especialidad.id = null
                    this.formulario.especialidad.nombre = ''
                    this.formulario.especialidad.valor = null
                    this.formulario.especialidad.observacion = ''
                    this.formulario.especialidad.garantia = ''
                } else {
                    this.$router.go(-1)
                }

            }
        },
        mounted() {
            this.listar_especialidad()
        }
    }
</script>
