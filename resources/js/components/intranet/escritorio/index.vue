<template>
    <div>
        <titulo-pagina titulo="Escritorio" :accion="false">
            <template slot="btn_accion" v-if="usuario && usuario.tipo_usuario == 1">
                <router-link class="btn btn-success box-shadow btn-icon btn-rounded" to="/reserva/"><i class="fa fa-plus"></i> Agendar consulta</router-link>
            </template>
        </titulo-pagina>

        <section class="main-content">
            <b-card>
                <b-row align-v="center">
                    <b-col xs="12" sm="12" md="3"><b><span v-text="rango_semanas"></span></b></b-col>
                    <b-col xs="12" sm="12" md="3"><b-button size="sm" variant="success" class="btn-rounded" v-b-tooltip.hover title="Semana anterior" block @click="listar_dias(2)">Semana anterior</b-button></b-col>
                    <b-col xs="12" sm="12" md="3"><b-button size="sm" variant="success" class="btn-rounded" v-b-tooltip.hover title="Semana siguiente" block @click="listar_dias(1)">Semana siguiente</b-button></b-col>
                    <b-col xs="12" sm="12" md="3">
                        <b-form-group label="Profesional :" label-cols-md="4" class="mb-0 pb-0" v-if="usuario" :disabled="usuario.tipo_usuario != 1">
                            <b-form-select v-model="profesional" :options="opciones_profesionales"></b-form-select>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-card>
            <b-card>
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
                                <b-alert v-show="filtrar_reservas(h.id, dias.lunes).length > 0" class="mb-1" :class="profesional == null || profesional == r.profesional_id ? '' : 'd-none'" :variant="r.clase_div" v-for="r in filtrar_reservas(h.id, dias.lunes)" :key="r.id" :id="r.id" show>
                                    <span>{{ r.nombre_cliente }}</span><br>
                                    <span>{{ r.nombre_servicio }}</span><br>
                                    <span>{{ r.nombre_profesional }}</span><br>

                                    <span v-if="usuario.tipo_usuario == 1">
                                        <b-button v-if="r.estado != 2" size="xs" class="btn-rounded" variant="light" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(r.id)">
                                            <i class="fa fa-check"></i>
                                        </b-button>

                                        <b-button v-if="r.estado != 3" size="xs" class="btn-rounded" variant="light" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(r.id, 3)">
                                            <i class="fa fa fa-remove"></i>
                                        </b-button>

                                        <router-link v-if="r.estado != 3" class="btn btn-light btn-rounded btn-xs" :to="{ name: 'reagendar', params: {id: r.id}}"  v-b-tooltip.hover title="Reagendar reserva"><i class="fa fa-calendar"></i></router-link>
                                        <router-link v-if="r.estado != 3 && r.reserva.pagada == 0" class="btn btn-light btn-rounded btn-xs" :to="{ name: 'pagar', params: {id: r.reserva.id}}"  v-b-tooltip.hover title="Pagar reserva"><i class="fa fa-usd"></i></router-link>
                                    </span>
                                </b-alert>
                                <b-alert class="mb-1" v-show="filtrar_reservas(h.id, dias.lunes).length == 0" variant="info" show>
                                    Sin reservas
                                </b-alert>
                            </b-td>
                            <b-td>
                                <b-alert v-show="filtrar_reservas(h.id, dias.martes).length > 0" class="mb-1" :class="profesional == null || profesional == r.profesional_id ? '' : 'd-none'" :variant="r.clase_div" v-for="r in filtrar_reservas(h.id, dias.martes)" :key="r.id" :id="r.id" show>
                                    <span>{{ r.nombre_cliente }}</span><br>
                                    <span>{{ r.nombre_servicio }}</span><br>
                                    <span>{{ r.nombre_profesional }}</span><br>
                                    <span v-if="usuario.tipo_usuario == 1">
                                        <b-button v-if="r.estado != 2" size="xs" class="btn-rounded" variant="light" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(r.id)">
                                            <i class="fa fa-check"></i>
                                        </b-button>

                                        <b-button v-if="r.estado != 3" size="xs" class="btn-rounded" variant="light" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(r.id, 3)">
                                            <i class="fa fa fa-remove"></i>
                                        </b-button>

                                        <router-link v-if="r.estado != 3"  class="btn btn-light btn-rounded btn-xs" :to="{ name: 'reagendar', params: {id: r.id}}"  v-b-tooltip.hover title="Reagendar reserva"><i class="fa fa-calendar"></i></router-link>
                                        <router-link v-if="r.estado != 3 && r.reserva.pagada == 0" class="btn btn-light btn-rounded btn-xs" :to="{ name: 'pagar', params: {id: r.reserva.id}}"  v-b-tooltip.hover title="Pagar reserva"><i class="fa fa-usd"></i></router-link>
                                    </span>
                                </b-alert>
                                <b-alert class="mb-1" v-if="filtrar_reservas(h.id, dias.martes).length == 0" variant="info" show>
                                    Sin reservas
                                </b-alert>
                            </b-td>
                            <b-td>
                                <b-alert v-show="filtrar_reservas(h.id, dias.miercoles).length > 0" class="mb-1" :class="profesional == null || profesional == r.profesional_id ? '' : 'd-none'" :variant="r.clase_div" v-for="r in filtrar_reservas(h.id, dias.miercoles)" :key="r.id" :id="r.id" show>
                                    <span>{{ r.nombre_cliente }}</span><br>
                                    <span>{{ r.nombre_servicio }}</span><br>
                                    <span>{{ r.nombre_profesional }}</span><br>

                                    <span v-if="usuario.tipo_usuario == 1">
                                        <b-button v-if="r.estado != 2" size="xs" class="btn-rounded" variant="light" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(r.id)">
                                            <i class="fa fa-check"></i>
                                        </b-button>

                                        <b-button v-if="r.estado != 3" size="xs" class="btn-rounded" variant="light" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(r.id, 3)">
                                            <i class="fa fa fa-remove"></i>
                                        </b-button>

                                        <router-link v-if="r.estado != 3"  class="btn btn-light btn-rounded btn-xs" :to="{ name: 'reagendar', params: {id: r.id}}"  v-b-tooltip.hover title="Reagendar reserva"><i class="fa fa-calendar"></i></router-link>
                                        <router-link v-if="r.estado != 3 && r.reserva.pagada == 0" class="btn btn-light btn-rounded btn-xs" :to="{ name: 'pagar', params: {id: r.reserva.id}}"  v-b-tooltip.hover title="Pagar reserva"><i class="fa fa-usd"></i></router-link>
                                    </span>
                                </b-alert>
                                <b-alert class="mb-1" v-show="filtrar_reservas(h.id, dias.miercoles).length == 0" variant="info" show>
                                    Sin reservas
                                </b-alert>
                            </b-td>
                            <b-td>
                                <b-alert v-show="filtrar_reservas(h.id, dias.jueves).length > 0" class="mb-1" :class="profesional == null || profesional == r.profesional_id ? '' : 'd-none'" :variant="r.clase_div" v-for="r in filtrar_reservas(h.id, dias.jueves)" :key="r.id" :id="r.id" show>
                                    <span>{{ r.nombre_cliente }}</span><br>
                                    <span>{{ r.nombre_servicio }}</span><br>
                                    <span>{{ r.nombre_profesional }}</span><br>

                                    <span v-if="usuario.tipo_usuario == 1">
                                        <b-button v-if="r.estado != 2" size="xs" class="btn-rounded" variant="light" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(r.id)">
                                            <i class="fa fa-check"></i>
                                        </b-button>

                                        <b-button v-if="r.estado != 3" size="xs" class="btn-rounded" variant="light" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(r.id, 3)">
                                            <i class="fa fa fa-remove"></i>
                                        </b-button>

                                        <router-link v-if="r.estado != 3"  class="btn btn-light btn-rounded btn-xs" :to="{ name: 'reagendar', params: {id: r.id}}"  v-b-tooltip.hover title="Reagendar reserva"><i class="fa fa-calendar"></i></router-link>
                                        <router-link v-if="r.estado != 3 && r.reserva.pagada == 0" class="btn btn-light btn-rounded btn-xs" :to="{ name: 'pagar', params: {id: r.reserva.id}}"  v-b-tooltip.hover title="Pagar reserva"><i class="fa fa-usd"></i></router-link>
                                    </span>
                                </b-alert>
                                <b-alert class="mb-1" v-show="filtrar_reservas(h.id, dias.jueves).length == 0" variant="info" show>
                                    Sin reservas
                                </b-alert>
                            </b-td>
                            <b-td>
                                <b-alert v-show="filtrar_reservas(h.id, dias.viernes).length > 0" class="mb-1" :class="profesional == null || profesional == r.profesional_id ? '' : 'd-none'" :variant="r.clase_div" v-for="r in filtrar_reservas(h.id, dias.viernes)" :key="r.id" :id="r.id" show>
                                    <span>{{ r.nombre_cliente }}</span><br>
                                    <span>{{ r.nombre_servicio }}</span><br>
                                    <span>{{ r.nombre_profesional }}</span><br>

                                    <span v-if="usuario.tipo_usuario == 1">
                                        <b-button v-if="r.estado != 2" size="xs" class="btn-rounded" variant="light" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(r.id)">
                                            <i class="fa fa-check"></i>
                                        </b-button>

                                        <b-button v-if="r.estado != 3" size="xs" class="btn-rounded" variant="light" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(r.id, 3)">
                                            <i class="fa fa fa-remove"></i>
                                        </b-button>

                                        <router-link v-if="r.estado != 3"  class="btn btn-light btn-rounded btn-xs" :to="{ name: 'reagendar', params: {id: r.id}}"  v-b-tooltip.hover title="Reagendar reserva"><i class="fa fa-calendar"></i></router-link>
                                        <router-link v-if="r.estado != 3 && r.reserva.pagada == 0" class="btn btn-light btn-rounded btn-xs" :to="{ name: 'pagar', params: {id: r.reserva.id}}"  v-b-tooltip.hover title="Pagar reserva"><i class="fa fa-usd"></i></router-link>
                                    </span>
                                </b-alert>
                                <b-alert class="mb-1" v-show="filtrar_reservas(h.id, dias.viernes).length == 0" variant="info" show>
                                    Sin reservas
                                </b-alert>
                            </b-td>
                            <b-td>
                                <b-alert v-show="filtrar_reservas(h.id, dias.sabado).length > 0" class="mb-1" :class="profesional == null || profesional == r.profesional_id ? '' : 'd-none'" :variant="r.clase_div" v-for="r in filtrar_reservas(h.id, dias.sabado)" :key="r.id" :id="r.id" show>
                                    <span>{{ r.nombre_cliente }}</span><br>
                                    <span>{{ r.nombre_servicio }}</span><br>
                                    <span>{{ r.nombre_profesional }}</span><br>

                                    <span v-if="usuario.tipo_usuario == 1">
                                        <b-button v-if="r.estado != 3" size="xs" class="btn-rounded" variant="light" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(r.id)">
                                            <i class="fa fa-check"></i>
                                        </b-button>

                                        <b-button v-if="r.estado != 3" size="xs" class="btn-rounded" variant="light" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(r.id, 3)">
                                            <i class="fa fa fa-remove"></i>
                                        </b-button>

                                        <router-link v-if="r.estado != 3"  class="btn btn-light btn-rounded btn-xs" :to="{ name: 'reagendar', params: {id: r.id}}"  v-b-tooltip.hover title="Reagendar reserva"><i class="fa fa-calendar"></i></router-link>
                                        <router-link v-if="r.estado != 3 && r.reserva.pagada == 0" class="btn btn-light btn-rounded btn-xs" :to="{ name: 'pagar', params: {id: r.reserva.id}}"  v-b-tooltip.hover title="Pagar reserva"><i class="fa fa-usd"></i></router-link>
                                    </span>
                                </b-alert>
                                <b-alert class="mb-1" v-show="filtrar_reservas(h.id, dias.sabado).length == 0" variant="info" show>
                                    Sin reservas
                                </b-alert>
                            </b-td>
                        </b-tr>
                        <b-tr class="text-center">
                            <b-td colspan="7"><h4 class="mb-3 mt-3"><b>Sin reservas en la semana.</b></h4></b-td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>
            </b-card>
        </section>
    </div>
