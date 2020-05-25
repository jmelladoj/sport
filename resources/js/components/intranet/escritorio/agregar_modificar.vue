<template>
    <div>
        <titulo-pagina :titulo="titulo_pagina" :accion="true">
            <template slot="btn_accion">
                <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="agregar_modificar_reserva"><i :class="modifica ? 'fa fa-pencil' : 'fa fa-plus'"></i>{{ modifica ? 'Actualizar' : 'Agregar' }} registro</b-button>
            </template>
        </titulo-pagina>

        <section class="main-content">
            <b-card header-text-variant="white" header="Detalles de la reserva" header-bg-variant="primary">
                <b-row>
                    <b-col md="4">                    
                        <b-form-group label="Servicio">
                            <b-input-group prepend="123">
                                <b-form-select
                                    v-model="$v.reserva.servicio_id.$model"
                                    :state="$v.reserva.servicio_id.$dirty ? !$v.reserva.servicio_id.$error : null"
                                    :options="opciones_servicios"
                                    @change="cargar_profesionales">
                                </b-form-select>

                                <template v-slot:append>
                                    <router-link class="btn btn-success" to="/servicios/administrar/"  v-b-tooltip.hover title="Agregar servicios"><i class="fa fa-plus"></i></router-link>
                                </template>
                            </b-input-group>
                        </b-form-group>
                        

                        <b-form-invalid-feedback :state="$v.reserva.servicio_id.$dirty ? !$v.reserva.servicio_id.$error : null">
                            <errores :errores="errores_reserva_servicio_id"></errores>
                        </b-form-invalid-feedback>
                    </b-col>

                    <b-col md="4">                    
                        <b-form-group label="Profesional">
                            <b-input-group prepend="123">
                                <b-form-select
                                    v-model="$v.reserva.profesional_id.$model"
                                    :state="$v.reserva.profesional_id.$dirty ? !$v.reserva.profesional_id.$error : null"
                                    :options="opciones_profesionales"
                                    :disabled="opciones_profesionales.length ? false : true"
                                    @change="cargar_sesiones_servicio">
                                </b-form-select>
                            </b-input-group>
                        </b-form-group>
                        

                        <b-form-invalid-feedback :state="$v.reserva.profesional_id.$dirty ? !$v.reserva.profesional_id.$error : null">
                            <errores :errores="errores_reserva_profesional_id"></errores>
                        </b-form-invalid-feedback>
                    </b-col>

                    <b-col md="4">                    
                        <b-form-group label="¿Pagara?">
                            <b-input-group prepend="123">
                                <b-form-select
                                    v-model="$v.reserva.pagada.$model"
                                    :state="$v.reserva.pagada.$dirty ? !$v.reserva.pagada.$error : null"
                                    :options="opciones_pagada">
                                </b-form-select>
                            </b-input-group>
                        </b-form-group>
                        

                        <b-form-invalid-feedback :state="$v.reserva.pagada.$dirty ? !$v.reserva.pagada.$error : null">
                            <errores :errores="errores_reserva_pagada"></errores>
                        </b-form-invalid-feedback>
                    </b-col>

                    <b-col lg="12" v-show="detalle_reserva.length">
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
                                <b-tr v-for="(r, index) in detalle_reserva" :key="index">
                                    <b-td>{{ index + 1 }}</b-td>
                                    <b-td>{{ r.nombre_servicio }}</b-td>
                                    <b-td>
  
                                        <b-input-group prepend="Abc" class="mt-0 mb-0">
                                            <b-form-datepicker 
                                                v-model="$v.detalle_reserva.$each[index].fecha_servicio.$model" 
                                                :state="$v.detalle_reserva.$each[index].fecha_servicio.$dirty ? !$v.detalle_reserva.$each[index].fecha_servicio.$error : null"
                                                placeholder="Sin fecha seleccionada"
                                                @input="obtener_horarios(index)"
                                            ></b-form-datepicker>
                                        </b-input-group>
                                    </b-td>
                                    <b-td>
                                        <b-input-group prepend="123" class="mt-0 mb-0">
                                            <b-form-select
                                                :id="'servicio_' + index"
                                                v-model="$v.detalle_reserva.$each[index].hora_clinicas_id.$model"
                                                :state="$v.detalle_reserva.$each[index].hora_clinicas_id.$dirty ? !$v.detalle_reserva.$each[index].hora_clinicas_id.$error : null"
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
            </b-card>

            <b-card header-text-variant="white" header="Información sobre el cliente" header-bg-variant="primary">
                <b-form>
                    <b-row>
                        <b-col md="6">         
                            <b-form-group label="Run">
                                <vue-bootstrap-typeahead 
                                    ref="typeahead" 
                                    :data="clientes" 
                                    :serializer="c => c.run + ' - ' + c.nombre" 
                                    placeholder="Escribe para buscar"
                                    :maxMatches="10"
                                    @hit="cliente_seleccionado"
                                    v-model="$v.cliente.run.$model" 
                                    :state="$v.cliente.run.$dirty ? !$v.cliente.run.$error : null"
                                    />
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.cliente.run.$dirty ? !$v.cliente.run.$error : null">
                                <errores :errores="errores_cliente_run"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col md="6">                    
                            <b-form-group label="Nombre completo">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        placeholder="Ej: Carlos Beltrán"
                                        v-model="$v.cliente.nombre.$model" 
                                        :state="$v.cliente.nombre.$dirty ? !$v.cliente.nombre.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.cliente.nombre.$dirty ? !$v.cliente.nombre.$error : null">
                                <errores :errores="errores_cliente_nombre"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col md="6">                    
                            <b-form-group label="Dirección">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        placeholder="Ej: Avenida a, casa #557, Talcahuano"
                                        v-model="$v.cliente.direccion.$model" 
                                        :state="$v.cliente.direccion.$dirty ? !$v.cliente.direccion.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.cliente.direccion.$dirty ? !$v.cliente.direccion.$error : null">
                                <errores :errores="errores_cliente_direccion"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col md="6">                    
                            <b-form-group label="Correo">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        placeholder="Ej: contacto@srcenter.cl"
                                        v-model="$v.cliente.correo.$model" 
                                        :state="$v.cliente.correo.$dirty ? !$v.cliente.correo.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.cliente.correo.$dirty ? !$v.cliente.correo.$error : null">
                                <errores :errores="errores_cliente_correo"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col md="6">                    
                            <b-form-group label="Teléfono">
                                <b-input-group prepend="123">
                                    <b-form-input
                                        placeholder="Ej: 987654321"
                                        v-model="$v.cliente.telefono.$model" 
                                        :state="$v.cliente.telefono.$dirty ? !$v.cliente.telefono.$error : null">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.cliente.telefono.$dirty ? !$v.cliente.telefono.$error : null">
                                <errores :errores="errores_cliente_telefono"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col md="6">
                            <b-form-group label="Fecha de nacimiento">
                                <b-input-group prepend="Abc">
                                    <b-form-datepicker 
                                        v-model="$v.cliente.fecha_nacimiento.$model" 
                                        :state="$v.cliente.fecha_nacimiento.$dirty ? !$v.cliente.fecha_nacimiento.$error : null"
                                        locale="es"
                                        placeholder="Sin fecha seleccionada"
                                    ></b-form-datepicker>
                                </b-input-group>
                            </b-form-group>


                            <b-form-invalid-feedback :state="$v.cliente.fecha_nacimiento.$dirty ? !$v.cliente.fecha_nacimiento.$error : null">
                                <errores :errores="errores_cliente_fecha_nacimiento"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col md="12">
                            <b-form-group label="Observaciones">
                                <b-form-textarea
                                    placeholder="Ej: cliente solo con disponibilidad los días lunes."
                                    v-model="$v.cliente.observacion.$model" 
                                    :state="$v.cliente.observacion.$dirty ? !$v.cliente.observacion.$error : null">
                                    rows="6"
                                    max-rows="10"
                                ></b-form-textarea>

                                <b-form-invalid-feedback :state="$v.cliente.observacion.$dirty ? !$v.cliente.observacion.$error : null">
                                <errores :errores="errores_cliente_observacion"></errores>
                            </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </b-form>
            </b-card>

            <b-card v-if="cliente.id" header-text-variant="white" header="Historial clínico del cliente" header-bg-variant="primary">
                <reservas-cliente :id_cliente="cliente.id"></reservas-cliente>
            </b-card>

            <b-card v-if="reserva.servicio_id" header-text-variant="white" header="Información sobre el pago" header-bg-variant="primary">
                <b-form>
                    <b-row>
                        <b-col md="4">                    
                            <b-form-group label="Subtotal">
                                <b-input-group prepend="123">
                                    <b-form-input
                                        v-model="venta.subtotal"
                                        readonly>
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                        </b-col>

                        <b-col md="4">                    
                            <b-form-group label="Tipo de documento">
                                <b-input-group prepend="123">
                                    <b-form-select
                                        v-model="$v.venta.tipo_documento.$model"
                                        :state="$v.venta.tipo_documento.$dirty ? !$v.venta.tipo_documento.$error : null"
                                        :options="opciones_tipo_documento"
                                        :disabled="reserva.pagada != 1">
                                    </b-form-select>
                                </b-input-group>
                            </b-form-group>
                            

                            <b-form-invalid-feedback :state="$v.venta.tipo_documento.$dirty ? !$v.venta.tipo_documento.$error : null">
                                <errores :errores="errores_venta_tipo_documento"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col md="4">                    
                            <b-form-group label="Total">
                                <b-input-group prepend="123">
                                    <b-form-input
                                        v-model="venta.subtotal"
                                        readonly>
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                        </b-col>

                       <b-col lg="12">
                           <b-form-group>
                                <b-table-simple class="my-3" show-empty small striped outlined stacked="sm">
                                    <b-thead>
                                        <b-tr class="text-center">
                                            <b-th>#</b-th>
                                            <b-th>Monto a cancelar</b-th>
                                            <b-th>Número de documento</b-th>
                                            <b-th>Medio de pago</b-th>
                                            <b-th>{{ reserva.pagada == 1 ? 'Monto cancelado' : 'Monto pendiente' }}</b-th>
                                        </b-tr>
                                    </b-thead>
                                    <b-tbody>
                                        <b-tr v-for="(p, index) in pagos" :key="index">
                                            <b-td class="text-center">{{ index + 1 }}</b-td>
                                            <b-td>
                                                <b-input-group prepend="123">
                                                    <b-form-input
                                                        v-model="p.monto_a_cancelar"
                                                        readonly>
                                                    </b-form-input>
                                                </b-input-group>
                                            </b-td>
                                            <b-td>
                                                <b-input-group prepend="123">
                                                    <b-form-input
                                                        v-model="$v.pagos.$each[index].numero_documento.$model"
                                                        :state="$v.pagos.$each[index].numero_documento.$dirty ? !$v.pagos.$each[index].numero_documento.$error : null"
                                                        class="mt-0 mb-0"
                                                        :readonly="reserva.pagada != 1">
                                                    </b-form-input>
                                                </b-input-group>
                                            </b-td>
                                            <b-td>
                                                <b-input-group prepend="123" class="mt-0 mb-0">
                                                    <b-form-select
                                                        v-model="$v.pagos.$each[index].medio_pago.$model"
                                                        :state="$v.pagos.$each[index].medio_pago.$dirty ? !$v.pagos.$each[index].medio_pago.$error : null"
                                                        class="mt-0 mb-0"
                                                        :options="opciones_medios_pago"
                                                        :disabled="reserva.pagada != 1">
                                                    </b-form-select>
                                                </b-input-group>                                     
                                            </b-td>
                                            <b-td>
                                                <b-input-group prepend="123">
                                                    <b-form-input
                                                        v-model="$v.pagos.$each[index].monto_pago.$model"
                                                        :state="$v.pagos.$each[index].monto_pago.$dirty ? !$v.pagos.$each[index].monto_pago.$error : null"
                                                        class="mt-0 mb-0"
                                                        @change="calcular_pago(index)"
                                                        :readonly="reserva.pagada != 1">
                                                    </b-form-input>
                                                </b-input-group>
                                            </b-td>
                                        </b-tr>
                                    </b-tbody>
                                </b-table-simple>
                           </b-form-group>
                        </b-col>

                        <b-col md="6" class="d-none">                    
                            <b-form-group label="Descuento">
                                <b-input-group prepend="123">
                                    <b-form-input
                                        placeholder="Ej: 1000"
                                        v-model="$v.venta.descuento.$model" 
                                        :state="$v.venta.descuento.$dirty ? !$v.venta.descuento.$error : null"
                                        @change="calcular_descuento">
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>

                            <b-form-invalid-feedback :state="$v.venta.descuento.$dirty ? !$v.venta.descuento.$error : null">
                                <errores :errores="errores_venta_descuento"></errores>
                            </b-form-invalid-feedback>
                        </b-col>

                        <b-col md="12">
                            <b-form-group label="Observaciones">
                                <b-form-textarea
                                    placeholder="Ej: Queda verificar pago de transferencia."
                                    v-model="$v.venta.observacion.$model" 
                                    :state="$v.venta.observacion.$dirty ? !$v.venta.observacion.$error : null">
                                    rows="6"
                                    max-rows="10"
                                ></b-form-textarea>

                                <b-form-invalid-feedback :state="$v.venta.observacion.$dirty ? !$v.venta.observacion.$error : null">
                                    <errores :errores="errores_venta_observacion"></errores>
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
                opciones_profesionales: [],
                opciones_servicios: [],
                opciones_pagada: [
                    { text: 'Selecciona una opción ...', value: null },
                    { text: 'Solo reservar', value: 0},
                    { text: 'Pagar ahora', value: 1 },
                ],
                opciones_medios_pago: [
                    { text: 'Selecciona una opción ...', value: null },
                    { text: 'Bono', value: 1 },
                    { text: 'Tarjeta débito', value: 2 },
                    { text: 'Tarjeta crédito', value: 3 },
                    { text: 'Efectivo', value: 4 },
                    { text: 'Transferencia', value: 5 }
                ],
                opciones_tipo_documento: [
                    { text: 'Selecciona una opción ...', value: null },
                    { text: 'Boleta', value: 1 },
                    { text: 'Factura', value: 2}
                ],
                clientes: [],
                servicios: [],
                cliente: {
                    id: null,
                    run: '',
                    nombre: '',
                    direccion: '',
                    correo: '',
                    telefono: null,
                    fecha_nacimiento: null,
                    observacion: ''
                },
                reserva: {
                    servicio_id: null,
                    profesional_id: null,
                    pagada: null,
                },
                venta: {
                    id: null,
                    subtotal: null,
                    descuento: 0,
                    total: null,
                    tipo_documento: null,
                    observacion: ''
                },
                pagos: [],
                detalle_reserva: []
            }
        },
        validations: {
            reserva: {
                servicio_id: {
                    required,
                },
                profesional_id: {
                    required,
                },
                pagada: {
                    required
                }
            },
            cliente: {
                run: {
                    required,
                    minLength: minLength(9)
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
            },
            detalle_reserva:{
                $each: {
                    hora_clinicas_id: {
                        
                    },
                    fecha_servicio: {

                    }
                }
            },
            pagos: {
                $each:{
                    medio_pago: {
                        requiredIf: requiredIf((value) => {
                            return  value.pagado == 1
                        })
                    },
                    numero_documento: {
                        requiredIf: requiredIf((value) => {
                            return  value.pagado == 1
                        }),
                        numeric,
                        minValue: minValue(1)
                    },
                    monto_pago: {
                        requiredIf: requiredIf((value) => {
                            return  value.pagado == 1
                        }),
                        numeric,
                        minValue: minValue(1)
                    }
                }
            },
            venta: {
                tipo_documento: {

                },
                descuento: {
                    //requiredIf: requiredIf((value) => {
                    //    return value > 0
                    //}),
                    numeric,
                    minValue: minValue(0),
                },
                observacion: {
                    requiredIf: requiredIf((value) => {
                        return value.length < 3
                    }),
                    minLength: minLength(3),
                }
            },
        },
        computed: {
            titulo_pagina(){
                return !this.modifica ? 'Agendar reserva' : 'Modficar reserva'
            },
            errores_reserva_servicio_id () {
                const errores = []
                if (!this.$v.reserva.servicio_id.$dirty) return errores
                !this.$v.reserva.servicio_id.required && errores.push('El campo es requerido.')
                return errores
            },
            errores_reserva_profesional_id () {
                const errores = []
                if (!this.$v.reserva.profesional_id.$dirty) return errores
                !this.$v.reserva.profesional_id.required && errores.push('El campo es requerido.')
                return errores
            },
            errores_reserva_pagada () {
                const errores = []
                if (!this.$v.reserva.pagada.$dirty) return errores
                !this.$v.reserva.pagada.required && errores.push('El campo es requerido.')
                return errores
            },
            errores_cliente_run () {
                const errores = []
                if (!this.$v.cliente.run.$dirty) return errores
                !this.$v.cliente.run.required && errores.push('El campo es requerido.')
                !this.$v.cliente.run.minLength && errores.push('Ingresa al menos 7 caracteres.')
                return errores
            },
            errores_cliente_nombre () {
                const errores = []
                if (!this.$v.cliente.nombre.$dirty) return errores
                !this.$v.cliente.nombre.required && errores.push('El campo es requerido.')
                !this.$v.cliente.nombre.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_cliente_direccion () {
                const errores = []
                if (!this.$v.cliente.direccion.$dirty) return errores
                !this.$v.cliente.direccion.required && errores.push('El campo es requerido.')
                !this.$v.cliente.direccion.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_cliente_correo () {
                const errores = []
                if (!this.$v.cliente.correo.$dirty) return errores
                !this.$v.cliente.correo.required && errores.push('El campo es requerido.')
                !this.$v.cliente.correo.email && errores.push('Debes ingresar un mail válido.')
                !this.$v.cliente.correo.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_cliente_telefono () {
                const errores = []
                if (!this.$v.cliente.telefono.$dirty) return errores
                !this.$v.cliente.telefono.required && errores.push('El campo es requerido.')
                !this.$v.cliente.telefono.numeric && errores.push('Solo se aceptan números.')
                !this.$v.cliente.telefono.minLength || !this.$v.cliente.telefono.maxLength && errores.push('Debes ingresar un número de 9 dígitos.')
                return errores
            },
            errores_cliente_fecha_nacimiento () {
                const errores = []
                if (!this.$v.cliente.fecha_nacimiento.$dirty) return errores
                !this.$v.cliente.fecha_nacimiento.required && errores.push('El campo es requerido.')
                !this.$v.cliente.fecha_nacimiento.maxValue && errores.push('La fecha de nacimiento no puede ser mayor a hoy.')
                return errores
            },
            errores_cliente_observacion () {
                const errores = []
                if (!this.$v.cliente.observacion.$dirty) return errores
                !this.$v.cliente.observacion.required && errores.push('El campo es requerido.')
                !this.$v.cliente.observacion.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_venta_tipo_documento () {
                const errores = []
                if (!this.$v.venta.tipo_documento.$dirty) return errores
                !this.$v.venta.tipo_documento.required && errores.push('El campo es requerido.')
                return errores
            },
            errores_venta_descuento () {
                const errores = []
                if (!this.$v.venta.descuento.$dirty) return errores
                !this.$v.venta.descuento.required && errores.push('El campo es requerido.')
                !this.$v.venta.descuento.numeric && errores.push('El campo solo acepta números.')
                !this.$v.venta.descuento.minValue && errores.push('El campo debe de tener un valor mínimo de 1.')
                return errores
            },
            errores_venta_observacion () {
                const errores = []
                if (!this.$v.venta.observacion.$dirty) return errores
                !this.$v.venta.observacion.required && errores.push('El campo es requerido.')
                !this.$v.venta.observacion.minLength && errores.push('Ingresa al menos 3 caracteres.')
                return errores
            },
            errores_venta_pagado () {
                const errores = []
                if (!this.$v.reserva.pagada.$dirty) return errores
                !this.$v.reserva.pagada.required && errores.push('El campo es requerido.')
                return errores
            },
        },
        methods: {
            ...mapMutations(['msg_success', 'msg_error']),
            formatear_rut(){
                let value = this.$v.cliente.run.$model.replace(/\./g, '').replace('-', '');

                if (value.match(/^(\d{2})(\d{3}){2}(\w{1})$/)) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{3})(\w{1})$/, '$1.$2.$3-$4');
                }
                else if (value.match(/^(\d)(\d{3}){2}(\w{0,1})$/)) {
                    value = value.replace(/^(\d)(\d{3})(\d{3})(\w{0,1})$/, '$1.$2.$3-$4');
                }
                else if (value.match(/^(\d)(\d{3})(\d{0,2})$/)) {
                    value = value.replace(/^(\d)(\d{3})(\d{0,2})$/, '$1.$2.$3');
                }
                else if (value.match(/^(\d)(\d{0,2})$/)) {
                    value = value.replace(/^(\d)(\d{0,2})$/, '$1.$2');
                }

                this.$v.cliente.run.$model = value
                this.$refs.typeahead.inputValue = value

            },
            cargar_profesionales() {
                let me = this

                axios.get('/api/profesionales/listado').then(function (response) {
                    me.opciones_profesionales = response.data
                }).catch(function (error) {
                    me.msg_error()
                })
            },
            async listar_servicios(){
                let me = this

                await axios.get('/api/servicios').then(function (response) {
                    me.servicios = response.data
                    me.opciones_servicios.push({ value: null, text: 'Selecciona una opción ...'})
    
                    me.servicios.forEach(function(s) {
                        me.opciones_servicios.push({ value: s.id, text: s.nombre })
                    })

                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            async listar_profesionales(){
                let me = this

                await axios.get('/api/profesionales').then(function (response) {
                    me.profesionales = response.data
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            async listar_clientes(run){
                let me = this

                await axios.get('/api/clientes').then(function (response) {
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
                    me.cliente.id = cliente.id
                    me.cliente.run = cliente.run
                    me.cliente.nombre = cliente.nombre
                    me.cliente.direccion = cliente.direccion
                    me.cliente.correo = cliente.correo
                    me.cliente.telefono = cliente.telefono
                    me.cliente.fecha_nacimiento = cliente.fecha_nacimiento
                    me.cliente.observacion = cliente.observacion

                    me.$refs.typeahead.inputValue = cliente.run
                }
            },
            cargar_profesionales(){
                let me = this

                me.pagos = []
                me.opciones_profesionales = []

                let servicio = this.servicios.find(s => this.reserva.servicio_id == s.id)

                if(servicio){
                   var profesionales = this.profesionales.filter(p => p.especialidad_id == servicio.especialidad_id)
                
                    me.opciones_profesionales.push({ value: null, text: 'Selecciona una opción ...'})
    
                    me.profesionales.forEach(function(p) {
                        me.opciones_profesionales.push({ value: p.id, text: p.nombre })
                    })

                    me.venta.subtotal = servicio.valor
                    me.venta.total = servicio.valor

                    me.agregar_pago(servicio.valor)
                }
                
            },
            cargar_sesiones_servicio(){
                let me = this

                me.detalle_reserva = []

                let servicio = this.servicios.find(s => s.id == this.reserva.servicio_id)
                let profesional = this.profesionales.find(p => p.id == this.reserva.profesional_id)


                if(servicio && profesional){
                    for (let index = 0; index < servicio.cantidad_sesiones; index++) {
                        me.detalle_reserva.push({
                            'nombre_servicio': servicio.nombre,
                            'fecha_servicio': null,
                            'estado': 1,
                            'hora_clinicas_id': null
                        })
                    }
                }
            },
            agregar_modificar_reserva(){
                if(this.$v.$invalid){
                    this.$v.$touch()
                    this.$store.commit('msg_error', 'Recuerda llenar correctamente el formulario correctamente.')
                    return
                }

                let me = this

                var ruta = this.modifica ? '/api/reservas/' + this.reserva.id : '/api/reservas'
                var method = this.modifica ? 'PUT' : 'POST'

                axios({
                    method: method,
                    url: ruta,
                    data: {
                        'cliente' : me.cliente,
                        'reserva': me.reserva,
                        'detalle_reservas': me.detalle_reserva,
                        'venta'   : me.venta,
                        'pagos' : me.pagos
                    }
                }).then(function (response) {
                    me.listar_clientes()
                    me.limpiar_datos()
                    me.$store.commit('msg_success', me.modifica ? 2 : 1)
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            obtener_horarios(index){
                let me = this

                $("#servicio_" + index).children().remove().end()
                $("#servicio_" + index).prop('disabled', true)

                axios.post('/api/disponibilidad/profesional',{
                    'fecha': me.detalle_reserva[index].fecha_servicio,
                    'profesional_id': me.reserva.profesional_id
                }).then(function (response) {
                    $("#servicio_" + index).append(new Option("Selecciona una opción ...", null))

                    response.data.forEach(function(h) {
                        $("#servicio_" + index).append(new Option(h.hora + ' - ' + h.tipo_hora, h.hora_clinicas_id))
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
            limpiar_datos(){
                this.cliente.id = null
                this.cliente.run = ''
                this.cliente.nombre = ''
                this.cliente.direccion = ''
                this.cliente.correo = ''
                this.cliente.telefono = null
                this.cliente.fecha_nacimiento = null
                this.cliente.observacion = ''

                this.reserva.id = null
                this.reserva.servicio_id = null
                this.reserva.profesional_id = null
                this.reserva.pagada = null

                this.detalle_reserva = []

                this.venta.id = null
                this.venta.subtotal = null
                this.venta.descuento = 0
                this.venta.total = null
                this.venta.tipo_documento = null
                this.venta.observacion = ''
            },
            agregar_pago(cancela){
                this.pagos.push({
                    monto_a_cancelar: cancela,
                    monto_pago: null,
                    medio_pago: null,
                    numero_documento: null
                })
            },
            calcular_pago(index){
                let me = this
                let cancelado = this.pagos.reduce((total, v) => total += parseInt(v.monto_pago), 0)

                me.pagos.forEach(function(p, index) {
                    if(p.monto_a_cancelar == p.monto_pago){
                        me.pagos.splice((index + 1), me.pagos.length)
                    }
                })

                if(me.pagos[index].monto_pago < me.pagos[index].monto_a_cancelar){
                    me.agregar_pago(me.pagos[index].monto_a_cancelar - me.pagos[index].monto_pago)                   
                }
            },
            calcular_descuento(){
                this.venta.total = this.venta.total - this.venta.descuento
            }
        },
        mounted() {
            this.listar_servicios()
            this.listar_profesionales()
            this.listar_clientes()
        }
    }
</script>