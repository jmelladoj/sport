<template>
    <div>
        <titulo-pagina v-if="nombre_especialidad" :titulo="nombre_especialidad" :accion="true">
            <template slot="btn_accion">
                <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="agregar_modificar_servicio"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
            </template>
        </titulo-pagina>

        <section class="main-content">
            <b-card header-text-variant="white" header="Información sobre el servicio" header-bg-variant="primary">
                <b-form>
                    <b-row>
                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Nombre">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        placeholder="Ej: Recovery"
                                        v-model="$v.formulario.servicio.nombre.$model" 
                                        :state="$v.formulario.servicio.nombre.$dirty ? !$v.formulario.servicio.nombre.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.servicio.nombre.$dirty ? !$v.formulario.servicio.nombre.$error : null">
                                <errores :errores="errores_servicio_nombre"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Especialidad">
                                <b-input-group prepend="123">
                                    <b-form-select
                                        v-model="$v.formulario.servicio.especialidad_id.$model"
                                        :state="$v.formulario.servicio.especialidad_id.$dirty ? !$v.formulario.servicio.especialidad_id.$error : null"
                                        :options="opciones_especialidad">
                                    </b-form-select>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.servicio.especialidad_id.$dirty ? !$v.formulario.servicio.especialidad_id.$error : null">
                                <errores :errores="errores_servicio_especialidad_id"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Valor">
                                <b-input-group prepend="123">
                                    <b-form-input
                                        placeholder="Ej: 2000"
                                        v-model="$v.formulario.servicio.valor.$model" 
                                        :state="$v.formulario.servicio.valor.$dirty ? !$v.formulario.servicio.valor.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.servicio.valor.$dirty ? !$v.formulario.servicio.valor.$error : null">
                                <errores :errores="errores_servicio_valor"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Sesiones">
                                <b-input-group prepend="123">
                                    <b-form-input
                                        placeholder="Ej: 1"
                                        v-model="$v.formulario.servicio.cantidad_sesiones.$model" 
                                        :state="$v.formulario.servicio.cantidad_sesiones.$dirty ? !$v.formulario.servicio.cantidad_sesiones.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.servicio.cantidad_sesiones.$dirty ? !$v.formulario.servicio.cantidad_sesiones.$error : null">
                                <errores :errores="errores_servicio_cantidad_sesiones"></errores>
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
                opciones_especialidad: [],
                formulario:{
                    servicio: {
                        id: null,
                        nombre: '',
                        valor: null,
                        cantidad_sesiones: null,
                        especialidad_id: null
                    }
                },
            }
        },
        validations:{
            formulario: {
                servicio: {
                    nombre: {
                        required,
                        minLength: minLength(3)
                    },
                    valor: {
                        required,
                        numeric,
                        minValue: minValue(0)
                    },
                    cantidad_sesiones: {
                        required,
                        numeric,
                        minValue: minValue(1)
                    },
                    especialidad_id: {
                        required,
                        numeric,
                        minValue: minValue(1)
                    }
                }
            }
        },
        computed: {
            nombre_especialidad(){
                var especialidad = this.opciones_especialidad.find(e => e.value == this.$route.params.id_especialidad)
                return especialidad ? 'Servicios de ' + especialidad.text : 'Servicios'
            },
            errores_servicio_nombre () {
                const errores = []
                if (!this.$v.formulario.servicio.nombre.$dirty) return errores
                !this.$v.formulario.servicio.nombre.required && errores.push('El campo es requerido.')
                !this.$v.formulario.servicio.nombre.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_servicio_valor () {
                const errores = []
                if (!this.$v.formulario.servicio.valor.$dirty) return errores
                !this.$v.formulario.servicio.valor.required && errores.push('El campo es requerido.')
                !this.$v.formulario.servicio.valor.numeric && errores.push('Solo se aceptan números.')
                !this.$v.formulario.servicio.valor.minValue && errores.push('El valor mínimo aceptado es 0.')
                return errores
            },
            errores_servicio_cantidad_sesiones () {
                const errores = []
                if (!this.$v.formulario.servicio.cantidad_sesiones.$dirty) return errores
                !this.$v.formulario.servicio.cantidad_sesiones.required && errores.push('El campo es requerido.')
                !this.$v.formulario.servicio.cantidad_sesiones.numeric && errores.push('Solo se aceptan números.')
                !this.$v.formulario.servicio.cantidad_sesiones.minValue && errores.push('El valor mínimo aceptado es 0.')
                return errores
            },
            errores_servicio_especialidad_id () {
                const errores = []
                if (!this.$v.formulario.servicio.especialidad_id.$dirty) return errores
                !this.$v.formulario.servicio.especialidad_id.required && errores.push('El campo es requerido.')
                !this.$v.formulario.servicio.especialidad_id.numeric && errores.push('Solo se aceptan números.')
                !this.$v.formulario.servicio.especialidad_id.minValue && errores.push('El valor mínimo aceptado es 0.')
                return errores
            }
        },
        methods: {
            listar_especialidades(){
                let me = this

                axios.get('/api/especialidades').then(function (response) {
                    var item = {
                        value: null,
                        text: 'Selecciona una opción ...'
                    }

                    me.opciones_especialidad.push(item)

                    response.data.forEach(function(e) {
                        var especialidad = {
                            value: e.id,
                            text: e.nombre
                        }

                        me.opciones_especialidad.push(especialidad)
                    })

                    if(me.$route.params.id_especialidad){
                        me.formulario.servicio.especialidad_id = me.$route.params.id_especialidad
                    }

                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            listar_servicio(){
                if(!this.$route.params.id){
                    return false
                }

                let me = this

                axios.get('/api/servicios/'+ this.$route.params.id).then(function (response) {
                    me.formulario.servicio.id = response.data.area_id
                    
                    me.formulario.servicio.id = response.data.id
                    me.formulario.servicio.nombre = response.data.nombre
                    me.formulario.servicio.valor = response.data.valor
                    me.formulario.servicio.cantidad_sesiones = response.data.cantidad_sesiones
                    me.formulario.servicio.especialidad_id = response.data.especialidad_id

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

                var ruta = this.modifica ? '/api/servicios/' + this.formulario.servicio.id : '/api/servicios'
                var method = this.modifica ? 'PUT' : 'POST'

                axios({
                    method: method,
                    url: ruta,
                    data: {
                        'id'                 : me.formulario.servicio.id,
                        'nombre'             : me.formulario.servicio.nombre,
                        'valor'              : me.formulario.servicio.valor,
                        'cantidad_sesiones'  : me.formulario.servicio.cantidad_sesiones,
                        'especialidad_id'    : me.formulario.servicio.especialidad_id
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
                    this.formulario.servicio.id = null
                    this.formulario.servicio.nombre = ''
                    this.formulario.servicio.valor = null
                    this.formulario.servicio.cantidad_sesiones = null
                    this.formulario.servicio.especialidad_id = null
                } else {
                    this.$router.go(-1)
                }

            }
        },
        mounted() {
            this.listar_especialidades()
            this.listar_servicio()
        }
    }
</script>
