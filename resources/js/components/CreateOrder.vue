<template>
    <div>
         <!-- Modal -->
        <div class="modal fade" id="create_order" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" ref="ver">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crear Orden</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <v-select @search="getUsers" v-model="order.user" :options="users" label="name"></v-select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Ciudad</label>
                                    <v-select v-model="order.city" :options="cities" label="name"></v-select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Productos</label>
                                    <v-select v-model="order.products" @input="addOrderProducts" :options="products" label="name"></v-select>
                                </div>
                            </div>
                            <div class="row" style="height: 430px" v-if="order.orderProducts.length > 0">
                                <div class="col-md-12 pre-scrollable" style="max-height:430px">
                                    <div class="card mt-1" :key="key" v-for="(item, key) in order.orderProducts" >
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <ul style="list-style-type: none">
                                                        <li v-if="order.orderProducts.length > 0"><h5>{{item.product.name}}</h5></li>
                                                        <li v-if="order.orderProducts.length > 0"><b>Precio: </b>{{item.product.sale_price}}</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <div class="col-md-4" style="padding-left: 0; padding-top: 22px;">
                                                            <button class="btn btn-primary" style="width: 100%" @click.prevent="downQuantity(item.product.id)"><i class="fa fa-minus"></i></button>
                                                        </div>
                                                        <div class="col-md-4" style="padding-left: 0; padding-top: 22px;">
                                                            <input type="text" v-model="item.quantity" disabled value="1" class="form-control" id="quantity" style="width: 100%">
                                                        </div>
                                                        <div class="col-md-4" style="padding-left: 0; padding-top: 22px;">
                                                            <button class="btn btn-primary" style="width: 100%" @click.prevent="upQuantity(item.product.id)"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Comentarios</label>
                                    <textarea class="form-control" v-model="order.comments"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Impuesto</label>
                                    <textarea class="form-control" v-model="order.impuesto"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h3><b>Subtotal:</b> {{getSubtotal}}</h3>
                            </div>
                            <div class="col-md-12">
                                <h3><b>Impuesto:</b> {{order.impuesto}}</h3>
                            </div>
                            <div class="col-md-12">
                                <h3><b>Total:</b> {{FinallyTotal}}</h3>
                            </div>
                            <button @click.prevent="saveOrder" class="btn btn-primary">Crear Orden</button>
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
            order: {
                orderProducts: [],
                subtotal: 0,
                impuesto: 0,
                total: 0
            },
            products: [],
            users: [],
            cities: [],
            infoCustomer: [],
            subtotal: false,
            total: false,
        }
        order: {}
    },
    mounted() {
        this.getProduct();
        this.getCities();
    },
    methods: {
        getProduct: function(){
            let loader = this.$loading.show({ container: this.$refs.ver })
            axios.get('/api/order/getProduct')
            .then(res => {
                loader.hide()
                this.products = res.data.data
            }).catch(err => {
                loader.hide()
                this.$swal({
                    icon: 'error',
                    text: 'No se pudo cargar los productos'
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

        getUsers: function(search, loading){
            let loader = this.$loading.show({ container: this.$refs.ver })
            axios.post('/api/order/getUsers', {search:search})
            .then(res => {
                loader.hide()
                this.users = res.data.data
            }).catch(err => {
                loader.hide()
                this.$swal({
                    icon: 'error',
                    text: 'No se pudo cargar los usuarios'
                })
            })
        },

        saveOrder: function(){
            if(!this.order.user){
                this.$swal({
                    icon: 'error',
                    text: 'No ha seleccionado un usuario'
                })
            }else if(!this.order.city){
                this.$swal({
                    icon: 'error',
                    text: 'No ha seleccionado una ciudad'
                })
            }else if(!this.order.orderProducts){
                this.$swal({
                    icon: 'error',
                    text: 'No ha seleccionado ningun producto'
                })
            }else if(!this.order.impuesto){
                this.$swal({
                    icon: 'error',
                    text: 'No ha especificado el valor del impuesto'
                })
            }else{
                let loader = this.$loading.show({ container: this.$refs.ver })
                let data = new FormData()
                for (const key in this.order) {
                    if (key == "user") {
                        data.append('user',this.order.user.id)
                    }else if(key == "city"){
                        data.append('city', this.order.city.id)
                    }else if(key == "orderProducts"){
                        data.append('products',JSON.stringify(this.order.orderProducts))
                    }else if(key == "comment"){
                        data.append(key,this.order[key])
                    }else if(key == "impuesto"){
                        data.append(key,this.order[key])
                    }else if(key == "subtotal"){
                        data.append(key,this.order[key])
                    }else if(key == "total"){
                        data.append(key,this.order[key])
                    }
                }
                axios.post('/api/order/saveOrder', data)
                .then(res => {
                    loader.hide()
                    if(res.data.code == 200){
                        this.$swal({
                            icon: 'success',
                            text: 'Se creo la orden exitosamente'
                        })
                        this.$emit('newOrder')
                        $('#create_order').modal('hide')
                    }
                }).catch(err => {
                    loader.hide()
                    this.$swal({
                        icon: 'error',
                        text: 'No se pudo cargar los productos'
                    })
                })
            }
        },

        addOrderProducts: function(){
            var filter = this.order.orderProducts.filter( item => item.id == this.order.products.id )
            if(filter.length == 0){
                this.order.orderProducts.push({ product: this.order.products, quantity: 1 })
            }else{
                alert(filter)
            }
            this.order.OrderDetail = null        
            this.subtotal = true
        },

        upQuantity: function(id){
            console.log(id)
            this.order.orderProducts.forEach(item => {
                if(item.product.id == id){
                    item.quantity++
                }
            })

            this.subtotal = true
        },

        downQuantity: function(id){
            this.order.orderProducts.forEach(item => {
                if(item.product.id == id){
                    item.quantity--
                }
            })

            this.subtotal = true
        },
    },
    watch: {
        order: {
            handler: function(){
                localStorage.setItem('orden', JSON.stringify(this.order))
                this.infoCustomer = [JSON.parse(localStorage.getItem('orden'))];
            },
            deep: true,
        }
    },

    computed: {
        getSubtotal: function(){
            this.subtotal = false
            let option = 0
            this.infoCustomer.map(function(item){
                for(var i=0; i < item.orderProducts.length; i++){
                    if(item.orderProducts.length > 0){
                        let sale_price = item.orderProducts[i].product.sale_price
                        let quantity = item.orderProducts[i].quantity
                        option+=sale_price*quantity 
                    }
                }
            })
            this.order.subtotal = option
            return option
            this.total = true
        },

        FinallyTotal: function(){
            this.total = false
            let total = 0
            let subtotal = this.order.subtotal
            let shipping_value = this.order.impuesto 
            total += subtotal + (subtotal * shipping_value / 100)
            this.order.total = total
            return total            
        }
    },
}
</script>