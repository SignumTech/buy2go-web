<template>
<div class="row mt-4">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12">
                <div class="bg-white shadow-sm rounded-1 h-100 p-3">
                    <h5 class="border-bottom pb-2 m-0"><i data-feather="dollar-sign"></i> Sales<span class="float-end badge rounded-1 bg-warning text-dark shadow-sm">Today</span></h5>
                    <h3 class="float-right mt-3"><strong>ETB {{salesToday.sales | numFormat}}</strong></h3>       
                    <h6 v-if="salesToday.type == `INC`" class="m-0 text-success"><span class="fa fa-arrow-up text-success"></span> {{salesToday.perecentage}}% (yesterday)</h6> 
                    <h6 v-if="salesToday.type == `DEC`" class="m-0 text-danger"><span class="fa fa-arrow-down text-danger"></span> {{salesToday.perecentage}}% (yesterday)</h6>
                </div>
            </div>
            <div class="col-md-12 mt-2">
                <div class="bg-white shadow-sm rounded-1 h-100 p-3">
                    <h5 class="border-bottom pb-2"><i data-feather="shopping-cart"></i> New Orders<span class="float-end badge rounded-1 bg-warning text-dark shadow-sm">Today</span></h5>
                    <h3 class="float-right mt-3"><strong>{{ordersToday.orders | numFormat}}</strong></h3>
                    <h6 v-if="ordersToday.type == `INC`" class="m-0 text-success"><span class="fa fa-arrow-up text-success"></span> {{ordersToday.perecentage}}% (yesterday)</h6> 
                    <h6 v-if="ordersToday.type == `DEC`" class="m-0 text-danger"><span class="fa fa-arrow-down text-danger"></span> {{ordersToday.perecentage}}% (yesterday)</h6>        
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12">
                <div class="bg-white shadow-sm rounded-1 p-1">
                    <div class="row">
                        <div class="col-md-3 text-center align-self-center">
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" v-model="formData.reportType" value="daily" type="radio">
                                <label class="form-check-label" for="inlineRadio1">Daily</label>
                            </div>
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" v-model="formData.reportType" value="monthly" type="radio">
                                <label class="form-check-label" for="inlineRadio2">Monthly</label>
                            </div>
                        </div>
                        <div v-if="formData.reportType==`daily`" class="col-md-3">
                            <input v-model="formData.from" type="date" class="form-control">
                        </div>
                        <div v-if="formData.reportType==`daily`" class="col-md-3">
                            <input v-model="formData.to" type="date" class="form-control">
                        </div>
                        <div v-if="formData.reportType==`monthly`" class="col-md-3">
                            <input v-model="formData.from" type="month" class="form-control">
                        </div>
                        <div v-if="formData.reportType==`monthly`" class="col-md-3">
                            <input v-model="formData.to" type="month" class="form-control">
                        </div>
                        <div class="col-md-3 align-self-center">
                            <button @click="salesReport()" class="btn btn-primary btn-sm"><span class="fa fa-chart-bar"></span> Generate Report</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-2">
                <div class="bg-white shadow-sm rounded-1 p-3">
                    <linechart v-if="!lineLoading" :chartData="chartSalesData" :options="chartSalesOptions"></linechart>
                </div>
            </div>
        </div>
    </div>

</div>    
</template>
<script>
import linechart from '../charts/lineChart.vue'
export default {
    components:{
        linechart
    },
    data(){
        return{
            lineLoading:false,
            salesToday:{},
            ordersToday:{},
            formData:{
                reportType:"daily",
                from:"",
                to:""
            },
            chartSalesData: {
                labels: [],
                datasets: [
                {
                    label: 'Sales',
                    backgroundColor: '#253cbf7a',
                    data: [],
                }
                ]
            },
            chartSalesOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
        }
    },
    mounted(){
        this.getSalesToday()
        this.getOrdersToday()
        this.getSalesSeven()
    },
    methods:{
        async salesReport(){
            this.lineLoading = true
            await axios.post('/salesReport', this.formData)
            .then( response =>{
                this.chartSalesData.labels = response.data.labels
                this.chartSalesData.datasets[0].data = response.data.data
                this.lineLoading = false
            })
        },
        async getSalesToday(){
            await axios.get('/salesToday')
            .then( response =>{
                this.salesToday = response.data
            })
        },
        async getOrdersToday(){
            await axios.get('/ordersToday')
            .then( response =>{
                this.ordersToday = response.data
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
    }
}
</script>