<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Order Details</strong></h5>
    </div>
    <div class="col-md-12 mt-2">
        <div class="bg-white rounded-1 p-3">
            <div v-if="order.order_status == `PROCESSING`" class="row m-0">
                <div class="col-md-12 p-2 mt-3">
                    <button @click="shipModal()" class="btn btn-primary px-4 rounded-1 float-end text-white"><span class="fa fa-shipping-fast"></span> Ship Order</button>
                </div>
            </div>
            <div v-if="loading" class="row m-0">
                <div class="col-md-12 p-5 mt-3">
                    <div class="d-flex justify-content-center align-self-center">
                        <pulse-loader :color="`#011b48`" :size="`15px`"></pulse-loader> 
                    </div>
                </div> 
            </div>
            <div v-if="!loading" class="row m-0">
                <div class="col-md-6 mt-3">
                    <h6 class="text-center">Order No.</h6>
                    <h5 class="text-center"><strong>{{order.order_no}}</strong></h5>
                </div>
                <div class="col-md-6 mt-3 border-start">
                    <h6 class="text-center">Order Date</h6>
                    <h5 class="text-center"><strong>{{order.created_at | moment("ddd, MMM Do YYYY")}}</strong></h5>
                </div> 
                <div class="col-md-12 mt-3">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr class="text-center">
                                <th colspan="2">Products</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ot in orderItems" :key="ot.id" class="align-middle">
                                <td class="ps-2 text-center"><img :src="`/storage/productsThumb/`+ot.p_image" class="img img-fluid" style="width:70px;height:auto"></td>
                                <td class="ps-2">
                                    <h6>{{ot.p_name}}</h6>                                
                                </td>
                                <td class="ps-2 text-center">{{ot.quantity}}</td>
                                <td class="ps-2 text-center">{{ot.price | numFormat}} ETB</td>
                                <td class="ps-2 text-center">{{order.order_status}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>    
                <div class="col-md-8 mt-3">

                </div>     
                    <div class="col-md-4 border-bottom border-3 mt-3">
                    <h6 class="mt-2">Sub total: <span class="float-end">{{order.total/1.15 | numFormat}} ETB</span></h6>
                    <h6 class="mt-2">Tax (15% VAT): <span class="float-end">{{order.total/1.15 * 0.15 | numFormat}} ETB</span></h6>
                    <h5 class="mt-2"><strong>Total: <span class="float-end">{{order.total | numFormat}} ETB</span></strong></h5>
                    </div>
                    <div @click="addressModal(address.geolocation)" class="col-md-6 mt-5">
                        <h5 class="border-bottom">Shipping Information</h5>
                        <div class="rounded-1 border-start border-warning border-5 p-3" style="cursor:pointer">
                            <h5><strong>{{address.f_name}} {{address.l_name}}</strong> </h5>
                            <h6>+251-{{address.phone_no}}</h6>
                            <h6>{{address.regular_address}}</h6>
                        </div>
                    </div>
                    <div @click="addressModal(address.geolocation)" class="col-md-6 mt-5">
                    <h5 class="border-bottom">Billing Information</h5>
                    <div class="rounded-1 border-start border-warning border-5 p-3" style="cursor:pointer">
                        <h5><strong>{{address.f_name}} {{address.l_name}}</strong> </h5>
                        <h6>+251-{{address.phone_no}}</h6>
                        <h6>{{address.regular_address}}</h6>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</div>

</template>
<script>
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import addressModalVue from './addressModal.vue'
import shippingDetailsVue from './shippingDetails.vue'
export default {
    components:{
        PulseLoader
    },
    data(){
        return{
            order:{},
            orderItems:[],
            address:{},
            loading:true
        }
    },
    created(){

    },
    mounted(){
        this.getOrder()

    },
    methods:{
        shipModal(){
            this.$modal.show(
                shippingDetailsVue,
                {id:this.$route.params.id},
                {width:"80%", height:"auto"},
                {"closed":this.getOrder}
            )
        },
        addressModal(location){
            this.$modal.show(
                addressModalVue,
                {location:location},
                {height:"auto",width:"800px"},
            )
        },
        async getOrder(){
            this.loading = true
            await axios.get('/orders/'+this.$route.params.id)
            .then( response =>{
                this.order = response.data.order_details
                this.orderItems = response.data.order_items

                this.getAddress(response.data.order_details.delivery_details)
            })
        },
        async getAddress(id){
            await axios.get('/addressBooks/'+id)
            .then( response =>{
                this.address = response.data
                this.loading = false
            })
        }
    }
}
</script>