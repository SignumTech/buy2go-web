<template>
<div class="row mt-4">
    <div class="col-3">
        <div class="bg-white shadow-sm rounded-1 h-100 p-3">
            <h5 class="border-bottom pb-2 m-0"><i data-feather="dollar-sign"></i> Sales<span class="float-end badge rounded-1 bg-warning text-dark shadow-sm">30 days</span></h5>
            <h3 class="float-right mt-3"><strong>ETB 215,000</strong></h3>       
            <h6 class="m-0 text-success"><span class="fa fa-arrow-up text-success"></span> 20% (30 days)</h6> 
        </div>
    </div>
    <div class="col-3">
        <div class="bg-white shadow-sm rounded-1 h-100 p-3">
            <h5 class="border-bottom pb-2"><i data-feather="shopping-cart"></i> Orders<span class="float-end badge rounded-1 bg-warning text-dark shadow-sm">30 days</span></h5>
            <h3 class="float-right mt-3"><strong>2,150</strong></h3>
            <h6 class="m-0 text-danger"><span class="fa fa-arrow-down text-danger"></span> 2% (30 days)</h6>        
        </div>
    </div>
    <div class="col-3">
        <div class="bg-white shadow-sm rounded-1 h-100 p-3">
            <h5 class="border-bottom pb-2"><i data-feather="users"></i> New users<span class="float-end badge rounded-1 bg-warning text-dark shadow-sm">30 days</span></h5>
            <h3 class="float-right mt-3"><strong>1,560</strong></h3>
            <h6 class="m-0 text-success"><span class="fa fa-arrow-up text-success"></span> 10% (30 days)</h6>         
        </div>
    </div>
    <div class="col-3">
        <div class="bg-white shadow-sm rounded-1 h-100 p-3">
            <h5 class="border-bottom pb-2"><i data-feather="dollar-sign"></i> Total Revenue<span class="float-end badge rounded-1 bg-warning text-dark shadow-sm">Annual</span></h5>
            <h3 class="float-right mt-3"><strong>ETB 3,450,000</strong></h3>
            <h6 class="m-0 text-success"><span class="fa fa-arrow-up text-success"></span> 30% (last year)</h6>         
        </div>
    </div>
    <div class="col-md-8 mt-3">
        <div class="bg-white shadow-sm rounded-1 p-3">
            <linechart :chartData="chartData" :options="chartOptions"></linechart>
        </div>
    </div>
    <div class="col-md-4 mt-3">
        <div class="bg-white shadow-sm rounded-1 p-3">
            <doughnutchart :chartData="chartDataDough" :options="chartOptionsDough"></doughnutchart>
        </div>
    </div>
</div>
</template>
<script>
import linechart from '../charts/linechart.vue'
import doughnutchart from '../charts/doughnutChart.vue';
export default {
    components:{
        linechart,
        doughnutchart
    },
    mounted(){
        feather.replace();
    },
    data(){
        return{
            chartData: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
                datasets: [
                {
                    label: 'Sales last 9 months',
                    backgroundColor: '#253cbf7a',
                    data: [200,258,275,341,325,350,245,324,290],
                }
                ]
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            chartDataDough: {
                labels: ["Processing", "Unpaid", "Shipped", "Delivered"],
                datasets: [
                {
                    label: 'Sales last 9 months',
                    backgroundColor: ['#fe3d3f7a','#c53cbf7a','#05dfba7a','#253dcd4a'],
                    data: [200,258,275,341],
                    
                }
                ]
            },
            chartOptionsDough: {
                responsive: true,
                maintainAspectRatio: false
            }
        }
    }
}
</script>