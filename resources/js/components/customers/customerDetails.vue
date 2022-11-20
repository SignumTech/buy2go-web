<template>
<div class="row mt-4 mx-4">
    <div class="col-md-4">
        <div class="bg-white rounded-1 shadow-sm p-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="rounded-circle overflow-hidden d-block m-auto" style="width:100px; height:100px">
                        <img class="img img-fluid" src="/storage/settings/store_placeholder.png" alt="">
                    </div>
                    <h5 class="text-center mt-3"><strong>{{shopDetails.shop_details.f_name}} {{shopDetails.shop_details.l_name}}</strong></h5>
                    <h6 class="text-center">+251-{{shopDetails.shop_details.phone_no}}</h6>
                    <hr>
                </div>
                <div class="col-md-12">
                    <h6><strong>Last Order</strong></h6>
                    <h6>{{shopDetails.last_order.order_no}} - {{shopDetails.last_order.created_at | moment("MMM Do YYYY")}}</h6>
                    <h6 class="mt-3"><strong>Average Order Value</strong></h6>
                    <h6>{{shopDetails.average_order | numFormat}} ETB</h6>
                    <h6 class="mt-3"><strong>Shop Registered</strong></h6>
                    <h6>{{shopDetails.shop_details.created_at | moment("MMM Do YYYY")}}</h6>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-1 shadow-sm p-4 mt-3">
            <div class="row">
                <h6><strong>Shop Locations</strong></h6>
                <div v-for="address,index in locations" :key="index" class="col-md-12 mt-2">
                    <h6 class="mb-2"><span class="fa fa-map-marker-alt"></span> {{address.regular_address}}</h6>
                </div>
            </div>
            
        </div>
    </div>
    <div class="col-md-8">
        <div v-if="shopDetails.shop_details.shop_status === `NOT_VERIFIED`" class="bg-warning rounded-1 shadow-sm">
            <div class="row mx-0 border-bottom p-3">
                <div class="col-md-6 align-self-center">
                    <h5 class="mb-0"><span class="fa fa-exclamation-triangle"></span> This shop is not verified!</h5>
                </div>
                <div class="col-md-6">
                    <button @click="verifyModal()" class="btn btn-success btn-sm float-end shadow-sm text-white"><span class="fa fa-check-circle"></span> Verify Shop</button>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-1 shadow-sm mt-2">
            <div class="row mx-0 border-bottom p-3">
                <div class="col-md-6">
                    <h5 class="mb-0"><strong>Orders</strong></h5>
                </div>
                <div class="col-md-6">
                    <h6 class="text-end mb-0">Total spent <strong>{{orders.total_spent | numFormat}} ETB</strong> on <strong>{{orders.orders_count}}</strong> orders</h6>
                </div>
            </div>
            <div class="row mx-0 border-bottom p-3">
                <table class="table">
                    <tbody>
                        <tr v-for="order,index in orders.orders" :key="index">
                            <td>{{order.order_no}}</td>
                            <td>{{order.created_at | moment("MMM Do YYYY")}}</td>
                            <td>{{order.order_status}}</td>
                            <td>{{order.items_count}} Items</td>
                            <td><strong>{{order.total | numFormat}} ETB</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import verifyShopModalVue from './verifyShopModal.vue'
export default {
    data(){
        return{
            shopDetails:{},
            orders:{},
            locations:{}
        }
    },
    mounted(){
        this.getShopDetails()
        this.getShopOrders()
        this.getShopLocations()
    },
    methods:{
        verifyModal(){
            this.$modal.show(
                verifyShopModalVue,
                {shop_id:this.$route.params.id},
                {height:"auto", width:'500px'},
                {"closed":this.getShopDetails}
            )
        },
        async getShopDetails(){
            await axios.get('/shopDetails/'+this.$route.params.id)
            .then( response =>{
                this.shopDetails = response.data
                console.log(this.shopDetails)
            })
        },
        async getShopOrders(){
            await axios.get('/shopOrders/'+this.$route.params.id)
            .then( response =>{
                this.orders = response.data
            })
        },
        async getShopLocations(){
            await axios.get('/shopLocations/'+this.$route.params.id)
            .then( response =>{
                this.locations = response.data
            })
        }
    }
}
</script>