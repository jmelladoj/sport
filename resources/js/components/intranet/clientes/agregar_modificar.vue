<template>
    <div>
        <titulo-pagina titulo="Clientes" :accion="true">
            <template slot="btn_accion">
                <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="agregar_modificar_cliente"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
            </template>
        </titulo-pagina>

        <section class="main-content">
            <b-card header-text-variant="white" header="Información sobre el cliente" header-bg-variant="primary">
                <b-form>
                    <b-row>
                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Run">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        v-rut:live
                                        placeholder="Ej: 18.685.728-3"
                                        v-model="$v.formulario.cliente.run.$model" 
                                        :state="$v.formulario.cliente.run.$dirty ? !$v.formulario.cliente.run.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.cliente.run.$dirty ? !$v.formulario.cliente.run.$error : null">
                                <errores :errores="errores_cliente_run"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Nombre completo">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        placeholder="Ej: Carlos Beltrán"
                                        v-model="$v.formulario.cliente.nombre.$model" 
                                        :state="$v.formulario.cliente.nombre.$dirty ? !$v.formulario.cliente.nombre.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.cliente.nombre.$dirty ? !$v.formulario.cliente.nombre.$error : null">
                                <errores :errores="errores_cliente_nombre"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Dirección">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        placeholder="Ej: Avenida a, casa #557, Talcahuano"
                                        v-model="$v.formulario.cliente.direccion.$model" 
                                        :state="$v.formulario.cliente.direccion.$dirty ? !$v.formulario.cliente.direccion.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.cliente.direccion.$dirty ? !$v.formulario.cliente.direccion.$error : null">
                                <errores :errores="errores_cliente_direccion"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Correo">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        placeholder="Ej: contacto@srcenter.cl"
                                        v-model="$v.formulario.cliente.correo.$model" 
                                        :state="$v.formulario.cliente.correo.$dirty ? !$v.formulario.cliente.correo.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.cliente.correo.$dirty ? !$v.formulario.cliente.correo.$error : null">
                                <errores :errores="errores_cliente_correo"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">                    
                            <b-form-group label="Teléfono">
                                <b-input-group prepend="123">
                                    <b-form-input
                                        placeholder="Ej: 987654321"
                                        v-model="$v.formulario.cliente.telefono.$model" 
                                        :state="$v.formulario.cliente.telefono.$dirty ? !$v.formulario.cliente.telefono.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.cliente.telefono.$dirty ? !$v.formulario.cliente.telefono.$error : null">
                                <errores :errores="errores_cliente_telefono"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="6">
                            <b-form-group label="Fecha de nacimiento">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        type="date"
                                        v-model="$v.formulario.cliente.fecha_nacimiento.$model" 
                                        :state="$v.formulario.cliente.fecha_nacimiento.$dirty ? !$v.formulario.cliente.fecha_nacimiento.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>


                            <b-form-invalid-feedback :state="$v.formulario.cliente.fecha_nacimiento.$dirty ? !$v.formulario.cliente.fecha_nacimiento.$error : null">
                                <errores :errores="errores_cliente_fecha_nacimiento"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col xs="12" sm="12" md="12">
                            <b-form-group label="Observaciones">
                                <b-form-textarea
                                    placeholder="Ej: cliente solo con disponibilidad los días lunes."
                                    v-model="$v.formulario.cliente.observacion.$model" 
                                    :state="$v.formulario.cliente.observacion.$dirty ? !$v.formulario.cliente.observacion.$error : null">
                                    rows="6"
                                    max-rows="10"
                                ></b-form-textarea>

                                <b-form-invalid-feedback :state="$v.formulario.cliente.observacion.$dirty ? !$v.formulario.cliente.observacion.$error : null">
                                <errores :errores="errores_cliente_observacion"></errores>
                            </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </b-form>
            </b-card>

            <b-row class="mb-5">
                <b-col lg="12" class="text-right">
                    <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="agregar_modificar_cliente"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
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
                    cliente: {
                        id: null,
                        run: '',
                        nombre: '',
                        direccion: '',
                        correo: '',
                        telefono: null,
                        fecha_nacimiento: null,
                        observacion: ''
                    }
                },
            }
        },
        validations:{
            formulario: {
                cliente: {
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
                    fecha_nacimiento: {
                        required,
                        maxValue: value => value < new Date().toISOString()
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
            errores_cliente_run () {
                const errores = []
                if (!this.$v.formulario.cliente.run.$dirty) return errores
                !this.$v.formulario.cliente.run.required && errores.push('El campo es requerido.')
                !this.$v.formulario.cliente.run.minLength && errores.push('Ingresa al menos 9 caracteres.')
                !this.$v.formulario.cliente.run.isUnique && errores.push('El cliente ya se encuentra ingresado en el sistema.')
                return errores
            },
            errores_cliente_nombre () {
                const errores = []
                if (!this.$v.formulario.cliente.nombre.$dirty) return errores
                !this.$v.formulario.cliente.nombre.required && errores.push('El campo es requerido.')
                !this.$v.formulario.cliente.nombre.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_cliente_direccion () {
                const errores = []
                if (!this.$v.formulario.cliente.direccion.$dirty) return errores
                !this.$v.formulario.cliente.direccion.required && errores.push('El campo es requerido.')
                !this.$v.formulario.cliente.direccion.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_cliente_correo () {
                const errores = []
                if (!this.$v.formulario.cliente.correo.$dirty) return errores
                !this.$v.formulario.cliente.correo.required && errores.push('El campo es requerido.')
                !this.$v.formulario.cliente.correo.email && errores.push('Debes ingresar un mail válido.')
                !this.$v.formulario.cliente.correo.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_cliente_telefono () {
                const errores = []
                if (!this.$v.formulario.cliente.telefono.$dirty) return errores
                !this.$v.formulario.cliente.telefono.required && errores.push('El campo es requerido.')
                !this.$v.formulario.cliente.telefono.numeric && errores.push('Solo se aceptan números.')
                !this.$v.formulario.cliente.telefono.minLength || !this.$v.formulario.cliente.telefono.maxLength && errores.push('Debes ingresar un número de 9 dígitos.')
                return errores
            },
            errores_cliente_fecha_nacimiento () {
                const errores = []
                if (!this.$v.formulario.cliente.fecha_nacimiento.$dirty) return errores
                !this.$v.formulario.cliente.fecha_nacimiento.required && errores.push('El campo es requerido.')
                !this.$v.formulario.cliente.fecha_nacimiento.maxValue && errores.push('La fecha de nacimiento no puede ser mayor a hoy.')
                return errores
            },
            errores_cliente_observacion () {
                const errores = []
                if (!this.$v.formulario.cliente.observacion.$dirty) return errores
                !this.$v.formulario.cliente.observacion.required && errores.push('El campo es requerido.')
                !this.$v.formulario.cliente.observacion.minLength && errores.push('Ingresa al menos 3 caracteres.')
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
                        me.formulario.cliente.fecha_nacimiento = me.$route.params.id_especialidad
                    }

                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            listar_cliente(){
                if(!this.$route.params.id){
                    return false
                }

                let me = this

                axios.get('/api/clientes/'+ this.$route.params.id).then(function (response) {
                    me.formulario.cliente.id = response.data.id
                    me.formulario.cliente.run = response.data.run
                    me.formulario.cliente.nombre = response.data.nombre
                    me.formulario.cliente.direccion = response.data.direccion
                    me.formulario.cliente.correo = response.data.correo
                    me.formulario.cliente.telefono = response.data.telefono
                    me.formulario.cliente.fecha_nacimiento = response.data.fecha_nacimiento
                    me.formulario.cliente.observacion = response.data.observacion

                    me.modifica = true
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            agregar_modificar_cliente() {
                if(this.$v.formulario.$invalid){
                    this.$v.formulario.$touch()
                    this.$store.commit('msg_error', 'Recuerda llenar correctamente el formulario.')
                    return
                }

                let me = this

                var ruta = this.modifica ? '/api/clientes/' + this.formulario.cliente.id : '/api/clientes'
                var method = this.modifica ? 'PUT' : 'POST'

                axios({
                    method: method,
                    url: ruta,
                    data: {
                        'id'              : me.formulario.cliente.id,
                        'run'             : me.formulario.cliente.run,
                        'nombre'          : me.formulario.cliente.nombre,
                        'direccion'       : me.formulario.cliente.direccion,
                        'correo'          : me.formulario.cliente.correo,
                        'telefono'        : me.formulario.cliente.telefono,
                        'fecha_nacimiento' : me.formulario.cliente.fecha_nacimiento,
                        'observacion'     : me.formulario.cliente.observacion,
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
                    this.formulario.cliente.id = null
                    this.formulario.cliente.run = ''
                    this.formulario.cliente.nombre = ''
                    this.formulario.cliente.direccion = ''
                    this.formulario.cliente.correo = ''
                    this.formulario.cliente.telefono = null
                    this.formulario.cliente.fecha_nacimiento = null
                    this.formulario.cliente.observacion = ''
                } else {
                    this.$router.go(-1)
                }

            }
        },
        mounted() {
            this.listar_especialidades()
            this.listar_cliente()
        }
    }
</script>
