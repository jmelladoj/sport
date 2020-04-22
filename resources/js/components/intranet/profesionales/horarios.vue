<template>
    <div>
        <titulo-pagina titulo="Horarios del profesional" :accion="true">
            <template slot="btn_accion">
                <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="actualizar_horario"><i :class="'fa fa-pencil'"></i> Actualizar registro</b-button>
            </template>
        </titulo-pagina>

        <section class="main-content">
            <b-card>
                <b-table-simple class="my-3" show-empty small striped outlined stacked="sm">
                    <b-thead>
                        <b-tr class="text-center">
                            <b-th>Horarios</b-th>
                            <b-th>Lunes</b-th>
                            <b-th>Martes</b-th>
                            <b-th>Miércoles</b-th>
                            <b-th>Jueves</b-th>
                            <b-th>Viernes</b-th>
                            <b-th>Sábado</b-th>
                        </b-tr>
                    </b-thead>
                    <b-tbody>
                        <b-tr class="text-center" v-for="h in horarios" :key="h.id">
                            <b-td>{{ h.hora }}</b-td>
                            <b-td>
                                <b-form-checkbox size="lg" v-model="lunes" :value="h.id"></b-form-checkbox>
                            </b-td>
                            <b-td>
                                <b-form-checkbox size="lg" v-model="martes" :value="h.id"></b-form-checkbox>
                            </b-td>
                            <b-td>
                                <b-form-checkbox size="lg" v-model="miercoles" :value="h.id"></b-form-checkbox>
                            </b-td>
                            <b-td>
                                <b-form-checkbox size="lg" v-model="jueves" :value="h.id"></b-form-checkbox>
                            </b-td>
                            <b-td>
                                <b-form-checkbox size="lg" v-model="viernes" :value="h.id"></b-form-checkbox>
                            </b-td>
                            <b-td>
                                <b-form-checkbox size="lg" v-model="sabado" :value="h.id"></b-form-checkbox>
                            </b-td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>
            </b-card>

            <b-row class="mb-5">
                <b-col lg="12" class="text-right">
                    <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="actualizar_horario"><i :class="'fa fa-pencil'"></i> Actualizar registro</b-button>
                    <b-button variant="danger" class="box-shadow btn-icon btn-rounded" @click="$router.go(-1)"><i class="fa fa-long-arrow-left"></i> Volver atrás</b-button>
                </b-col>
            </b-row>
        </section>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                horarios: [],
                lunes: [],
                martes: [],
                miercoles: [],
                jueves: [],
                viernes: [],
                sabado: []
            }
        },
        methods: {
            listar_horarios(){
                let me = this

                axios.get('/api/horarios/clinica').then(function (response) {
                    me.horarios = response.data
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            listar_horarios_profesional(){
                if(!this.$route.params.id){
                    return false
                }

                let me = this

                axios.get('/api/horarios/profesional/' + this.$route.params.id).then(function (response) {
                    var lunes     = response.data.filter(h => h.dia == 1)
                    var martes    = response.data.filter(h => h.dia == 2)
                    var miercoles = response.data.filter(h => h.dia == 3)
                    var jueves    = response.data.filter(h => h.dia == 4)
                    var viernes   = response.data.filter(h => h.dia == 5)
                    var sabado    = response.data.filter(h => h.dia == 6)

                    me.agregar_dias_tabla(lunes, me.lunes)
                    me.agregar_dias_tabla(martes, me.martes)
                    me.agregar_dias_tabla(miercoles, me.miercoles)
                    me.agregar_dias_tabla(jueves, me.jueves)
                    me.agregar_dias_tabla(viernes, me.viernes)
                    me.agregar_dias_tabla(sabado, me.sabado)

                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            agregar_dias_tabla(data = [], dia = []){
                data.forEach( function(h) {
                    dia.push(h.hora_clinicas_id)
                });

                return dia
            },
            actualizar_horario(){
                let me = this

                axios({
                    method: 'PUT',
                    url: '/api/horarios/profesional/' + this.$route.params.id,
                    data: {
                        'profesional_id' : me.$route.params.id,
                        'lunes'          : me.lunes,
                        'martes'         : me.martes,
                        'miercoles'      : me.miercoles,
                        'jueves'         : me.jueves,
                        'viernes'        : me.viernes,
                        'sabado'         : me.sabado
                    }
                }).then(function (response) {
                    me.$router.go(-1)
                    me.$store.commit('msg_success', 2)
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            }
        },
        mounted() {
            this.listar_horarios()
            this.listar_horarios_profesional()
        }
    }
</script>
