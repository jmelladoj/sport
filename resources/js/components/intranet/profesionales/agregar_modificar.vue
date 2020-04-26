<template>
    <div>
        <titulo-pagina titulo="Profesionales" :accion="true">
            <template slot="btn_accion">
                <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="agregar_modificar_profesional"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
            </template>
        </titulo-pagina>

        <section class="main-content">
            <b-card header-text-variant="white" header="Información sobre el profesional" header-bg-variant="primary">
                <b-form>
                    <b-row>
                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Especialidad">
                                <b-input-group prepend="123">
                                    <b-form-select
                                        v-model="$v.formulario.profesional.especialidad_id.$model"
                                        :state="$v.formulario.profesional.especialidad_id.$dirty ? !$v.formulario.profesional.especialidad_id.$error : null"
                                        :options="opciones_especialidad">
                                    </b-form-select>

                                    <template v-slot:append>
                                        <router-link class="btn btn-success" to="/especialidades/administrar/"  v-b-tooltip.hover title="Agregar especialidades"><i class="fa fa-plus"></i></router-link>
                                    </template>

                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.profesional.especialidad_id.$dirty ? !$v.formulario.profesional.especialidad_id.$error : null">
                                <errores :errores="errores_profesional_especialidad_id"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Run">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        v-rut:live
                                        placeholder="Ej: 18.685.728-3"
                                        v-model="$v.formulario.profesional.run.$model" 
                                        :state="$v.formulario.profesional.run.$dirty ? !$v.formulario.profesional.run.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.profesional.run.$dirty ? !$v.formulario.profesional.run.$error : null">
                                <errores :errores="errores_profesional_run"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Nombre completo">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        placeholder="Ej: Carlos Beltrán"
                                        v-model="$v.formulario.profesional.nombre.$model" 
                                        :state="$v.formulario.profesional.nombre.$dirty ? !$v.formulario.profesional.nombre.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.profesional.nombre.$dirty ? !$v.formulario.profesional.nombre.$error : null">
                                <errores :errores="errores_profesional_nombre"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Dirección">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        placeholder="Ej: Avenida a, casa #557, Talcahuano"
                                        v-model="$v.formulario.profesional.direccion.$model" 
                                        :state="$v.formulario.profesional.direccion.$dirty ? !$v.formulario.profesional.direccion.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.profesional.direccion.$dirty ? !$v.formulario.profesional.direccion.$error : null">
                                <errores :errores="errores_profesional_direccion"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Correo">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        placeholder="Ej: contacto@srcenter.cl"
                                        v-model="$v.formulario.profesional.correo.$model" 
                                        :state="$v.formulario.profesional.correo.$dirty ? !$v.formulario.profesional.correo.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.profesional.correo.$dirty ? !$v.formulario.profesional.correo.$error : null">
                                <errores :errores="errores_profesional_correo"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Teléfono">
                                <b-input-group prepend="123">
                                    <b-form-input
                                        placeholder="Ej: 987654321"
                                        v-model="$v.formulario.profesional.telefono.$model" 
                                        :state="$v.formulario.profesional.telefono.$dirty ? !$v.formulario.profesional.telefono.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.profesional.telefono.$dirty ? !$v.formulario.profesional.telefono.$error : null">
                                <errores :errores="errores_profesional_telefono"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="12">
                            <b-form-group label="Observaciones">
                                <b-form-textarea
                                    placeholder="Ej: Profesional solo con disponibilidad los días lunes."
                                    v-model="$v.formulario.profesional.observacion.$model" 
                                    :state="$v.formulario.profesional.observacion.$dirty ? !$v.formulario.profesional.observacion.$error : null">
                                    rows="6"
                                    max-rows="10"
                                ></b-form-textarea>

                                <b-form-invalid-feedback :state="$v.formulario.profesional.observacion.$dirty ? !$v.formulario.profesional.observacion.$error : null">
                                <errores :errores="errores_profesional_observacion"></errores>
                            </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </b-form>
            </b-card>

            <b-row class="mb-5">
                <b-col lg="12" class="text-right">
                    <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="agregar_modificar_profesional"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
                    <b-button variant="danger" class="box-shadow btn-icon btn-rounded" @click="$router.go(-1)"><i class="fa fa-long-arrow-left"></i> Volver atrás</b-button>
                </b-col>
            </b-row>
        </section>
    </div>
</template>

