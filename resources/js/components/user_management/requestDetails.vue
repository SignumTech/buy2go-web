<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Request Details</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white p-3 rounded-1 shadow-sm">
            <div class="row border-bottom">
                <div class="col-md-3">
                    <h4><strong>{{request.f_name}} {{request.l_name}}</strong></h4>
                    <h5><strong>Balance: {{request.balance}} ETB</strong></h5>
                    <h6>Phone: +251-{{request.phone_no}}</h6>
                </div>
                <div class="col-md-6 text-center align-self-center">
                    <h5><strong>Request Amount</strong></h5>
                    <h5><strong>{{request.amount}} ETB</strong></h5>
                </div>
                <div class="col-md-3 align-self-center">
                    <button v-if="request.request_status == `PAID`" @click="confirmRequest()" class="btn btn-primary form-control"><span class="fa fa-print"></span> PRINT RECIEPT</button>
                    <button v-if="request.request_status == `PENDING`" @click="confirmRequest()" class="btn btn-primary form-control"><span class="fa fa-stamp"></span> CONFIRM REQUEST</button>
                    <h6 v-if="!requestExceeds" class="text-danger mt-2 text-center">{{requestExceeds}}</h6>
                </div>
            </div>
            <div v-if="request.request_status == `PAID`" class="row border-bottom">
                <img class="img img-fluid d-flex m-auto" src="/storage/settings/paid.jpg" alt="" style="height: 200px; width:auto">
            </div>
            <div v-if="request.request_status == `CONFIRMED`" class="row border-bottom">
                <div class="col-md-12 mt-3">
                    <h6 class="text-center">The agent will scan the below QR code to get paid.</h6>
                </div>
                <div class="col-md-12 d-flex justify-content-center p-4">
                    <qr-code :text="request.request_hash"></qr-code>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mt-3">
                    <h6 class="text-center">Request No.</h6>
                    <h5 class="text-center"><strong>{{request.request_no}}</strong></h5>
                </div>
                <div class="col-md-4 mt-3 border-start">
                    <h6 class="text-center">Requested at</h6>
                    <h5 class="text-center"><strong>{{request.created_at | moment("ddd, MMM Do YYYY")}}</strong></h5>
                </div> 
                <div class="col-md-4 mt-3 border-start">
                    <h6 class="text-center">Requested Status</h6>
                    <h5 class="text-center"><strong>{{request.request_status}}</strong></h5>
                </div> 
            </div>
        </div>
    </div>
</div>    
</template>
<script>
export default {
    data(){
        return{
            request:{},
            requestExceeds:null
        }
    },
    mounted(){
        this.getRequest()
    },
    methods:{
        async confirmRequest(){
            await axios.put('/confirmRequest/'+this.$route.params.id)
            .then( response =>{
                this.getRequest()
            })
            .catch( error =>{
                if(error.response.status == 422){
                    this.requestExceeds = error.response.data
                }
            })
        },
        async getRequest(){
            await axios.get('/showPaymentRequest/'+this.$route.params.id)
            .then( response =>{
                this.request = response.data
                this.connect()
            })
        },
        connect(){
            window.Echo.channel('cash_withdrawn.'+this.request.id)
            .listen('CashWithdrawn', (e) => {
                this.getRequest();
                console.log(e);
            });
        },
    }
}
</script>