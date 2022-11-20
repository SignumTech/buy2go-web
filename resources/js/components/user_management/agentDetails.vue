<template>
<div class="row mt-4 mx-4">
    <div class="col-md-4">
        <div class="bg-white rounded-1 shadow-sm p-4">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="rounded-circle overflow-hidden d-block m-auto" style="width:100px; height:100px">
                        <img class="img img-fluid" src="/storage/settings/user_placeholder.png" alt="">
                    </div>
                    <h5 class="text-center mt-3"><strong>{{agent.agent_details.f_name}} {{agent.agent_details.l_name}}</strong></h5>
                    <h6 class="text-center">+251-{{agent.agent_details.phone_no}}</h6>
                    <h4 class="text-center mt-3"><strong>Balance : {{agent.agent_balance | numFormat}} ETB </strong></h4>
                    <h4 class="text-center mt-3"></h4>
                    <button @click="payAgent(agent)" class="btn btn-primary px-4 py-1 rounded-1 btn-outline text-center"><span class="fa fa-hand-holding-usd"></span> Pay Agent</button>
                    <hr>
                </div>
                <div class="col-md-12">
                    <h6 class="mt-3"><strong>Last Order</strong></h6>
                    <h6>{{agent.last_order.order_no}} - {{agent.last_order.created_at | moment("MMM Do YYYY")}}</h6>
                    <h6 class="mt-3"><strong>Average Order Value</strong></h6>
                    <h6>{{agent.average_order | numFormat}} ETB</h6>
                    <h6 class="mt-3"><strong>Agent Registered</strong></h6>
                    <h6>{{agent.agent_details.created_at | moment("MMM Do YYYY")}}</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div v-if="agent.agent_details.shop_status === `NOT_VERIFIED`" class="bg-warning rounded-1 shadow-sm">
            <div class="row mx-0 border-bottom p-3">
                <div class="col-md-6 align-self-center">
                    <h5 class="mb-0"><span class="fa fa-exclamation-triangle"></span> This Agent is not verified!</h5>
                </div>
                <div class="col-md-6">
                    <button @click="verifyAgent()" class="btn btn-success btn-sm float-end shadow-sm text-white"><span class="fa fa-check-circle"></span> Verify Agent</button>
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
                            <td>{{order.items_count}}</td>
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
import payModalVue from './payModal.vue'
export default {
    data(){
        return{
            agent:{
                agent_details:{},
                last_order:{}
            },
            orders:{},
            locations:{}
        }
    },
    mounted(){
        this.getagent()
        this.getAgentOrders()
    },
    methods:{
        async verifyAgent(){
            var check = confirm("Are you sure you want to verifiy this agent?")
            if(check){
                await axios.put('/verfiyAgent/'+this.$route.params.id)
                .then( response =>{
                    this.getagent();    
                })
            }
            
        },
        payAgent(agent){
            this.$modal.show(
                payModalVue,
                {"agent":agent},
                {height:"auto", width:"500px"},
                {"closed": this.getAgent}
            )
        },
        async getagent(){
            await axios.get('/agentDetails/'+this.$route.params.id)
            .then( response =>{
                this.agent = response.data
            })
        },
        async getAgentOrders(){
            await axios.get('/agentOrders/'+this.$route.params.id)
            .then( response =>{
                this.orders = response.data
            })
        }
    }
}
</script>