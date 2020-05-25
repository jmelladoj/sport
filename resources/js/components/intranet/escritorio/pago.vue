<template>
    <div>
        <titulo-pagina titulo="Pagar reserva" :accion="true">
            <template slot="btn_accion">
                <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="pagar"><i class="fa fa-pencil"></i>Actualizar registro</b-button>
            </template>
        </titulo-pagina>

        <section class="main-content">
            <b-card header-text-variant="white" header="Información sobre el servicio a pagar" header-bg-variant="primary">
                <b-form>
                    <b-row>
                        <b-col md="6">                    
                            <b-form-group label="Servicio">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        v-model="reserva.nombre_servicio" 
                                        readonly>    
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                        <b-col md="6">                    
                            <b-form-group label="Valor a pagar">
                                <b-input-group prepend="Abc">
                                    <b-form-input
                                        v-model="reserva.valor_servicio" 
                                        readonly>    
                                    </b-form-input>
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </b-form>
            </b-card>

            <b-card header-text-variant="white" header="Información sobre el pago" header-bg-variant="primary">
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
                                    >
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
                                                    >
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
                                                    >
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
                                                    >
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
                                    :state="$v.venta.observacion.$dirty ? !$v.venta.observacion.$error : null"
                                    rows="6"
                                    max-rows="10"
                                >
                                    
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
                    <b-button variant="success" class="box-shadow btn-icon btn-rounded" @click="pagar"><i class="fa fa-pencil"></i>Actualizar registro</b-button>
                    <b-button variant="danger" class="box-shadow btn-icon btn-rounded" @click="$router.go(-1)"><i class="fa fa-long-arrow-left"></i> Volver atrás</b-button>
                </b-col>
            </b-row>
        </section>
    </div>
</template>

<script>
    import { required, requiredIf, numeric, minValue, minLength } from 'vuelidate/lib/validators'
    import { mapMutations } from 'vuex'

    export default {
        data(){
            return {
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
                reserva: {
                    nombre_servicio: '',
                    valor_servicio: null
                },
                venta: {
                    id: null,
                    subtotal: null,
                    descuento: 0,
                    total: null,
                    tipo_documento: null,
                    observacion: ''
                },
                pagos: []
            }
        },
        validations: {
            pagos: {
                $each:{
                    medio_pago: {
                        required
                    },
                    numero_documento: {
                        required,
                        numeric,
                        minValue: minValue(1)
                    },
                    monto_pago: {
                        required,
                        numeric,
                        minValue: minValue(1)
                    }
                }
            },
            venta: {
                tipo_documento: {
                    required
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
            }
        },
        computed: {
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
        },
        methods: {
            listar_reserva(){
                if(!this.$route.params.id){
                    return false
                }

                let me = this

                axios.get('/api/reservas/'+ this.$route.params.id).then(function (response) {
                    me.reserva.nombre_servicio = response.data.nombre_servicio
                    me.reserva.valor_servicio = response.data.valor_servicio

                    me.venta.subtotal = response.data.valor_servicio
                    me.venta.total = response.data.valor_servicio
                    me.agregar_pago(response.data.valor_servicio)
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
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
            },
            pagar(){
                if(this.$v.$invalid){
                    this.$v.$touch()
                    this.$store.commit('msg_error', 'Recuerda llenar correctamente el formulario.')
                    return
                }

                let me = this

                axios({
                    method: 'PUT',
                    url: '/api/pago/reserva/' + this.$route.params.id,
                    data: {
                        'venta'   : me.venta,
                        'pagos' : me.pagos
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
            this.listar_reserva()
        }
    }
</script>