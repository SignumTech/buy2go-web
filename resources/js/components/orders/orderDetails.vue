<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Order Details</strong></h5>
    </div>
    <div class="col-md-12 mt-2">
        <div class="bg-white rounded-1 p-3">
            <div class="row mx-0 mb-3">
                <div class="col-md-2 align-self-center">
                    <router-link to="/ordersList" style="cursor:pointer"><span class="fa fa-arrow-left fs-5"></span></router-link>
                </div>
                
                <div class="col-md-8 d-flex justify-content-center">
                    <div class="text-center">
                        <div class="p-2 mx-5 bg-success rounded-5 text-white">
                            <i data-feather="shopping-cart"></i>
                        </div>
                        
                        <p class="text-center m-0">Order Placed</p>
                    </div>
                    <div>
                        <div v-if="order.order_status == `PENDING_CONFIRMATION` || order.order_status == `PENDING_PICKUP` || order.order_status == `SHIPPED` || order.order_status == `DELIVERED`" class="p-2 mx-5 bg-success rounded-5 text-white">
                            <i data-feather="user-plus"></i>
                        </div>
                        <div v-else class="p-2 mx-5 bg-secondary rounded-5 text-white">
                            <i data-feather="user-plus"></i>
                        </div>
                        <p class="text-center m-0">Driver Assigned</p>
                    </div>
                    <div>
                        <div v-if="order.order_status == `PENDING_PICKUP` || order.order_status == `SHIPPED` || order.order_status == `DELIVERED`" class="p-2 mx-5 bg-success rounded-5 text-white">
                            <i data-feather="thumbs-up"></i>
                        </div>
                        <div v-else class="p-2 mx-5 bg-secondary rounded-5 text-white">
                            <i data-feather="thumbs-up"></i>
                        </div>
                        <p class="text-center m-0">Driver Confirmed</p>
                    </div>
                    <div>
                        <div v-if="order.order_status == `SHIPPED` || order.order_status == `DELIVERED`" class="p-2 mx-5 bg-success rounded-5 text-white">
                            <i data-feather="truck"></i>
                        </div>
                        <div v-else class="p-2 mx-5 bg-secondary rounded-5 text-white">
                            <i data-feather="truck"></i>
                        </div>
                        <p class="text-center m-0">Shipped</p>
                    </div>
                    <div>
                        <div  v-if="order.order_status == `DELIVERED`" class="p-2 mx-5 bg-success rounded-5 text-white">
                            <i data-feather="check-circle"></i>
                        </div>
                        <div v-else class="p-2 mx-5 bg-secondary rounded-5 text-white">
                            <i data-feather="check-circle"></i>
                        </div>
                        <p class="text-center m-0">Delivered</p>
                    </div>
                </div>
                <div v-if="order.order_status == `CANCELED`" class="col-md-12 mt-3">
                    <h5 class="bg-danger text-white m-0 p-2 rounded-1 shadow-sm text-center">Order Canceled!</h5>
                </div>
                <div v-if="order.order_status == `PROCESSING` && permission.assignDetails" class="col-md-2 p-2 ">
                    <button @click="shipModal()" class="btn btn-primary px-4 rounded-1 float-end text-white"><span class="fa fa-shipping-fast"></span> Ship Order</button>
                </div>
                <div v-if="order.order_status == `SHIPPED` && permission.confirmDelivery" class="col-md-2 p-2 ">
                    <button @click="deliverOrder()" class="btn btn-primary px-4 rounded-1 float-end text-white"><span class="fa fa-box-open"></span> Confirm Delivery</button>
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
                <div v-if="order.order_status != `PROCESSING`" class="col-md-12 mt-4">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-center">Assigned Driver</h6>
                            <h5 class="text-center"><strong>{{orderDriver.f_name}} {{orderDriver.l_name}} - {{orderDriver.l_plate}}</strong></h5>
                        </div>
                        <div class="col-md-3 border-start border-end">
                            <h6 class="text-center">Assigned Warehouse</h6>
                            <h5 class="text-center"><strong>{{orderDriver.w_name}}</strong></h5>
                        </div>
                        <div class="col-md-3 border-end">
                            <h6 class="text-center">Driver accepted Order</h6>
                            <h5 class="text-center"><strong>{{order.accepted_at | moment("MMM Do YYYY, H:m:s")}}</strong></h5>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-center">Last status update</h6>
                            <h5 class="text-center"><strong>{{order.updated_at | moment("MMM Do YYYY, H:m:s")}}</strong></h5>
                        </div>
                    </div>
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
                            <tr v-for="ot in orderItems" :key="ot.id" :class="(ot.item_status == `USER_REMOVED` || ot.item_status == `WAREHOUSE_REMOVED`)?'align-middle table-danger':'align-middle'">
                                <td class="ps-2 text-center"><img :src="`/storage/productsThumb/`+ot.p_image" class="img img-fluid" style="width:70px;height:auto"></td>
                                <td class="ps-2">
                                    <h6>{{ot.p_name}} <span v-if="!parseInt(ot.taxable)" class="bg-warning float-end p-1 rounded-pill px-2 shadow-sm">Non Taxable Item</span></h6>                                
                                </td>
                                <td class="ps-2 text-center">{{ot.item_status?ot.updated_quantity:ot.quantity}}</td>
                                <td class="ps-2 text-center">{{ot.price | numFormat}} ETB</td>
                                <td class="ps-2 text-center">{{ot.item_status?ot.item_status:`UNCHANGED`}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>    
                <div class="col-md-8 mt-3">
                    <div v-if="order.order_type == `AGENT_ORDER`">
                        <h5><span class="badge rounded-pill bg-info text-dark">Ordered by agent</span></h5>
                        <h6>Agent Name: {{agent.f_name}} {{ agent.l_name }}</h6>
                        <h6>Phone Number: +{{agent.country_code}}-{{ agent.phone_no }}</h6>
                    </div>
                    
                </div>     
                <div class="col-md-4 border-bottom border-3 mt-3">
                    <h6 class="mt-2">Sub total: <span class="float-end">{{taxCalculations.subTotal | numFormat}} ETB</span></h6>
                    <h6 class="mt-2">Tax (15% VAT): <span class="float-end">{{taxCalculations.vat | numFormat}} ETB</span></h6>
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
            agent:{},
            order:{},
            taxCalculations:{
                subTotal:null,
                vat:null,
                total:null
            },
            orderItems:[],
            address:{},
            loading:true,
            permission:{},
            orderDriver:{}
        }
    },
    created(){

    },
    mounted(){
        this.$store.dispatch('auth/permissions')
        .then( () =>{
            this.permission = this.$store.state.auth.permissions
        })
        this.getOrder()
        this.getOrderDriver()
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
        async deliverOrder(){
            if(confirm("Are you sure you want to confirm this delivery?")){
                await axios.put('/confirmDelivery/'+this.$route.params.id, {payment_method:"Cash on delivery", payment_status:"PAID"})
                .then( response =>{
                    this.getOrder()
                    this.getOrderDriver()
                    this.$notify({
                        group: 'foo',
                        type: 'success',
                        title: 'Order Delivered',
                        text: 'Order Delivered Successfully'
                    });
                })
            }
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
                this.taxCalculations = this.calculateTax(this.orderItems)
                this.getAddress(response.data.order_details.delivery_details)
                if(this.order.order_type == 'AGENT_ORDER'){
                    this.getAgent(this.order.agent_id)
                }
            })
        },
        async getAgent(id){
            await axios.get('/getAgent/'+id)
            .then( response =>{
                this.agent = response.data
            })
        },
        calculateTax(orderItems){
            var subtotal = 0;
            var vat=0;
            var total = 0;
            orderItems.forEach(item =>{
                if(item.item_status == 'UPDATED' || item.item_status == 'USER_REMOVED' || item.item_status == 'WAREHOUSE_REMOVED'){
                    subtotal = subtotal+(item.price*item.updated_quantity)
                    if(parseInt(item.taxable) === 1){
                        total = total+(item.price*item.updated_quantity*1.15)
                        vat = vat+(item.price*item.updated_quantity*0.15)
                    }
                    else{
                        total = total+(item.price*item.quantity)
                    }
                }
                else{
                    subtotal = subtotal+(item.price*item.quantity)
                    if(parseInt(item.taxable) === 1){
                        total = total+(item.price*item.quantity*1.15)
                        vat = vat+(item.price*item.quantity*0.15)
                    }
                    else{
                        total = total+(item.price*item.quantity)
                    }
                }
                
            })
            return {subTotal:subtotal,vat:vat,total:total}
        },
        async getAddress(id){
            await axios.get('/addressBooks/'+id)
            .then( response =>{
                this.address = response.data
                this.loading = false
            })
        },
        async getOrderDriver(){
            await axios.get('/getOrderDetails/'+this.$route.params.id)
            .then( response =>{
                this.orderDriver = response.data
            })
        }
    }
}
</script>