<template ref="order">
    <div>
        <v-client-table :data="rows" :columns="columns" :options="{ headings }">
            <template slot="opciones" slot-scope="props">   
                <button type="button" class="btn btn-primary btn-sm" @click="openModal('#edit_order', props.row)">
                    <i class="fa fa-edit"></i>
                </button>
                <button type="button" class="btn btn-primary btn-sm" @click="deleteOrder(props.row.id)">
                    <i class="fa fa-remove"></i>
                </button>
            </template>
        </v-client-table>

        <div class="modal fade" id="edit_order" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" ref="ver">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Orden</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Ciudad</label>
                                    <v-select v-model="data_edit.city" :options="cities" label="name"></v-select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Comentarios</label>
                                    <textarea class="form-control" v-model="data_edit.comments"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <v-select :options="statuses" label="name" v-model="data_edit.status"></v-select>
                                </div>
                            </div>
                            <button @click.prevent="editOrder" class="btn btn-primary">Editar Orden</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            rows: [],
            columns: [
                'id',
                'user.name',
                'city.name',
                'status.name',
                'subtotal',
                'iva_id',
                'total',
                'opciones'
            ],
            headings: {
                'id': 'Id',
                'user.name': 'Cliente',
                'city.name': 'Ciudad',
                'status.name': 'Estado',
                'subtotal': 'Subtotal',
                'iva_id': 'Impuesto',
                'total': 'Total'
            },
            data_edit: {},
            cities: [],
            statuses: []
        }
    },
    mounted() {
        this.getOrder();
        this.getStatuses();
        this.getCities();
    },
    methods: {
        getOrder: function(){
            let loader = this.$loading.show({ container: this.$refs.order })
            axios.get('/api/order/getOrder')
            .then(res => {
                loader.hide()
                this.rows = res.data.data
            }).catch(err => {
                loader.hide()
                this.$swal({
                    icon: 'error',
                    text: 'No se pudo cargar las ordenes'
                })
            })
        },

        getStatuses: function(){
            let loader = this.$loading.show({ container: this.$refs.order })
            axios.get('/api/order/getStatuses')
            .then(res => {
                loader.hide()
                this.statuses = res.data.data
            }).catch(err => {
                loader.hide()
                this.$swal({
                    icon: 'error',
                    text: 'No se pudo cargar los estados'
                })
            })
        },

        openModal: function(id, data = null){
            this.data_edit = data;
            $(id).modal('show');
        },

        editOrder: function(){
            let loader = this.$loading.show({ container: this.$refs.order })
            let formData = new FormData()
            formData.append('status', this.data_edit.status.id)
            formData.append('comment', this.data_edit.comments)
            formData.append('city', this.data_edit.city.id)
            formData.append('id', this.data_edit.id)
            axios.post('/api/order/editOrder', formData)
            .then(res => {
                loader.hide()
               if(res.data.code == 200){
                   this.$swal({
                    icon: 'success',
                    text: 'Se edito la orden exitosamente'
                })
                this.getOrder()
                $('#edit_order').modal('hide')
               }
            }).catch(err => {
                loader.hide()
                this.$swal({
                    icon: 'error',
                    text: 'No se pudo cargar las ordenes'
                })
            })
        },

        deleteOrder: function(id){
            let loader = this.$loading.show({ container: this.$refs.order })
            axios.post('/api/order/deleteOrder', { id })
            .then(res => {
                loader.hide()
                this.$swal({
                    icon: 'success',
                    text: 'Se elimino la orden exitosamente'
                })
                this.getOrder()
            }).catch(err => {
                loader.hide()
                this.$swal({
                    icon: 'error',
                    text: 'No se pudo cargar las ordenes'
                })
            })
        },

        getCities: function(){
            let loader = this.$loading.show({ container: this.$refs.ver })
            axios.get('/api/order/getCities')
            .then(res => {
                loader.hide()
                this.cities = res.data.data
            }).catch(err => {
                loader.hide()
                this.$swal({
                    icon: 'error',
                    text: 'No se pudo cargar las ciudades'
                })
            })
        },
    },
}
</script>