<template>
    <div>
        <titulo-pagina titulo="clientes"></titulo-pagina>

        <section class="main-content">
            <b-row>
                <b-col>
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

                            <template v-slot:cell(index)="data">
                                {{ data.index + 1 }}
                            </template>

                            <template v-slot:cell(telefono)="data">
                                +596 {{ data.item.telefono }}
                            </template>

                            <template v-slot:cell(acciones)="row">
                                <b-button v-if="!row.item.deleted_at" size="xs" variant="warning" v-b-tooltip.hover title="Actualizar infromación de registro" @click="abrir_modal_cliente(row.item)">
                                    <i class="fa fa-pencil"></i>
                                </b-button>

                                <b-button v-if="row.item.deleted_at" size="xs" v-b-tooltip.hover title="Restaurar registro" @click="borrar(row.item.id, 2)" class="btn btn-success">
                                    <i class="fa fa fa-undo"></i>
                                </b-button>

                                <b-button v-else size="xs" variant="danger" v-b-tooltip.hover title="Eliminar registro" @click="borrar(row.item.id, 1)">
                                    <i class="fa fa-trash"></i>
                                </b-button>
                            </template>
                        </b-table>
                    </b-card>
                </b-col>
            </b-row>

            <b-modal ref="modal_cliente" :title="modal_cliente.titulo" no-close-on-backdrop scrollable static>
                <b-row>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Nombre completo">
                            <b-form-input
                                v-model="$v.cliente.nombre.$model"
                                :state="$v.cliente.nombre.$dirty ? !$v.cliente.nombre.$error : null"
                                aria-describedby="cliente-nombre"
                            ></b-form-input>

                            <b-form-invalid-feedback id="cliente-nombre">
                                Campo de alfanúmerico, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Run">
                            <b-form-input
                                v-rut:live
                                v-model="$v.cliente.run.$model"
                                :state="$v.cliente.run.$dirty ? !$v.cliente.run.$error : null"
                                aria-describedby="cliente-run"
                            ></b-form-input>

                            <b-form-invalid-feedback id="cliente-run">
                                Debes ingresar un run válido y no existente en la base de datos.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Dirección">
                            <b-form-input
                                v-model="$v.cliente.direccion.$model"
                                :state="$v.cliente.direccion.$dirty ? !$v.cliente.direccion.$error : null"
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
                                v-model="$v.cliente.correo.$model"
                                :state="$v.cliente.correo.$dirty ? !$v.cliente.correo.$error : null"
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
                                v-model="$v.cliente.telefono.$model"
                                :state="$v.cliente.telefono.$dirty ? !$v.cliente.telefono.$error : null"
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
                                v-model="$v.cliente.fecha_nacimiento.$model"
                                :state="$v.cliente.fecha_nacimiento.$dirty ? !$v.cliente.fecha_nacimiento.$error : null"
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
                                v-model="$v.cliente.observacion.$model"
                                :state="$v.cliente.observacion.$dirty ? !$v.cliente.observacion.$error : null"
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

                <template slot="modal-footer">
                    <b-button type="button" v-b-tooltip.hover title="Añadir registro a la base de datos" :disabled="$v.cliente.$invalid" v-show="modal_cliente.accion == 1" size="md" variant="success" @click="crear_actualizar_cliente"> Guardar </b-button>
                    <b-button type="button" v-b-tooltip.hover title="Actualizar información de registro" :disabled="$v.cliente.$invalid" v-show="modal_cliente.accion == 2" size="md" variant="warning" @click="crear_actualizar_cliente"> Actualizar </b-button>
                    <b-button type="button" size="md" variant="danger" @click="cerrar_modal_cliente"> Cerrar </b-button>
                </template>
            </b-modal>

            <div class="fixed-bottom mb-2 mr-2">
                <b-button v-if="consulta == 2" v-b-tooltip.hover title="Ocultar registros eliminados" pill variant="warning" size="sm" class="pull-right heigth-25 px-2 ml-1 mr-1" @click="listar_clientes(1)"><i class="fa fa-eye"></i></b-button>
                <b-button v-else v-b-tooltip.hover title="Mostrar registros eliminados" pill variant="danger" size="sm" class="pull-right heigth-25 px-2 ml-1 mr-1" @click="listar_clientes(2)"><i class="fa fa-eye-slash"></i></b-button>
                <b-button v-b-tooltip.hover title="Agregar registro" pill variant="success" size="sm" class="pull-right heigth-25 px-2 ml-1 mr-1" @click="abrir_modal_cliente"><i class="fa fa-plus"></i></b-button>
            </div>
        </section>
    </div>

</template>

