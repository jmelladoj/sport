<template>
    <div>
        <b-row>
            <b-col lg="6" class="my-1">
                <b-form-group label="Búsqueda" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="filterInput" class="mb-0" >
                <b-input-group size="sm">
                    <b-form-input v-model="filter" type="search" id="filterInput" placeholder="Escribe para buscar"></b-form-input>
                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
                    </b-input-group-append>
                </b-input-group>
                </b-form-group>
            </b-col>

            <b-col lg="6" class="my-1">
                <b-form-group label="Ordenar" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="sortBySelect" class="mb-0">
                    <b-input-group size="sm">
                        <b-form-select v-model="sortBy" id="sortBySelect" :options="sortOptions" class="w-75">
                            <template v-slot:first>
                                <option value="">Sin ordenar</option>
                            </template>
                        </b-form-select>
                        <b-form-select v-model="sortDesc" size="sm" :disabled="!sortBy" class="w-25">
                            <option :value="false">Asc</option>
                            <option :value="true">Desc</option>
                        </b-form-select>
                    </b-input-group>
                </b-form-group>
            </b-col>

            <b-col lg="6" class="my-1">
                <b-form-group label="Por página" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="perPageSelect" class="mb-0">
                    <b-form-select v-model="perPage" id="perPageSelect" size="sm" :options="pageOptions"></b-form-select>
                </b-form-group>
            </b-col>

            <b-col lg="6" class="my-1">
                <b-pagination v-model="currentPage" :total-rows="totalRows" :per-page="perPage" align="fill" size="sm" class="my-0" ></b-pagination>
            </b-col>
        </b-row>

        <b-table class="my-3" show-empty small striped outlined stacked="sm" :items="items" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" @filtered="onFiltered" >
            <template v-slot:empty>
                <center><h5>No hay registros</h5></center>
            </template>

            <template v-slot:cell(index)="data">
                {{ data.index + 1 }}
            </template>

            <template v-slot:cell(valor)="data">
                {{ data.item.valor | currency }}
            </template>

            <template v-slot:cell(estado)="data">
                <b-badge v-if="data.item.estado == 1" variant="warning" class="text-white">Agendada</b-badge>
                <b-badge v-else-if="data.item.estado == 2" variant="success" class="text-white">Confirmada</b-badge>
                <b-badge v-else variant="danger" class="text-white">Anulada</b-badge>
            </template>

            <template v-slot:cell(acciones)="row">
                <router-link class="btn btn-warning btn-rounded btn-xs" :to="{ name: 'reagendar', params: {id: row.item.id}}"  v-b-tooltip.hover title="Reagendar reserva"><i class="fa fa-calendar"></i></router-link>

                <b-button size="xs" class="btn-rounded" variant="success" v-b-tooltip.hover title="Confirmar hora" @click="estado_reserva(row.item.id)">
                    <i class="fa fa-check"></i>
                </b-button>

                <b-button size="xs" class="btn-rounded" variant="danger" v-b-tooltip.hover title="Anular hora" @click="estado_reserva(row.item.id, 3)">
                    <i class="fa fa fa-remove"></i>
                </b-button>
            </template>
        </b-table>
    </div>
</template>

<script>
    export default {
        props: {
            id_cliente: null
        },
        data() {
            return {
                items: [],
                fields: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'venta_id', label: 'Número venta', sortable: true, class: 'text-left' },
                    { key: 'nombre_servicio', label: 'Servicio', sortable: true, class: 'text-left' },
                    { key: 'nombre_profesional', label: 'Profesional', sortable: true, class: 'text-left' },
                    { key: 'fecha_servicio', label: 'Fecha', sortable: true, class: 'text-left' },
                    { key: 'estado', label: 'Estado', sortable: true, class: 'text-center' },
                    { key: 'acciones', label: 'Acciones', class: 'text-center'}
                ],
                totalRows: 1,
                currentPage: 1,
                perPage: 15,
                pageOptions: [15, 50, 100, 150, 200, 250],
                sortBy: '',
                sortDesc: false,
                filter: null,
            }
        },
        computed: {
            sortOptions() {
                return this.fields.filter(f => f.sortable).map(f => {
                    return { text: f.label, value: f.key }
                })
            }
        },
        methods: {
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            listar_reservas(){
                let me = this

                axios.get('/api/clientes/reservas/'+ this.id_cliente).then(function (response) {
                    me.items = response.data
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
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
            }
        },
        mounted() {
            this.listar_reservas()
        }
    }
</script>
