<template>
    <div>
        <titulo-pagina :titulo="titulo_pagina" :accion="true"></titulo-pagina>

        <section class="main-content">
            <b-form @submit="onSubmit" @keyup.enter="gestionar_usuario">
                <b-card header-text-variant="white" header="Información de usuario" header-bg-variant="primary">     
                    <b-row>
                        <b-col md="6">                    
                            <b-form-group label="Nombre">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        v-model="$v.usuario.nombre.$model" 
                                        :state="$v.usuario.nombre.$dirty ? !$v.usuario.nombre.$error : null"
                                        placeholder="Ej: Marcelo Jiménez">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.usuario.nombre.$dirty ? !$v.usuario.nombre.$error : null">
                                <errores :errores="errores_usuario_nombre"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col md="6">                    
                            <b-form-group label="Usuario">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        v-model="$v.usuario.usuario.$model" 
                                        :state="$v.usuario.usuario.$dirty ? !$v.usuario.usuario.$error : null"
                                        placeholder="Ej: invitado"
                                        :readonly="modifica">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            
                            <b-form-invalid-feedback :state="$v.usuario.usuario.$dirty ? !$v.usuario.usuario.$error : null">
                                <errores :errores="errores_usuario_usuario"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col md="6">                    
                            <b-form-group :label="!modifica ? 'Clave' : 'Cambiar clave'">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        type="password"
                                        v-model="$v.usuario.password.$model" 
                                        :state="$v.usuario.password.$dirty ? !$v.usuario.password.$error : null"
                                        placeholder="Ej: a1b2c3">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.usuario.password.$dirty ? !$v.usuario.password.$error : null">
                                <errores :errores="errores_usuario_password"></errores>
                            </b-form-invalid-feedback>
                        </b-col>


                        <b-col md="6">     
                            <b-form-group label="Tipo de usuario">
                                <b-input-group prepend="123">
                                    <b-form-select
                                        v-model="$v.usuario.tipo_usuario.$model"
                                        :state="$v.usuario.tipo_usuario.$dirty ? !$v.usuario.tipo_usuario.$error : null"
                                        :options="opciones_tipo_usuario">
                                    </b-form-select>
                                </b-input-group>
                            </b-form-group>
                                           
                            <b-form-invalid-feedback :state="$v.usuario.tipo_usuario.$dirty ? !$v.usuario.tipo_usuario.$error : null">
                                <errores :errores="errores_usuario_tipo_usuario"></errores>
                            </b-form-invalid-feedback>
                        </b-col>
                    </b-row>
                </b-card>

                <b-row class="mb-5">
                    <b-col lg="12" class="text-right">
                        <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="gestionar_usuario"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
                        <b-button variant="danger" class="box-shadow btn-icon btn-rounded" @click="$router.go(-1)"><i class="fa fa-long-arrow-left"></i> Volver atrás</b-button>
                    </b-col>
                </b-row>
            </b-form>
        </section>
    </div>
</template>

<script>
    import { required, minLength, isUnique, requiredIf, email } from 'vuelidate/lib/validators'
    import { mapMutations } from 'vuex'

    export default {
        data() {
            return {
                opciones_tipo_usuario: [
                    { value: null, text: 'Selecciona una opción ...'},
                    { value: 1, text: 'Administrador'},
                    { value: 2, text: 'Usuario normal'},
                ],
                modifica: false,
                usuario: {
                    id: 0,
                    nombre: '',
                    usuario: '',
                    password: '',
                    tipo_usuario: null
                }
            }
        },
        validations:{
            usuario: {
                usuario: {
                    required,
                    minLength: minLength(3),
                    
                    async isUnique (value) {
                        if (value.length < 3) return true
                        if (this.modifica) return true

                        return axios.get(`/api/usuarios/unico/${value}`).then(response => {
                            return response.data
                        }) 
                    }
                },
                nombre: {
                    required,
                    minLength: minLength(3)
                },
                password: {
                    required: requiredIf(function(value) {
                        return !this.modifica && value.length < 3
                    }),
                    minLength: minLength(3)
                },
                tipo_usuario: {
                    required
                }
            }
        },
        computed: {
            titulo_pagina(){
                return !this.modifica ? 'Agregar usuario' : 'Editar usuario'
            },
            errores_usuario_usuario () {
                const errores = []
                if (!this.$v.usuario.usuario.$dirty) return errores
                !this.$v.usuario.usuario.required && errores.push('El campo es requerido.')
                !this.$v.usuario.usuario.minLength && errores.push('Ingresa al menos 3 caracteres.')
                !this.$v.usuario.usuario.isUnique && errores.push('El usuario ya se encuentra ingresado en el sistema.')
                return errores
            },
            errores_usuario_nombre () {
                const errores = []
                if (!this.$v.usuario.nombre.$dirty) return errores
                !this.$v.usuario.nombre.required && errores.push('El campo es requerido.')
                !this.$v.usuario.nombre.minLength && errores.push('El campo requiere al menos 3 caracteres.')
                return errores
            },
            errores_usuario_password () {
                const errores = []
                if (!this.$v.usuario.password.$dirty) return errores
                !this.$v.usuario.password.required && errores.push('El campo es requerido.')
                !this.$v.usuario.password.minLength && errores.push('El campo requiere al menos 3 caracteres.')
                return errores
            },
            errores_usuario_tipo_usuario () {
                const errores = []
                if (!this.$v.usuario.tipo_usuario.$dirty) return errores
                !this.$v.usuario.tipo_usuario.required && errores.push('El campo es requerido.')
                return errores
            }
        },
        methods: {
            ...mapMutations(['msg_success', 'msg_error']),
            onSubmit(evt) {
                evt.preventDefault()
            },
            listar_usuario(){
                if(!this.$route.params.id){
                    return false
                }

                let me = this

                axios.get('/api/usuarios/'+ this.$route.params.id).then(function (response) {
                    me.usuario.id = response.data.id
                    me.usuario.nombre = response.data.nombre
                    me.usuario.usuario = response.data.usuario
                    me.usuario.tipo_usuario = response.data.tipo_usuario

                    me.modifica = true
                    me.$v.$touch()
                }).catch(function (error) {
                    me.msg_error()
                })

                this.$v.$touch()
            },
            gestionar_usuario() {
                if(this.$v.$invalid){
                    this.$v.$touch()
                    this.msg_error('Recuerda llenar correctamente el formulario',)
                    return
                }

                let me = this

                var ruta = this.modifica ? '/api/usuarios/' + this.usuario.id : '/api/usuarios'
                var method = this.modifica ? 'PUT' : 'POST'

                axios({
                    method: method,
                    url: ruta,
                    data: me.usuario
                }).then(function (response) {
                    me.limpiar_datos()
                    me.msg_success(me.modifica ? 2 : 1)
                }).catch(function (error) {
                    me.msg_error()
                })
            },
            limpiar_datos(){
                if(!this.modifica){
                    this.usuario.id = 0
                    this.usuario.nombre = ''
                    this.usuario.usuario = ''
                    this.usuario.password = ''
                    this.usuario.tipo_usuario = null
                } else {
                    this.$router.go(-1)
                }

            }
        },
        mounted() {
            this.listar_usuario()
        }
    }
</script>
