<template>
    <div class="misc-content">
        <b-container fluid>
            <b-row class="justify-content-center pb-5">
                <b-col xs="10" md="4">
                    <div class="misc-header text-center">
                        <img alt="" src="/assets/img/src.png" class="logo-icon margin-r-10" width="200">

                    </div>
                    <div class="misc-box">
                        <form role="form" @keyup.enter="ingresar">
                            <b-form-group label="Usuario">
                                <b-input-group>
                                    <template v-slot:append>
                                        <b-input-group-text><strong class="text-danger"><span class="icon-user text-muted icon-input"></span></strong></b-input-group-text>
                                    </template>

                                    <b-form-input
                                        placeholder="Ingresa tu usuario"
                                        v-model="$v.login.usuario.$model"
                                        :state="$v.login.usuario.$dirty ? !$v.login.usuario.$error : null"
                                    ></b-form-input>
                                </b-input-group>

                                <b-form-invalid-feedback :state="$v.login.usuario.$dirty ? !$v.login.usuario.$error : null">
                                    <errores :errores="errores_login_usuario"></errores>
                                </b-form-invalid-feedback>
                            </b-form-group>

                            <b-form-group label="Contraseña">
                                <b-input-group>
                                    <template v-slot:append>
                                        <b-input-group-text><strong class="text-danger"><span class="icon-lock text-muted icon-input"></span></strong></b-input-group-text>
                                    </template>

                                    <b-form-input
                                        type="password"
                                        placeholder="Ingresa tu contraseña"
                                        v-model="$v.login.password.$model"
                                        :state="$v.login.password.$dirty ? !$v.login.password.$error : null"
                                    ></b-form-input>
                                </b-input-group>

                                <b-form-invalid-feedback :state="$v.login.password.$dirty ? !$v.login.password.$error : null">
                                    <errores :errores="errores_login_password"></errores>
                                </b-form-invalid-feedback>
                            </b-form-group>


                            <hr>
                            <button type="button" @click="ingresar" class="btn btn-block btn-success btn-rounded box-shadow">Ingresar</button>
                        </form>
                    </div>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
    import { required, minLength } from 'vuelidate/lib/validators'
    import { mapMutations } from 'vuex'

    export default {
        data(){
            return {
                login: {
                    usuario: '',
                    password: ''
                }
            }
        },
        computed: {
            errores_login_usuario () {
                const errores = []
                if (!this.$v.login.usuario.$dirty) return errores
                !this.$v.login.usuario.required && errores.push('Ingresa tu usuario.')
                !this.$v.login.usuario.email && errores.push('Ingresa un usuario válido.')
                !this.$v.login.usuario.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_login_password () {
                const errores = []
                if (!this.$v.login.password.$dirty) return errores
                !this.$v.login.password.required && errores.push('Ingresa tu password.')
                !this.$v.login.password.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
        },
        validations:{
            login: {
                usuario: {
                    required,
                    minLength: minLength(4)
                },
                password: {
                    required,
                    minLength: minLength(4)
                }
            }
        },
        methods: {
            ...mapMutations(['msg_success', 'msg_error', 'obtener_token']),
            ...mapMutations('usuario', ['actualizar']),
            ingresar(){
                if(this.$v.login.$invalid){
                    this.$v.login.$touch()
                    this.$store.commit('msg_error', 'Recuerda llenar correctamente el formulario.')
                    return
                }

                let me = this

                axios.post('/api/auth/login',{
                    'usuario': me.login.usuario,
                    'password': me.login.password
                }).then(function (response) {
                    me.$store.commit('obtener_token', response.data.access_token)
                    me.actualizar(response.data.usuario)
                    me.$router.push('home')
                }).catch(function (error) {
                    if (error.response.status == 401 || error.response.status == 422){
                        me.login.password = ''
                        me.msg_error('Usuario o contraseña incorrectos. Por favor intenta nuevamente.')
                    }
                })
            }
        }
    }
</script>

<style>
    @import '../../../public/css/app.css';

    html,body{
        height: 100%;
    }
</style>
