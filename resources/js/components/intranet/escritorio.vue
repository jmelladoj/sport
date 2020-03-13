<template>
    <span>
        <titulo-pagina titulo="Escritorio"></titulo-pagina>

        <section class="main-content">
            <b-row>
                <b-col>
                    <b-card>
                        <b-row align-v="center">
                            <b-col xs="12" sm="12" md="3"><b><span v-text="rango_semanas"></span></b></b-col>
                            <b-col xs="12" sm="12" md="3"><b-button size="sm" variant="success" v-b-tooltip.hover title="Semana anterior" block @click="listar_dias(2)">Semana anterior</b-button></b-col>
                            <b-col xs="12" sm="12" md="3"><b-button size="sm" variant="success" v-b-tooltip.hover title="Semana siguiente" block @click="listar_dias(1)">Semana siguiente</b-button></b-col>
                            <b-col xs="12" sm="12" md="3">
                                <b-form-group label="Profesional :" label-cols-md="4" class="mb-0 pb-0">
                                    <b-form-select v-model="profesional">
                                        <option :value="null">Ver todos</option>
                                        <option v-for="p in profesionales" :key="p.id" :value="p.id" v-text="p.nombre"></option>
                                    </b-form-select>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-card>
                </b-col>
            </b-row>
            <b-row>
                <b-col>
                    <b-card>
                        <b-row>
                            <b-table-simple class="my-3" show-empty small striped outlined stacked="sm">
                                <b-thead>
                                    <b-tr class="text-center">
                                        <b-th>Horarios</b-th>
                                        <b-th>Lunes <br> {{ dias.lunes }} </b-th>
                                        <b-th>Martes <br> {{ dias.martes }} </b-th>
                                        <b-th>Miércoles <br> {{ dias.miercoles }} </b-th>
                                        <b-th>Jueves <br> {{ dias.jueves }} </b-th>
                                        <b-th>Viernes <br> {{ dias.viernes }} </b-th>
                                        <b-th>Sábado <br> {{ dias.sabado }} </b-th>
                                    </b-tr>
                                </b-thead>
                                <b-tbody>
                                    <b-tr class="text-center" v-for="h in horas_semana" :key="h.id">
                                        <b-td class="font-weight-bold" v-text="h.hora"></b-td>
                                       <b-td>
                                            <b-alert v-show="filtrar_reservas(h.id, dias.lunes).length > 0" class="mb-0" :class="profesional == null || profesional == r.profesional ? '' : 'd-none'" :variant="r.clase_div" v-for="r in filtrar_reservas(h.id, dias.lunes)" :key="r.id" :id="r.id" show>
                                                <span>{{ r.nombre_cliente }}</span><br>
                                                <span>{{ r.nombre_servicio }}</span><br>
                                                <span>{{ r.nombre_profesional }}</span><br>

                                                <b-button size="xs" variant="light" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(r.id)">
                                                    <i class="fa fa-check"></i>
                                                </b-button>

                                                <b-button size="xs" variant="light" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(r.id, 3)">
                                                    <i class="fa fa fa-remove"></i>
                                                </b-button>
                                            </b-alert>
                                            <b-alert class="mb-0" v-show="filtrar_reservas(h.id, dias.lunes).length == 0" variant="info" show>
                                                Sin reservas
                                            </b-alert>
                                        </b-td>
                                        <b-td>
                                            <b-alert v-show="filtrar_reservas(h.id, dias.martes).length > 0" class="mb-0" :class="profesional == null || profesional == r.profesional ? '' : 'd-none'" :variant="r.clase_div" v-for="r in filtrar_reservas(h.id, dias.martes)" :key="r.id" :id="r.id" show>
                                                <span>{{ r.nombre_cliente }}</span><br>
                                                <span>{{ r.nombre_servicio }}</span><br>
                                                <span>{{ r.nombre_profesional }}</span><br>

                                                <b-button size="xs" variant="light" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(r.id)">
                                                    <i class="fa fa-check"></i>
                                                </b-button>

                                                <b-button size="xs" variant="light" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(r.id, 3)">
                                                    <i class="fa fa fa-remove"></i>
                                                </b-button>
                                            </b-alert>
                                            <b-alert class="mb-0" v-show="filtrar_reservas(h.id, dias.martes).length == 0" variant="info" show>
                                                Sin reservas
                                            </b-alert>
                                        </b-td>
                                        <b-td>
                                            <b-alert v-show="filtrar_reservas(h.id, dias.miercoles).length > 0" class="mb-0" :class="profesional == null || profesional == r.profesional ? '' : 'd-none'" :variant="r.clase_div" v-for="r in filtrar_reservas(h.id, dias.miercoles)" :key="r.id" :id="r.id" show>
                                                <span>{{ r.nombre_cliente }}</span><br>
                                                <span>{{ r.nombre_servicio }}</span><br>
                                                <span>{{ r.nombre_profesional }}</span><br>

                                                <b-button size="xs" variant="light" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(r.id)">
                                                    <i class="fa fa-check"></i>
                                                </b-button>

                                                <b-button size="xs" variant="light" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(r.id, 3)">
                                                    <i class="fa fa fa-remove"></i>
                                                </b-button>
                                            </b-alert>
                                            <b-alert class="mb-0" v-show="filtrar_reservas(h.id, dias.miercoles).length == 0" variant="info" show>
                                                Sin reservas
                                            </b-alert>
                                        </b-td>
                                        <b-td>
                                            <b-alert v-show="filtrar_reservas(h.id, dias.jueves).length > 0" class="mb-0" :class="profesional == null || profesional == r.profesional ? '' : 'd-none'" :variant="r.clase_div" v-for="r in filtrar_reservas(h.id, dias.jueves)" :key="r.id" :id="r.id" show>
                                                <span>{{ r.nombre_cliente }}</span><br>
                                                <span>{{ r.nombre_servicio }}</span><br>
                                                <span>{{ r.nombre_profesional }}</span><br>

                                                <b-button size="xs" variant="light" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(r.id)">
                                                    <i class="fa fa-check"></i>
                                                </b-button>

                                                <b-button size="xs" variant="light" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(r.id, 3)">
                                                    <i class="fa fa fa-remove"></i>
                                                </b-button>
                                            </b-alert>
                                            <b-alert class="mb-0" v-show="filtrar_reservas(h.id, dias.jueves).length == 0" variant="info" show>
                                                Sin reservas
                                            </b-alert>
                                        </b-td>
                                        <b-td>
                                            <b-alert v-show="filtrar_reservas(h.id, dias.viernes).length > 0" class="mb-0" :class="profesional == null || profesional == r.profesional ? '' : 'd-none'" :variant="r.clase_div" v-for="r in filtrar_reservas(h.id, dias.viernes)" :key="r.id" :id="r.id" show>
                                                <span>{{ r.nombre_cliente }}</span><br>
                                                <span>{{ r.nombre_servicio }}</span><br>
                                                <span>{{ r.nombre_profesional }}</span><br>

                                                <b-button size="xs" variant="light" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(r.id)">
                                                    <i class="fa fa-check"></i>
                                                </b-button>

                                                <b-button size="xs" variant="light" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(r.id, 3)">
                                                    <i class="fa fa fa-remove"></i>
                                                </b-button>
                                            </b-alert>
                                            <b-alert class="mb-0" v-show="filtrar_reservas(h.id, dias.viernes).length == 0" variant="info" show>
                                                Sin reservas
                                            </b-alert>
                                        </b-td>
                                        <b-td>
                                            <b-alert v-show="filtrar_reservas(h.id, dias.sabado).length > 0" class="mb-0" :class="profesional == null || profesional == r.profesional ? '' : 'd-none'" :variant="r.clase_div" v-for="r in filtrar_reservas(h.id, dias.sabado)" :key="r.id" :id="r.id" show>
                                                <span>{{ r.nombre_cliente }}</span><br>
                                                <span>{{ r.nombre_servicio }}</span><br>
                                                <span>{{ r.nombre_profesional }}</span><br>

                                                <b-button v-if="r.estado != 3" size="xs" variant="light" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(r.id)">
                                                    <i class="fa fa-check"></i>
                                                </b-button>

                                                <b-button v-if="r.estado != 3" size="xs" variant="light" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(r.id, 3)">
                                                    <i class="fa fa fa-remove"></i>
                                                </b-button>
                                            </b-alert>
                                            <b-alert class="mb-0" v-show="filtrar_reservas(h.id, dias.sabado).length == 0" variant="info" show>
                                                Sin reservas
                                            </b-alert>
                                        </b-td>
                                    </b-tr>

                                </b-tbody>
                            </b-table-simple>
                        </b-row>
                    </b-card>
                </b-col>
            </b-row>

            <b-modal ref="modal_reserva" title="Agendar reserva" no-close-on-backdrop scrollable static size="xl">
                <b-card border-variant="info" header="Datos de reserva" header-bg-variant="info" header-text-variant="white">
                    <b-card-text>
                        <b-row>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Fecha">
                                    <b-form-input
                                        type="date"
                                        v-model="$v.reserva.reserva.fecha.$model"
                                        :state="$v.reserva.reserva.fecha.$dirty ? !$v.reserva.reserva.fecha.$error : null"
                                        aria-describedby="reserva-fecha"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="reserva-fecha">
                                        Campo de alfanúmerico, mínimo de 3 caracteres y no debe de estar en la base de datos.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Servicio">
                                    <b-form-select
                                        v-model="$v.reserva.reserva.servicio.$model"
                                        :state="$v.reserva.reserva.servicio.$dirty ? !$v.reserva.reserva.servicio.$error : null"
                                        aria-describedby="reserva-reserva-servicio">
                                        <option :value="null">Selecciona una opción</option>
                                        <option v-for="s in servicios" :key="s.id" :value="s.id" v-text="s.nombre"></option>
                                    </b-form-select>


                                    <b-form-invalid-feedback id="reserva-reserva-servicio">
                                        Debes de seleccionar una opción.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Profesional">
                                    <b-form-select
                                        v-model="$v.reserva.reserva.profesional.$model"
                                        :state="$v.reserva.reserva.profesional.$dirty ? !$v.reserva.reserva.profesional.$error : null"
                                        aria-describedby="reserva-reserva-profesional"
                                        @change="listar_horas_profesional">
                                        <option :value="null">Selecciona una opción</option>
                                        <option v-for="p in profesionales" :key="p.id" :value="p.id" v-text="p.nombre"></option>
                                    </b-form-select>


                                    <b-form-invalid-feedback id="reserva-reserva-profesional">
                                        Debes de seleccionar una opción.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Horario">
                                    <b-form-select
                                        v-model="$v.reserva.reserva.hora.$model"
                                        :state="$v.reserva.reserva.hora.$dirty ? !$v.reserva.reserva.hora.$error : null"
                                        aria-describedby="reserva-reserva-hora">
                                        <option :value="null">Selecciona una opción</option>
                                        <option v-if="horas_profesional.length == 0">Sin horas</option>
                                        <option v-for="h in horas_profesional" :key="h.id" :value="h.id" v-text="h.hora"></option>
                                    </b-form-select>


                                    <b-form-invalid-feedback id="reserva-reserva-hora">
                                        Debes de seleccionar una opción.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-card-text>
                </b-card>

                <b-card border-variant="info" header="Datos cliente" header-bg-variant="info" header-text-variant="white">
                    <b-card-text>
                        <b-row>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Run">
                                    <vue-bootstrap-typeahead
                                        ref="typeahead_cliente"
                                        :data="clientes"
                                        v-model="$v.reserva.cliente.run.$model"
                                        :serializer="c => c.run"
                                        placeholder="Escribe para filtrar ..."
                                        @hit="cliente_seleccionado($event)"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Nombre completo">
                                    <b-form-input
                                        v-model="$v.reserva.cliente.nombre.$model"
                                        :state="$v.reserva.cliente.nombre.$dirty ? !$v.reserva.cliente.nombre.$error : null"
                                        aria-describedby="cliente-nombre"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="cliente-nombre">
                                        Campo de númerico, mínimo de 3 caracteres.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Dirección">
                                    <b-form-input
                                        v-model="$v.reserva.cliente.direccion.$model"
                                        :state="$v.reserva.cliente.direccion.$dirty ? !$v.reserva.cliente.direccion.$error : null"
                                        aria-describedby="cliente-direccion"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="cliente-direccion">
                                        Campo de alfanúmerico, mínimo de 3 caracteres.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Correo">
                                    <b-form-input
                                        v-model="$v.reserva.cliente.correo.$model"
                                        :state="$v.reserva.cliente.correo.$dirty ? !$v.reserva.cliente.correo.$error : null"
                                        aria-describedby="cliente-correo"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="cliente-correo">
                                        Campo de alfanúmerico, mínimo de 3 caracteres y no debe de estar en la base de datos.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Teléfono">
                                    <b-form-input
                                        v-model="$v.reserva.cliente.telefono.$model"
                                        :state="$v.reserva.cliente.telefono.$dirty ? !$v.reserva.cliente.telefono.$error : null"
                                        aria-describedby="cliente-telefono"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="cliente-telefono">
                                        Campo de númerico, debes ingresar un número de teléfono ej: 987654321.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Fecha de nacimiento">
                                    <b-form-input
                                        type="date"
                                        v-model="$v.reserva.cliente.fecha_nacimiento.$model"
                                        :state="$v.reserva.cliente.fecha_nacimiento.$dirty ? !$v.reserva.cliente.fecha_nacimiento.$error : null"
                                        aria-describedby="cliente-fecha-nacimiento"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="cliente-fecha-nacimiento">
                                        Debes de ingresar una fecha válida.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="12">
                                <b-form-group label="Observaciones">
                                    <b-form-textarea
                                        v-model="$v.reserva.cliente.observacion.$model"
                                        :state="$v.reserva.cliente.observacion.$dirty ? !$v.reserva.cliente.observacion.$error : null"
                                        aria-describedby="cliente-observacion"
                                        rows="3"
                                        max-rows="6"
                                    ></b-form-textarea>

                                    <b-form-invalid-feedback id="cliente-observacion">
                                        Campo de alfanúmerico, mínimo de 3 caracteres.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-card-text>
                </b-card>

                <template slot="modal-footer">
                    <b-button type="button" v-b-tooltip.hover title="Añadir registro a la base de datos" :disabled="$v.reserva.$invalid" size="md" variant="success" @click="agendar_reserva"> Agendar </b-button>
                    <b-button type="button" size="md" variant="danger" @click="cerrar_modal_reserva"> Cerrar </b-button>
                </template>
            </b-modal>

            <div class="fixed-bottom mb-2 mr-2">
                <b-button v-b-tooltip.hover title="Agregar registro" pill variant="success" size="sm" class="pull-right heigth-25 px-2 ml-1 mr-1" @click="abrir_modal_reserva"><i class="fa fa-plus"></i></b-button>
            </div>
        </section>
    </span>