<script>
    import { required, between, email, minLength, isUnique, maxLength, numeric, requiredIf } from 'vuelidate/lib/validators'
    import { mapMutations, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                consulta: 1,
                items: [],
                fields: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'nombre', label: 'Nombre', sortable: true, class: 'text-left' },
                    { key: 'correo', label: 'Correo', sortable: true, class: 'text-left' },
                    { key: 'telefono', label: 'Teléfono', sortable: true, class: 'text-left' },
                    { key: 'acciones', label: 'Acciones', class: 'text-center'}
                ],
                totalRows: 1,
                currentPage: 1,
                perPage: 15,
                pageOptions: [15, 50, 100, 150, 200, 150],
                sortBy: '',
                sortDesc: false,
                filter: null,
                modal_cliente: {
                    titulo: '',
                    accion: 0
                },
                cliente: {
                    id: 0,
                    run: '',
                    nombre: '',
                    direccion: '',
                    correo: '',
                    telefono: null,
                    fecha_nacimiento: '',
                    observacion: ''
                }
            }
        },
        validations:{
            cliente: {
                run: {
                    required,
                    minLength: minLength(7),
                    async isUnique (value) {
                        if(this.modal_cliente.accion == 2) return true
                        if (value === '' || value.length < 3) return true

                        const response = await fetch(`/clientes/unico/${value}`)
                        return await response.json()
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
                    required: requiredIf(function () {
                        return this.modal_cliente.accion == 1 ? false : true
                    }),
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
            ...mapMutations(['msg_success', 'msg_error']),
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            listar_clientes(consulta = 1){
                let me = this

                me.consulta = consulta

                axios.get('/clientes/usuario/'+ me.consulta).then(function (response) {
                    me.items = response.data.clientes
                    me.totalRows = me.items.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrir_modal_cliente(data = []) {
                let me = this

                me.limpiar_datos_cliente()
                me.modal_cliente.titulo = data.id == undefined ? "Agregar cliente" : "Modificar cliente"
                me.modal_cliente.accion = data.id == undefined ? 1 : 2

                if(me.modal_cliente.accion == 2){
                    me.cliente.id = data.id
                    me.cliente.run = data.run
                    me.cliente.nombre = data.nombre
                    me.cliente.direccion = data.direccion
                    me.cliente.correo = data.correo
                    me.cliente.telefono = data.telefono
                    me.cliente.fecha_nacimiento = data.fecha_nacimiento
                    me.cliente.observacion = data.observacion

                    this.$v.cliente.$touch(true)
                }

                this.$refs['modal_cliente'].show()
            },
            cerrar_modal_cliente() {
                this.modal_cliente.titulo = ""
                this.modal_cliente.accion = 0
                this.$refs['modal_cliente'].hide()
            },
            limpiar_datos_cliente() {
                this.cliente.id = 0
                this.cliente.run = ''
                this.cliente.nombre = ''
                this.cliente.direccion = ''
                this.cliente.correo = ''
                this.cliente.telefono = null
                this.cliente.fecha_nacimiento = ''
                this.cliente.observacion = ''

                this.$v.$reset();
            },
            crear_actualizar_cliente() {
                if(this.$v.cliente.$invalid){
                    this.$v.cliente.$touch()
                    return
                }

                let me = this

                axios.post('/cliente/crear/actualizar',{
                        'id': me.cliente.id,
                        'run': me.cliente.run,
                        'nombre': me.cliente.nombre,
                        'direccion': me.cliente.direccion,
                        'correo': me.cliente.correo,
                        'telefono': me.cliente.telefono,
                        'fecha_nacimiento': me.cliente.fecha_nacimiento,
                        'observacion': me.cliente.observacion
                    }).then(function (response) {
                        me.listar_clientes()
                        me.$store.commit('msg_success', me.cliente.id == 0 ? 'Registro agregado exitosamente.' :  'Registro actualizado exitosamente.')

                        if(me.cliente.id == 0){
                            me.limpiar_datos_cliente()
                        } else {
                            me.cerrar_modal_cliente()
                        }

                    }).catch(function (error) {
                        me.$store.commit('msg_error', 'Ha ocurrido un error, por favor intentalo nuevamente.')
                        console.log(error)
                })
            },
            borrar(id, accion) {
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
                        let me = this
                        axios.post('/cliente/borrar',{
                            'id': id,
                            'accion': accion
                        }).then(function (response) {
                            me.listar_clientes(me.consulta);
                            me.$store.commit('msg_success', accion == 1 ? 'Registro eliminado exitosamente.' : 'Registro restaurado exitosamente.')
                        }).catch(function (error) {
                            me.$store.commit('msg_error', 'Ha ocurrido un error, por favor intentalo nuevamente.')
                            console.log(error);
                        })
                    }
                })
            }
        },
        mounted() {
            this.listar_clientes()
        }
    }
</script>