<script>
    import { required, between, email, minLength, isUnique, maxLength, numeric, requiredIf, minValue } from 'vuelidate/lib/validators'
    import { mapMutations } from 'vuex'

    export default {
        data() {
            return {
                modifica: false,
                opciones_especialidad: [],
                formulario:{
                    profesional: {
                        id: null,
                        run: '',
                        nombre: '',
                        direccion: '',
                        correo: '',
                        telefono: null,
                        especialidad_id: null,
                        observacion: ''
                    }
                },
            }
        },
        validations:{
            formulario: {
                profesional: {
                    run: {
                        required,
                        minLength: minLength(9),
                        async isUnique (value) {
                            if(this.modifica || value === '' || value.length < 9) return true

                            return new Promise((resolve, reject) => {
                                axios.get(`/api/clientes/unico/${value}`)
                                .then((response) => {
                                    this.isUnique = response.data
                                    resolve(response.data)
                                })
                                .catch((error) => {
                                    this.isUnique = false
                                    reject(false)
                                })
                            })                      
                        }
                    },
                    nombre: {
                        required,
                        minLength: minLength(3)
                    },
                    direccion: {
                        required,
                        minLength: minLength(3)
                    },
                    correo: {
                        required,
                        email,
                        minLength: minLength(3)
                    },
                    telefono: {
                        required,
                        numeric,
                        minLength: minLength(9),
                        maxLength: maxLength(9)
                    },
                    especialidad_id: {
                        required,
                        numeric,
                        minValue: minValue(1)
                    },
                    observacion: {
                        requiredIf: requiredIf((value) => {
                            return value.length < 3
                        }),
                        minLength: minLength(3),
                    }
                },
            }
        },
        computed: {
            errores_profesional_run () {
                const errores = []
                if (!this.$v.formulario.profesional.run.$dirty) return errores
                !this.$v.formulario.profesional.run.required && errores.push('El campo es requerido.')
                !this.$v.formulario.profesional.run.minLength && errores.push('Ingresa al menos 9 caracteres.')
                !this.$v.formulario.profesional.run.isUnique && errores.push('El profesional ya se encuentra ingresado en el sistema.')
                return errores
            },
            errores_profesional_nombre () {
                const errores = []
                if (!this.$v.formulario.profesional.nombre.$dirty) return errores
                !this.$v.formulario.profesional.nombre.required && errores.push('El campo es requerido.')
                !this.$v.formulario.profesional.nombre.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_profesional_direccion () {
                const errores = []
                if (!this.$v.formulario.profesional.direccion.$dirty) return errores
                !this.$v.formulario.profesional.direccion.required && errores.push('El campo es requerido.')
                !this.$v.formulario.profesional.direccion.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_profesional_correo () {
                const errores = []
                if (!this.$v.formulario.profesional.correo.$dirty) return errores
                !this.$v.formulario.profesional.correo.required && errores.push('El campo es requerido.')
                !this.$v.formulario.profesional.correo.email && errores.push('Debes ingresar un mail válido.')
                !this.$v.formulario.profesional.correo.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_profesional_telefono () {
                const errores = []
                if (!this.$v.formulario.profesional.telefono.$dirty) return errores
                !this.$v.formulario.profesional.telefono.required && errores.push('El campo es requerido.')
                !this.$v.formulario.profesional.telefono.numeric && errores.push('Solo se aceptan números.')
                !this.$v.formulario.profesional.telefono.minLength || !this.$v.formulario.profesional.telefono.maxLength && errores.push('Debes ingresar un número de 9 dígitos.')
                return errores
            },
            errores_profesional_especialidad_id () {
                const errores = []
                if (!this.$v.formulario.profesional.especialidad_id.$dirty) return errores
                !this.$v.formulario.profesional.especialidad_id.required && errores.push('El campo es requerido.')
                return errores
            },
            errores_profesional_observacion () {
                const errores = []
                if (!this.$v.formulario.profesional.observacion.$dirty) return errores
                !this.$v.formulario.profesional.observacion.required && errores.push('El campo es requerido.')
                !this.$v.formulario.profesional.observacion.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
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
                        me.formulario.profesional.especialidad_id = me.$route.params.id_especialidad
                    }

                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            listar_profesional(){
                if(!this.$route.params.id){
                    return false
                }

                let me = this

                axios.get('/api/profesionales/'+ this.$route.params.id).then(function (response) {
                    me.formulario.profesional.id = response.data.id
                    me.formulario.profesional.run = response.data.run
                    me.formulario.profesional.nombre = response.data.nombre
                    me.formulario.profesional.direccion = response.data.direccion
                    me.formulario.profesional.correo = response.data.correo
                    me.formulario.profesional.telefono = response.data.telefono
                    me.formulario.profesional.especialidad_id = response.data.especialidad_id
                    me.formulario.profesional.observacion = response.data.observacion

                    me.modifica = true
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            agregar_modificar_profesional() {
                if(this.$v.formulario.$invalid){
                    this.$v.formulario.$touch()
                    this.$store.commit('msg_error', 'Recuerda llenar correctamente el formulario.')
                    return
                }

                let me = this

                var ruta = this.modifica ? '/api/profesionales/' + this.formulario.profesional.id : '/api/profesionales'
                var method = this.modifica ? 'PUT' : 'POST'

                axios({
                    method: method,
                    url: ruta,
                    data: {
                        'id'              : me.formulario.profesional.id,
                        'run'             : me.formulario.profesional.run,
                        'nombre'          : me.formulario.profesional.nombre,
                        'direccion'       : me.formulario.profesional.direccion,
                        'correo'          : me.formulario.profesional.correo,
                        'telefono'        : me.formulario.profesional.telefono,
                        'especialidad_id' : me.formulario.profesional.especialidad_id,
                        'observacion'     : me.formulario.profesional.observacion,
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
                    this.formulario.profesional.id = null
                    this.formulario.profesional.run = ''
                    this.formulario.profesional.nombre = ''
                    this.formulario.profesional.direccion = ''
                    this.formulario.profesional.correo = ''
                    this.formulario.profesional.telefono = null
                    this.formulario.profesional.especialidad_id = null
                    this.formulario.profesional.observacion = ''
                } else {
                    this.$router.go(-1)
                }

            }
        },
        mounted() {
            this.listar_especialidades()
            this.listar_profesional()
        }
    }
</script>
