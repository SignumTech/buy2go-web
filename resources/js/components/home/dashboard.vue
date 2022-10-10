<template>
<div class="row mt-4">
    <div class="col-3">
        <div class="bg-white shadow-sm rounded-1 h-100 p-3">
            <h5 class="border-bottom pb-2 m-0"><i data-feather="dollar-sign"></i> Sales<span class="float-end badge rounded-1 bg-warning text-dark shadow-sm">30 days</span></h5>
            <h3 class="float-right mt-3"><strong>ETB {{salesThirty.sales | numFormat}}</strong></h3>       
            <h6 v-if="salesThirty.type == `INC`" class="m-0 text-success"><span class="fa fa-arrow-up text-success"></span> {{salesThirty.perecentage}}% (30 days)</h6> 
            <h6 v-if="salesThirty.type == `DEC`" class="m-0 text-danger"><span class="fa fa-arrow-down text-danger"></span> {{salesThirty.perecentage}}% (30 days)</h6>
        </div>
    </div>
    <div class="col-3">
        <div class="bg-white shadow-sm rounded-1 h-100 p-3">
            <h5 class="border-bottom pb-2"><i data-feather="shopping-cart"></i> Orders<span class="float-end badge rounded-1 bg-warning text-dark shadow-sm">30 days</span></h5>
            <h3 class="float-right mt-3"><strong>{{ordersThirty.orders | numFormat}}</strong></h3>
            <h6 v-if="ordersThirty.type == `INC`" class="m-0 text-success"><span class="fa fa-arrow-up text-success"></span> {{ordersThirty.perecentage}}% (30 days)</h6> 
            <h6 v-if="ordersThirty.type == `DEC`" class="m-0 text-danger"><span class="fa fa-arrow-down text-danger"></span> {{ordersThirty.perecentage}}% (30 days)</h6>        
        </div>
    </div>
    <div class="col-3">
        <div class="bg-white shadow-sm rounded-1 h-100 p-3">
            <h5 class="border-bottom pb-2"><i data-feather="users"></i> New users<span class="float-end badge rounded-1 bg-warning text-dark shadow-sm">30 days</span></h5>
            <h3 class="float-right mt-3"><strong>{{usersThirty.users}}</strong></h3>
            <h6 v-if="usersThirty.type == `INC`" class="m-0 text-success"><span class="fa fa-arrow-up text-success"></span> {{ordersThirty.perecentage}}% (30 days)</h6> 
            <h6 v-if="usersThirty.type == `DEC`" class="m-0 text-danger"><span class="fa fa-arrow-down text-danger"></span> {{ordersThirty.perecentage}}% (30 days)</h6>                
        </div>
    </div>
    <div class="col-3">
        <div class="bg-white shadow-sm rounded-1 h-100 p-3">
            <h5 class="border-bottom pb-2"><i data-feather="dollar-sign"></i> Total Revenue<span class="float-end badge rounded-1 bg-warning text-dark shadow-sm">Annual</span></h5>
            <h3 class="float-right mt-3"><strong>ETB {{revenueYear.sales | numFormat}}</strong></h3>
            <h6 v-if="revenueYear.type == `INC`" class="m-0 text-success"><span class="fa fa-arrow-up text-success"></span> {{revenueYear.perecentage}}% (last year)</h6>      
            <h6 v-if="revenueYear.type == `DEC`" class="m-0 text-danger"><span class="fa fa-arrow-down text-danger"></span> {{revenueYear.perecentage}}% (last year)</h6>   
        </div>
    </div>
    <div class="col-md-8 mt-3">
        <div class="bg-white shadow-sm rounded-1 p-3">
            <linechart v-if="!lineLoading" :chartData="chartSalesData" :options="chartSalesOptions"></linechart>
        </div>
    </div>
    <div class="col-md-4 mt-3">
        <div class="bg-white shadow-sm rounded-1 p-3">
            <doughnutchart v-if="!pieLoading" :chartData="chartOrdersData" :options="chartOrdersOptions"></doughnutchart>
        </div>
    </div>
</div>
</template>
<script>
import linechart from '../charts/lineChart'
import doughnutchart from '../charts/doughnutChart.vue';
export default {
    components:{
        linechart,
        doughnutchart
    },
    mounted(){
        
        this.getSalesThirty();
        this.getOrdersThirty();
        this.getUsersThirty();
        this.getRevenueYear();
        this.getSalesSeven();
        this.getOrdersSeven();
        feather.replace();
    },
    data(){
        return{
            lineLoading:true,
            pieLoading:true,
            salesThirty:{},
            ordersThirty:{},
            usersThirty:{},
            revenueYear:{},
            chartSalesData: {
                labels: [],
                datasets: [
                {
                    label: 'Sales last 7 days',
                    backgroundColor: '#253cbf7a',
                    data: [],
                }
                ]
            },
            chartSalesOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            chartOrdersData: {
                labels: [],
                datasets: [
                {
                    label: 'Sales last 9 months',
                    backgroundColor: ['#fe3d3f7a','#c53cbf7a','#05dfba7a','#253dcd4a'],
                    data: [],
                    
                }
                ]
            },
            chartOrdersOptions: {
                responsive: true,
                maintainAspectRatio: false
            }
        }
    },
    methods:{
        async getSalesThirty(){
            await axios.get('/salesThirty')
            .then( response =>{
                this.salesThirty = response.data
            })
        },
        async getOrdersThirty(){
            await axios.get('/ordersThirty')
            .then( response =>{
                this.ordersThirty = response.data
            })
        },
        async getUsersThirty(){
            await axios.get('/usersThirty')
            .then( response =>{
                this.usersThirty = response.data
            })
        },
        async getRevenueYear(){
            await axios.get('/revenueYear')
            .then( response =>{
                this.revenueYear = response.data
            })
        },
        async getSalesSeven(){
            this.lineLoading = true
            await axios.get('/salesSeven')
            .then( response =>{
                this.chartSalesData.labels = response.data.labels
                this.chartSalesData.datasets[0].data = response.data.data
                this.lineLoading = false
            })
        },
        async getOrdersSeven(){
            this.pieLoading = true
            await axios.get('/ordersSeven')
            .then( response =>{
                this.chartOrdersData.labels = response.data.labels
                this.chartOrdersData.datasets[0].data = response.data.data
                this.pieLoading = false
            })
        },
    }
}
</script>