</template>

<script>
    import { required, between, email, minLength, maxLength, numeric, minValue } from 'vuelidate/lib/validators'
    import { mapMutations, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                reservas: [],
                horas_profesional: [],
                horas_semana: [],
                clientes: [],
                servicios: [],
                dias: [],
                profesionales: [],
                dias: {
                    lunes: 'prueba'
                },
                fields: [],
                profesional: null,
                fecha_inicio: 'sf',
                fecha_termino: '',
                reserva: {
                    cliente: {
                        id: 0,
                        run: '',
                        nombre: '',
                        direccion: '',
                        correo: '',
                        telefono: null,
                        fecha_nacimiento: '',
                        observacion: ''
                    },
                    reserva: {
                        servicio: null,
                        profesional: null,
                        hora: null,
                        fecha: ''
                    }
                },
                dias: {
                    lunes: '',
                    martes: '',
                    miercoles: '',
                    jueves: '',
                    viernes: '',
                    sabado: ''
                }
            }
        },
        validations:{
            reserva: {
                cliente: {
                    run: {
                        required
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
                        required
                    },
                    observacion: {
                        minLength: minLength(3),
                    }
                },
                reserva: {
                    servicio: {
                        required
                    },
                    profesional: {
                        required
                    },
                    hora: {
                        required
                    },
                    fecha: {
                        required
                    }
                }
            }
        },
        computed: {
            rango_semanas(){
                return this.fecha_inicio + " AL " + this.fecha_termino
            }
        },
        methods: {
            ...mapMutations(['msg_success', 'msg_error']),
            filtrar_reservas(hora, fecha){
                return this.reservas.filter(function(r) {
                    return r.hora_clinicas_id == hora && r.fecha_servicio == fecha;
                })

            },
            listar_clientes(){
                let me = this

                axios.get('/clientes/usuario/1').then(function (response) {
                    me.clientes = response.data.clientes
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cliente_seleccionado(e){
                this.reserva.cliente.id = e.id
                this.reserva.cliente.run = e.run
                this.reserva.cliente.nombre = e.nombre
                this.reserva.cliente.direccion = e.direccion
                this.reserva.cliente.correo = e.correo
                this.reserva.cliente.telefono = e.telefono
                this.reserva.cliente.fecha_nacimiento = e.fecha_nacimiento
                this.reserva.cliente.observacion = e.observacion
            },
            listar_horas_profesional(){
                let me = this

                if(this.reserva.reserva.profesional != null){
                    me.horas_profesional = []

                    axios.get('/horas/profesional/' + this.reserva.reserva.profesional + '/' + this.reserva.reserva.fecha).then(function (response) {
                        me.horas_profesional = response.data.horas
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            listar_reservas(){
               let me = this

                axios.get('/reservas/' + this.fecha_inicio + '/' + this.fecha_termino).then(function (response) {
                    me.reservas = response.data.reservas
                    me.horas_semana = response.data.horas
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listar_horas(){
                let me = this

                axios.get('/horas/usuario/1').then(function (response) {
                    me.horas = response.data.horas
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listar_dias(accion = 0){
                let me = this
                axios.get('/horarios/dias/' + accion + '/' + this.fecha_inicio).then(function (response) {
                    me.fecha_inicio = response.data.fecha
                    me.fecha_termino = response.data.sabado

                    me.dias.lunes = response.data.lunes
                    me.dias.martes = response.data.martes
                    me.dias.miercoles = response.data.miercoles
                    me.dias.jueves = response.data.jueves
                    me.dias.viernes = response.data.viernes
                    me.dias.sabado = response.data.sabado

                    me.listar_reservas()

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listar_servicios(){
                let me = this

                axios.get('/servicios/usuario/1').then(function (response) {
                    me.servicios = response.data.servicios

                    me.fields.push()
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listar_profesionales(){
                let me = this

                axios.get('/profesionales/usuario/1').then(function (response) {
                    me.profesionales = response.data.profesionales
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listar_registros(){
                this.listar_horas()
                this.listar_servicios()
                this.listar_profesionales()
                this.listar_clientes()
            },
            abrir_modal_reserva(){
                this.limpiar_datos_reserva()
                this.$refs['modal_reserva'].show()
            },
            cerrar_modal_reserva(){
                this.$refs['modal_reserva'].hide()
            },
            limpiar_datos_reserva(){
                this.reserva.cliente.id = 0
                this.reserva.cliente.run = ''
                this.reserva.cliente.nombre = ''
                this.reserva.cliente.direccion = ''
                this.reserva.cliente.correo = ''
                this.reserva.cliente.telefono = null
                this.reserva.cliente.fecha_nacimiento = ''
                this.reserva.cliente.observacion = ''

                this.reserva.reserva.servicio = null
                this.reserva.reserva.profesional = null
                this.reserva.reserva.fecha = ''

                this.$refs.typeahead_cliente.inputValue = ''

                this.$v.$reset()
            },
            agendar_reserva(){
                if(this.$v.reserva.cliente.$invalid){
                    this.$v.cliente.$touch()
                    return
                }

                let me = this

                axios.post('/reserva/agendar',{
                    'cliente': me.reserva.cliente,
                    'servicio_id': me.reserva.reserva.servicio,
                    'profesional_id': me.reserva.reserva.profesional,
                    'fecha_servicio': me.reserva.reserva.fecha,
                    'hora_id': me.reserva.reserva.hora
                }).then(function (response) {
                    me.listar_clientes()
                    me.$store.commit('msg_success', 'Reseva agendada exitosamente.')

                    me.cerrar_modal_reserva()
                }).catch(function (error) {
                    me.$store.commit('msg_error', 'Ha ocurrido un error, por favor intentalo nuevamente.')
                    console.log(error)
                })
            },
            estado_reserva(id, estado = 2){
                swal.fire({
                title: '¿Alguna observación?',
                input: 'textarea',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                confirmButtonText: estado == 2 ? 'Confirmar' : 'Anular',
                showLoaderOnConfirm: true,
                    preConfirm: (observacion) => {
                        let me = this
                        axios.post('/reserva/actualizar/estado',{
                            'id': id,
                            'estado': estado,
                            'observacion': observacion
                        }).then(function (response) {
                            me.listar_reservas();
                            me.$store.commit('msg_success', 'Reserva actualizada.')
                        }).catch(function (error) {
                            me.$store.commit('msg_error', 'Ha ocurrido un error, por favor intentalo nuevamente.')
                            console.log(error);
                        })
                    }
                })
            }
        },
        beforeMount() {
            this.listar_dias()
            this.listar_registros()

            setTimeout(() => {
                this.listar_reservas()
            }, (2000))
        }
    }
</script>

