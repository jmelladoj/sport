<template>
    <div>
        <titulo-pagina :titulo="modifica ? 'Modificar reserva' : 'Agendar reserva'" :accion="true">
            <template slot="btn_accion">
                <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="agregar_modificar_reserva"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
            </template>
        </titulo-pagina>

        <section class="main-content">
            <b-card header-text-variant="white" header="Detalles de la reserva" header-bg-variant="primary">
                <b-form>
                    <b-row>
                        <b-col md="6">                    
                            <b-form-group label="Servicio">
                                <b-input-group prepend="123">
                                    <b-form-select
                                        v-model="$v.formulario.reserva.servicio_id.$model"
                                        :state="$v.formulario.reserva.servicio_id.$dirty ? !$v.formulario.reserva.servicio_id.$error : null"
                                        :options="opciones_servicios"
                                        @change="cargar_profesionales">
                                    </b-form-select>

                                    <template v-slot:append>
                                        <router-link class="btn btn-success" to="/servicios/administrar/"  v-b-tooltip.hover title="Agregar servicios"><i class="fa fa-plus"></i></router-link>
                                    </template>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.reserva.servicio_id.$dirty ? !$v.formulario.reserva.servicio_id.$error : null">
                                <errores :errores="errores_reserva_servicio_id"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col md="6">                    
                            <b-form-group label="Profesional">
                                <b-input-group prepend="123">
                                    <b-form-select
                                        v-model="$v.formulario.reserva.profesional_id.$model"
                                        :state="$v.formulario.reserva.profesional_id.$dirty ? !$v.formulario.reserva.profesional_id.$error : null"
                                        :options="opciones_profesionales"
                                        :disabled="opciones_profesionales.length ? false : true"
                                        @change="cargar_sesiones_servicio">
                                    </b-form-select>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.formulario.reserva.profesional_id.$dirty ? !$v.formulario.reserva.profesional_id.$error : null">
                                <errores :errores="errores_reserva_profesional_id"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                       <b-col lg="12" v-show="formulario.reserva.reservas.length">
                            <b-table-simple class="my-3" show-empty small striped outlined stacked="sm">
                                <b-thead>
                                    <b-tr class="text-center">
                                        <b-th>#</b-th>
                                        <b-th>Nombre servicio</b-th>
                                        <b-th>Fecha a agendar</b-th>
                                        <b-th>Hora a agendar</b-th>
                                    </b-tr>
                                </b-thead>
                                <b-tbody>
                                    <b-tr v-for="(r, index) in formulario.reserva.reservas" :key="index">
                                        <b-td>{{ index + 1 }}</b-td>
                                        <b-td>{{ r.nombre_servicio }}</b-td>
                                        <b-td>
                                            <b-input-group prepend="Abc" class="mt-0 mb-0">
                                                <b-form-input
                                                    type="date"
                                                    v-model="$v.formulario.reserva.reservas.$each[index].fecha_servicio.$model" 
                                                    :state="$v.formulario.reserva.reservas.$each[index].fecha_servicio.$dirty ? !$v.formulario.reserva.reservas.$each[index].fecha_servicio.$error : null"
                                                    @change="obtener_horarios(index)">
                                                    
                                                </b-form-input>
                                            </b-input-group>
                                        </b-td>
                                        <b-td>
                                            <b-input-group prepend="123" class="mt-0 mb-0">
                                                <b-form-select
                                                    :id="'servicio_' + index"
                                                    v-model="$v.formulario.reserva.reservas.$each[index].hora_clinicas_id.$model"
                                                    :state="$v.formulario.reserva.reservas.$each[index].hora_clinicas_id.$dirty ? !$v.formulario.reserva.reservas.$each[index].hora_clinicas_id.$error : null"
                                                    disabled
                                                    class="mt-0 mb-0">
                                                </b-form-select>
                                            </b-input-group>                                     
                                        </b-td>
                                    </b-tr>
                                </b-tbody>
                            </b-table-simple>
                        </b-col>
                    </b-row>
                </b-form>
            </b-card>

            <b-card header-text-variant="white" header="Información sobre el cliente" header-bg-variant="primary">
                <b-form>
                    <b-row>
                        <b-col xs="12" sm="12" md="6">         
                            <b-form-group label="Run">
                                <vue-bootstrap-typeahead 
                                    v-rut:live
                                    ref="typeahead" 
                                    :data="clientes" 
                                    :serializer="c => c.run" 
                                    placeholder="Escribe para buscar"
                                    :maxMatches="10"
                                    @hit="cliente_seleccionado"
                                    v-model="$v.formulario.cliente.run.$model" 
                                    :state="$v.formulario.cliente.run.$dirty ? !$v.formulario.cliente.run.$error : null"/>
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
                    <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="agregar_modificar_reserva"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
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
                clientes: [],
                profesionales: [],
                opciones_profesionales: [],
                servicios: [],
                opciones_servicios: [],
                formulario: {
                    reserva: {
                        id: null,
                        servicio_id: null,
                        profesional_id: null,
                        reservas: []
                    },
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
                }
            }
        },
        validations:{
            formulario: {
                reserva: {
                    servicio_id: {
                        required,
                    },
                    profesional_id: {
                        required,
                    },
                    reservas:{
                        $each: {
                            hora_clinicas_id: {
                                
                            },
                            fecha_servicio: {

                            }
                        }
                    }
                },
                cliente: {
                    run: {
                        required,
                        minLength: minLength(7)
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
                }
            }
        },
        computed: {
            errores_cliente_run () {
                const errores = []
                if (!this.$v.formulario.cliente.run.$dirty) return errores
                !this.$v.formulario.cliente.run.required && errores.push('El campo es requerido.')
                !this.$v.formulario.cliente.run.minLength && errores.push('Ingresa al menos 7 caracteres.')
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
            errores_reserva_servicio_id () {
                const errores = []
                if (!this.$v.formulario.reserva.servicio_id.$dirty) return errores
                !this.$v.formulario.reserva.servicio_id.required && errores.push('El campo es requerido.')
                !this.$v.formulario.reserva.servicio_id.numeric && errores.push('Solo se aceptan números.')
                !this.$v.formulario.reserva.servicio_id.minValue && errores.push('El valor mínimo aceptado es 0.')
                return errores
            },
            errores_reserva_profesional_id () {
                const errores = []
                if (!this.$v.formulario.reserva.profesional_id.$dirty) return errores
                !this.$v.formulario.reserva.profesional_id.required && errores.push('El campo es requerido.')
                return errores
            }
        },
        methods: {
            listar_clientes(run){
                let me = this

                axios.get('/api/clientes').then(function (response) {
                    me.clientes = response.data
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            cliente_seleccionado(e){
                let me = this

                var cliente = me.clientes.find(function(c) {
                    return c.id == e.id;
                });

                if(cliente){
                    me.formulario.cliente.id = cliente.id
                    me.formulario.cliente.run = cliente.run
                    me.formulario.cliente.nombre = cliente.nombre
                    me.formulario.cliente.direccion = cliente.direccion
                    me.formulario.cliente.correo = cliente.correo
                    me.formulario.cliente.telefono = cliente.telefono
                    me.formulario.cliente.fecha_nacimiento = cliente.fecha_nacimiento
                    me.formulario.cliente.observacion = cliente.observacion
                }
            },
            listar_servicios(){
                let me = this

                axios.get('/api/servicios').then(function (response) {
                    me.servicios = response.data

                    var item = { value: null, text: 'Selecciona una opción ...'}

                    me.opciones_servicios.push(item)
    
                    me.servicios.forEach(function(s) {
                        var servicio = { value: s.id, text: s.nombre }
                        me.opciones_servicios.push(servicio)
                    })

                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            listar_profesionales(){
                let me = this

                axios.get('/api/profesionales').then(function (response) {
                    me.profesionales = response.data
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            cargar_profesionales(){
                let me = this

                let servicio = this.servicios.find(s => this.formulario.reserva.servicio_id == s.id)

                if(servicio){
                   var profesionales = this.profesionales.filter(p => p.especialidad_id == servicio.especialidad_id)
                
                    var item = { value: null, text: 'Selecciona una opción ...'}

                    me.opciones_profesionales.push(item)
    
                    me.profesionales.forEach(function(p) {
                        var profesional = { value: p.id, text: p.nombre }
                        me.opciones_profesionales.push(profesional)
                    })
                }
                
            },
            cargar_sesiones_servicio(){
                let me = this

                me.formulario.reserva.reservas = []

                let servicio = this.servicios.find(s => s.id == this.formulario.reserva.servicio_id)
                let profesional = this.profesionales.find(p => p.id == this.formulario.reserva.profesional_id)


                if(servicio && profesional){
                    for (let index = 0; index < servicio.cantidad_sesiones; index++) {
                        var horarios = []
                        var reserva = {
                            'nombre_cliente': me.formulario.cliente.nombre,
                            'nombre_servicio': servicio.nombre,
                            'nombre_profesional': profesional.nombre,
                            'fecha_servicio': null,
                            'estado': 1,
                            'cliente_id': null,
                            'servicio_id': servicio.id,
                            'profesional_id': profesional.id,
                            'hora_clinicas_id': null
                        }

                        me.formulario.reserva.reservas.push(reserva)
                    }
                }
            },
            obtener_horarios(index){
                let me = this

                $("#servicio_" + index).children().remove().end()
                $("#servicio_" + index).prop('disabled', true)

                axios.post('/api/disponibilidad/profesional',{
                    'fecha': me.formulario.reserva.reservas[index].fecha_servicio,
                    'profesional_id': me.formulario.reserva.profesional_id
                }).then(function (response) {
                    $("#servicio_" + index).append(new Option("Selecciona una opción ...", null))

                    response.data.forEach(function(h) {
                        $("#servicio_" + index).append(new Option(h.hora, h.hora_clinicas_id))
                    })

                    if(response.data.length > 0){
                        $("#servicio_" + index).prop('disabled', false)
                    } else {
                        $("#servicio_" + index).children().remove().end()
                        $("#servicio_" + index).append(new Option("Sin horarios de atención", null))
                    }
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })

            },
            agregar_modificar_reserva(){
                if(this.$v.formulario.$invalid){
                    this.$v.formulario.$touch()
                    this.$store.commit('msg_error', 'Recuerda llenar correctamente el formulario.')
                    return
                }

                let me = this

                var ruta = this.modifica ? '/api/reservas/' + this.formulario.reserva.id : '/api/reservas'
                var method = this.modifica ? 'PUT' : 'POST'

                axios({
                    method: method,
                    url: ruta,
                    data: {
                        'cliente' : me.formulario.cliente,
                        'reservas': me.formulario.reserva.reservas
                    }
                }).then(function (response) {
                    me.listar_clientes()
                    me.limpiar_datos()
                    me.$store.commit('msg_success', me.modifica ? 2 : 1)
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            limpiar_datos(){
                if(!this.modifica){
                    this.formulario.id = null
                    this.formulario.run = ''
                    this.formulario.nombre = ''
                    this.formulario.direccion = ''
                    this.formulario.correo = ''
                    this.formulario.telefono = null
                    this.formulario.fecha_nacimiento = null
                    this.formulario.observacion = ''

                    this.formulario.reserva.id = null
                    this.formulario.reserva.servicio_id = null
                    this.formulario.reserva.profesional_id = null
                } else {
                    this.$router.go(-1)
                }
            }
        },
        mounted() {
            this.listar_servicios()
            this.listar_profesionales()
            this.listar_clientes()
        }
    }
</script>
