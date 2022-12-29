<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Orders</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-2 shadow-sm">
            <div class="row">
                <div class="col-md-2">
                    <label for="">Order No.</label>
                    <input v-model="queryData.order_no" type="text" class="form-control rounded-1" placeholder="Order Number">
                    
                </div>
                <div class="col-md-2">
                    <label for="">Status</label>
                    <select v-model="queryData.order_status" class="form-select rounded-1">
                        <option :value="null">All statuses</option>
                        <option value="PROCESSING">Processing</option>
                        <option value="PENDING_CONFIRMATION">Pending Confirmation</option>
                        <option value="PENDING_PICKUP">Pending Pickup</option>
                        <option value="SHIPPED">Shipped</option>
                        <option value="DELIVERED">Delivered</option>
                    </select>
                </div>
                <div class="col-md-2 align-self-center">
                    <label for="">Payment Status</label>
                    <select v-model="queryData.payment_status" class="form-select rounded-1">
                        <option :value="null">All statuses</option>
                        <option value="PAID">Paid</option>
                        <option value="UNPAID">Unpaid</option>
                    </select>
                </div>
                <div class="col-md-2 align-self-center">
                    <label for="">Payment Methods</label>
                    <select v-model="queryData.payment_method" class="form-select rounded-1">
                        <option :value="null">All methods</option>
                        <option value="Cash on delivery">Cash on delivery</option>
                        <option value="Credit">Credit</option>
                        <option value="Online Gateway">Online gateway</option>
                    </select>
                </div>
                <div class="col-md-2 align-self-end">
                    <button @click="filterOrders()" class="btn btn-success form-control rounded-1"><span class="fa fa-filter"></span> Filter</button>
                </div>
                <div class="col-md-2 align-self-end">
                    <form action="#" @submit.prevent="exportOrders">
                        <button type="submit" class="btn btn-primary form-control rounded-1"><span class="fa fa-file-export"></span> Export</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white shadow-sm p-2">
            <div class="row ms-0 me-0">
                <div @click="getAllOrders()" :class="(active==`all`)?`col-md-2 p-2 border-bottom border-dark border-3`:`col-md-2 p-2 orders-hover rounded-1`" style="cursor:pointer">
                    <h6 class="text-center m-0"><span class="fa fa-shopping-bag"></span> All Ordered</h6>
                </div>
                <div @click="getProcessing()" :class="(active==`PROCESSING`)?`col-md-2 p-2 orders-hover border-bottom border-dark border-3`:`col-md-2 p-2 orders-hover rounded-1`" style="cursor:pointer">
                    <h6 class="text-center m-0"><span class="fa fa-cart-plus"></span> Processing</h6>
                    
                </div>
                <div @click="getPendingConfirmation()" :class="(active==`PENDING_CONFIRMATION`)?`col-md-2 p-2 orders-hover border-bottom border-dark border-3`:`col-md-2 p-2 orders-hover rounded-1`" style="cursor:pointer">
                    <h6 class="text-center m-0"><span class="fa fa-user-plus"></span> Pending Confirmation</h6>
                </div>
                <div @click="getPendingPickup()" :class="(active==`PENDING_PICKUP`)?`col-md-2 p-2 orders-hover border-bottom border-dark border-3`:`col-md-2 p-2 orders-hover rounded-1`" style="cursor:pointer">
                    <h6 class="text-center m-0"><span class="fa fa-truck-loading"></span> Pending Pickup</h6>
                </div> 
                <div @click="getShipped()" :class="(active==`SHIPPED`)?`col-md-2 p-2 orders-hover border-bottom border-dark border-3`:`col-md-2 p-2 orders-hover rounded-1`" style="cursor:pointer">
                    <h6 class="text-center m-0"><span class="fa fa-shipping-fast"></span> Shipped</h6>
                    
                </div>
                <div @click="getDelivered()" :class="(active==`DELIVERED`)?`col-md-2 p-2 orders-hover border-bottom border-dark border-3`:`col-md-2 p-2 orders-hover rounded-1`" style="cursor:pointer">
                    <h6 class="text-center m-0"><span class="fa fa-box-open"></span> Delivered</h6>
                </div>           
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <LvProgressBar v-if="loading" mode="indeterminate" color="#011b48" />
        <div class="bg-white shadow-sm p-2">
            <table class="table mt-2">
                <thead>
                    <tr class="text-center">
                        <th>Order No</th>
                        <th>Order date</th>
                        <th>Total</th>
                        <th>No. of items</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Payment Method</th>
                        <th>Order Type</th>
                        <th></th>
                    </tr>                        
                </thead>

                <tbody>
                    <tr v-for="order in orders" :key="order.id" class="border-bottom text-center align-middle">
                        <td>
                            {{order.order_no}}
                        </td>
                        <td>{{order.created_at | moment("MMM Do YYYY H:m:s a")}}</td>
                        
                        <td>{{order.total | numFormat}} ETB</td>
                        <td>{{order.no_items}} items</td>
                        <td><statusesVue :status="order.order_status"></statusesVue></td>
                        <td>{{order.payment_status}}</td>
                        <td>{{order.payment_method}}</td>
                        <td>{{order.order_type}}</td>
                        <td><router-link :to="`/orderDetails/`+order.id">Order Details <span class="fa fa-external-link-alt"></span></router-link></td>
                    </tr>                        
                </tbody>
            </table>
            <nav v-if="!filtered && orderData.total > orderData.per_page" aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
                <ul class="pagination justify-content-center">
                    <li v-for="pd,index in paginationData" :key="index" :class="(pd.active)?`page-item active text-white`:`page-item`">
                        <a class="page-link" @click="getPage(pd.url)" aria-label="Previous">
                            <span :class="(pd.active)?`text-white`:``" aria-hidden="true" v-html="pd.label"></span>
                        </a>
                    </li>
                </ul>
            </nav>
            <nav v-if="filtered && orderData.total > orderData.per_page" aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
                <ul class="pagination justify-content-center">
                    <li v-for="pd,index in paginationData" :key="index" :class="(pd.active)?`page-item active text-white`:`page-item`">
                        <a class="page-link" @click="getFilteredPage(pd.url)" aria-label="Previous">
                            <span :class="(pd.active)?`text-white`:``" aria-hidden="true" v-html="pd.label"></span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>  
