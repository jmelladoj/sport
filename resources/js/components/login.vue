<template>
    <div class="misc-content">
        <b-container fluid>
            <b-row class="justify-content-center pb-5">
                <b-col xs="10" md="4">
                    <div class="misc-header text-center">
                        <img alt="" src="assets/img/icon.png" class="logo-icon margin-r-10">
                        <img alt="" src="assets/img/logo-dark.png" class="toggle-none hidden-xs">
                    </div>
                    <div class="misc-box">
                        <form role="form">
                            <b-form-group label="Usuario">
                                <b-input-group>
                                    <template v-slot:append>
                                        <b-input-group-text><strong class="text-danger"><span class="icon-user text-muted icon-input"></span></strong></b-input-group-text>
                                    </template>

                                    <b-form-input
                                        placeholder="Ingresa tu usuario"
                                        v-model="$v.login.usuario.$model"
                                        :state="$v.login.usuario.$dirty ? !$v.login.usuario.$error : null"
                                        aria-describedby="usuario-input"
                                    ></b-form-input>
                                </b-input-group>

                                <b-form-invalid-feedback id="usuario-input">
                                    Ingresa tu usuario, mínimo de 4 caracteres.
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
                                        aria-describedby="password-input"
                                    ></b-form-input>
                                </b-input-group>

                                <b-form-invalid-feedback id="password-input">
                                    Ingresa tu contraseña y un mínimo de 4 caracteres.
                                </b-form-invalid-feedback>
                            </b-form-group>

                            <hr>
                            <button type="button" :disabled="$v.login.$invalid" @click="ingresar" class="btn btn-block btn-success btn-rounded box-shadow">Ingresar</button>
                        </form>
                    </div>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
    import { required, minLength, between, email } from 'vuelidate/lib/validators'
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
            ...mapMutations(['msg_success', 'msg_error']),
            ingresar(){
                if(this.$v.login.$invalid){
                    this.$v.login.$touch()
                    return
                }

                let me = this

                axios.post('/login',{
                    'usuario': me.login.usuario,
                    'password': me.login.password
                }).then(function (response) {
                    window.location.href = "/home"
                }).catch(function (error) {
                    if (error.response.status == 422){
                        me.login.password = ''
                        me.$store.commit('msg_error', 'Las credenciales introducidas son incorrectas.')
                    }
                })
            }
        }
    }
</script>
