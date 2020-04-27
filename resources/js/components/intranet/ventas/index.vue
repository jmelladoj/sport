<template>
    <div>
        <titulo-pagina titulo="Ventas" :accion="false"></titulo-pagina>
        
        <section class="main-content">
            <b-row>
                <b-col md="4">
                    <div class="widget  bg-light">
                        <div class="row row-table ">
                            <div>
                                <h2 class="margin-b-5">Ventas bono</h2>
                                <p class="text-muted">Total</p>
                                <span class="float-right text-primary widget-r-m">{{ ventas_bono | currency }}</span>
                            </div>
                        </div>
                    </div>
                </b-col>
                <b-col md="4">
                    <div class="widget  bg-light">
                        <div class="row row-table ">
                            <div>
                                <h2 class="margin-b-5">Ventas débito</h2>
                                <p class="text-muted">Total</p>
                                <span class="float-right text-indigo widget-r-m">{{ ventas_debito | currency }}</span>
                            </div>
                        </div>
                    </div>
                </b-col>
                <b-col md="4">
                    <div class="widget  bg-light">
                        <div class="row row-table ">
                            <div>
                                <h2 class="margin-b-5">Ventas crédito</h2>
                                <p class="text-muted">Total</p>
                                <span class="float-right text-success widget-r-m">{{ ventas_credito | currency }}</span>
                            </div>
                        </div>
                    </div>
                </b-col>
                <b-col md="6">
                    <div class="widget  bg-light">
                        <div class="row row-table ">
                            <div>
                                <h2 class="margin-b-5">Ventas efectivo</h2>
                                <p class="text-muted">Total</p>
                                <span class="float-right text-warning widget-r-m">{{ ventas_efectivo | currency }}</span>
                            </div>
                        </div>
                    </div>
                </b-col>
                <b-col md="6">
                    <div class="widget  bg-light">
                        <div class="row row-table ">
                            <div>
                                <h2 class="margin-b-5">Ventas transferencias</h2>
                                <p class="text-muted">Total</p>
                                <span class="float-right text-secondary widget-r-m">{{ ventas_transferencia | currency }}</span>
                            </div>
                        </div>
                    </div>
                </b-col>
            </b-row>

            <b-card>
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

                    <template v-slot:cell(total)="data">
                        {{ data.item.total | currency }}
                    </template>

                    <template v-slot:cell(tipo_documento)="data">
                        {{ data.item.tipo_documento == 1 ? 'Boleta' : 'Factura' }}
                    </template>

                    <template v-slot:cell(estado)="data">
                        <b-badge v-if="!data.item.deleted_at" variant="success" class="text-white">Activo</b-badge>
                        <b-badge v-else variant="danger" class="text-white">Inactivo</b-badge>
                    </template>

                    <template v-slot:cell(acciones)="row">
                        <b-button v-if="row.item.deleted_at" variant="warning" class="btn-rounded"  size="xs" v-b-tooltip.hover title="Restaurar venta" @click="borrar(row.item.id, 2)">
                            <i class="fa fa fa-undo"></i>
                        </b-button>

                        <b-button v-else size="xs" variant="danger" class="btn-rounded" v-b-tooltip.hover title="Anular venta" @click="borrar(row.item.id, 1)">
                            <i class="fa fa-trash"></i>
                        </b-button>
                    </template>
                </b-table>
            </b-card>
        </section>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                items: [],
                detalle: [],
                fields: [
                    { key: 'id', label: '#', sortable: true, class: 'text-center' },
                    { key: 'nombre_cliente', label: 'Cliente', sortable: true, class: 'text-left' },
                    { key: 'total', label: 'Total', sortable: true, class: 'text-left' },
                    { key: 'tipo_documento', label: 'Tipo de documento', sortable: true, class: 'text-center' },
                    { key: 'created_at', label: 'Fecha y hora', sortable: true, class: 'text-center' },
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
            },
            ventas_bono(){
                return this.detalle.reduce((total, d) => total += d.medio_pago === 1 ? parseInt(d.monto_pago) : 0, 0)
            },
            ventas_debito(){
                return this.detalle.reduce((total, d) => total += d.medio_pago === 2 ? parseInt(d.monto_pago) : 0, 0)
            },
            ventas_credito(){
                return this.detalle.reduce((total, d) => total += d.medio_pago === 3 ? parseInt(d.monto_pago) : 0, 0)
            },
            ventas_efectivo(){
                return this.detalle.reduce((total, d) => total += d.medio_pago === 4 ? parseInt(d.monto_pago) : 0, 0)
            },
            ventas_transferencia(){
                return this.detalle.reduce((total, d) => total += d.medio_pago === 5 ? parseInt(d.monto_pago) : 0, 0)
            }
        },
        methods: {
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            listar_ventas(){
                let me = this

                axios.get('/api/ventas').then(function (response) {
                    me.items = response.data.ventas
                    me.detalle = response.data.detalle
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            borrar(id, accion) {
                let me = this

                swal.fire({
                    title: accion == 1 ? '¿Deseas borrar el registro?' : '¿Deseas resturar el registro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: accion == 1 ? 'Sí, ¡bórrar!' : 'Sí, restaurar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/api/ventas/' + id).then(function (response) {
                            me.listar_ventas()
                            me.$store.commit('msg_success', accion == 1 ? 3 : 4)
                        }).catch(function (error) {
                            me.$store.commit('msg_error')
                        })
                    }
                })
            }
        },
        mounted() {
            this.listar_ventas()
        }
    }
</script>