</template>

<script>
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import statusesVue from './statuses.vue'
import LvProgressBar from 'lightvue/progress-bar';
export default {
    components:{
            PulseLoader,
            statusesVue,
            LvProgressBar: LvProgressBar
        },
    data(){
        return{
            active:'all',
            orders:{},
            paginationData:{},
            loading:true,
            queryData:{
                order_no:null,
                order_status:null,
                payment_status:null,
                payment_method:null,
            },
            range:{},
            filtered:false,
            orderData:{}
        }
    },
    mounted(){
        this.getAllOrders()
    },
    methods:{
        async exportOrders(){
            await axios.post('/exportOrders', this.queryData, {responseType: 'blob'})
            .then( response =>{
                const href = URL.createObjectURL(response.data);

                // create "a" HTML element with href to file & click
                const link = document.createElement('a');
                link.href = href;
                link.setAttribute('download', 'orders'+Date.now()+'.xlsx'); 
                document.body.appendChild(link);
                link.click();

                // clean up "a" element & remove ObjectURL
                document.body.removeChild(link);
                URL.revokeObjectURL(href);
            })
        },
        async getPage(pageUrl){
            this.loading = true
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data.links
                this.orders = response.data.data
                this.loading = false
            })
            .catch( error =>{
                this.loading = false
            })
        },
        async getFilteredPage(pageUrl){
            this.loading = true
            await axios.post(pageUrl, this.queryData)
            .then( response => {
                this.orderData = response.data 
                this.paginationData = response.data.links
                this.orders = response.data.data
                this.loading = false
            })
            .catch( error =>{
                this.loading = false
            })
        },
        async filterOrders(){
            this.active = ''
            this.loading = true
            this.filtered = true
            await axios.post('/filterOrders', this.queryData)
            .then( response =>{
                this.orders = response.data.data
                this.paginationData = response.data.links
                this.loading = false
            })
        },
        connect(){

        },
        async getAllOrders(){
            this.active = 'all'
            this.loading = true
            this.filtered = false
            await axios.get('/orders')
            .then( response =>{
                this.orderData = response.data 
                this.orders = response.data.data
                this.paginationData = response.data.links
                this.loading = false
            })
        },
        async getPendingConfirmation(){
            this.active = 'PENDING_CONFIRMATION'
            this.loading = true
            this.filtered = false
            await axios.get('/getPendingConfirmation')
            .then( response =>{
                this.orderData = response.data 
                this.orders = response.data.data
                this.paginationData = response.data.links
                this.loading = false
            })
        },
        async getPendingPickup(){
            this.active = 'PENDING_PICKUP'
            this.loading = true
            this.filtered = false
            await axios.get('/getPendingPickup')
            .then( response =>{
                this.orderData = response.data 
                this.orders = response.data.data
                this.paginationData = response.data.links
                this.loading = false
            })
        },
        async getProcessing(){
            this.active = 'PROCESSING'
            this.loading = true
            this.filtered = false
            await axios.get('/getProcessing')
            .then( response =>{
                this.orderData = response.data 
                this.orders = response.data.data
                this.paginationData = response.data.links
                this.loading = false
            })
        },
        async getShipped(){
            this.active = 'SHIPPED'
            this.loading = true
            this.filtered = false
            await axios.get('/getShipped')
            .then( response =>{
                this.orderData = response.data 
                this.orders = response.data.data
                this.paginationData = response.data.links
                this.loading = false
            })
        },
        async getDelivered(){
            this.active = 'DELIVERED'
            this.loading = true
            this.filtered = false
            await axios.get('/getDelivered')
            .then( response =>{
                this.orderData = response.data 
                this.orders = response.data.data
                this.paginationData = response.data.links
                this.loading = false
            })
        }
    }
}
</script>
    