</template>

<script>
    import { mapState } from 'vuex'

    export default {
        data() {
            return {
                profesional: null,
                opciones_profesionales: [],
                fecha_inicio: 'sf',
                fecha_termino: '',
                horas_semana: [],
                reservas: [],
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
        computed: {
            ...mapState('usuario', ['usuario']),
            rango_semanas(){
                return this.fecha_inicio + " AL " + this.fecha_termino
            }
        },
        methods: {
            filtrar_reservas(hora, fecha){
                let me = this
                return this.reservas.filter(function(r) {
                    if(me.usuario && me.usuario.tipo_usuario == 1){
                        return r.hora_clinicas_id == hora && r.fecha_servicio == fecha;
                    } else {
                        return r.hora_clinicas_id == hora && r.fecha_servicio == fecha && me.profesional != null && me.profesional == r.profesional_id;
                    }
                })
            },
            listar_profesionales(){
                let me = this

                axios.get('/api/profesionales').then(function (response) {
                    me.opciones_profesionales.push({ value: null, text: 'Todos'})

                    if(response.data.length == 0) return false

                    response.data.forEach(function(p) {
                        if(!p.deleted_at){
                            me.opciones_profesionales.push({ value: p.id, text: p.nombre })
                        }

                    })
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listar_dias(accion = 0){
                let me = this
                axios.get('/api/horario/dias/' + accion + '/' + this.fecha_inicio).then(function (response) {
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
            listar_reservas(){
               let me = this

                axios.get('/api/reservas/' + this.fecha_inicio + '/' + this.fecha_termino).then(function (response) {
                    me.reservas = response.data.reservas
                    me.horas_semana = response.data.horas
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            estado_reserva(id, accion = 2){
                let me = this

                swal.fire({
                    title: accion == 2 ? '¿Deseas confirmar la reserva?' : '¿Deseas anular la reserva?',
                    icon: accion == 2 ? 'success' : 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: accion == 2 ? 'Sí, ¡confirmar!' : 'Sí, anular',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        axios.put('/api/reservas/' + id,{
                            'estado': accion
                        }).then(function (response) {
                            me.listar_reservas()
                            me.$store.commit('msg_success', 1)
                        }).catch(function (error) {
                            me.$store.commit('msg_error')
                        })
                    }
                })
            },

        },
        mounted() {
            this.listar_dias()
            this.listar_profesionales()

            if(this.usuario && this.usuario.tipo_usuario != 1){
                this.profesional = this.usuario.id
            }
        }
    }
</script>
