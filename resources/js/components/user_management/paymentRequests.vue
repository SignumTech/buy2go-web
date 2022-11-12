<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Payment Requests</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white p-3 rounded-1 shadow-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Agent name</th>
                        <th>Request Number</th>
                        <th>Phone Number</th>
                        <th>Request Amount</th>
                        <th>Requested at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="request,index in requests" :key="index">
                        <td>{{index+1}}</td>
                        <td>{{request.request_no}}</td>
                        <td>{{request.f_name}} {{request.l_name}}</td>
                        <td>{{request.phone_no}}</td>
                        <td>{{request.amount}}</td>
                        <td>{{request.created_at | moment("MMM Do YYYY")}}</td>
                        <td><router-link :to="`/agentDetail/`+request.user_id">Agent Details <span class="fa fa-external-link-alt"></span></router-link></td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
                <ul class="pagination justify-content-center">
                    <li v-for="pd,index in paginationData.links" :key="index" :class="(pd.active)?`page-item active text-white`:`page-item`">
                        <a class="page-link" @click="getPage(pd.url)" aria-label="Previous">
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
export default {
    data(){
        return{
            requests:{},
        }
    },
    mounted(){
        this.getRequests()
    },
    methods:{
        async getPage(pageUrl){
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data
                this.requests = response.data.data;
            })
        },
        async getRequests(){
            await axios.get('/getRequests')
            .then( response =>{
                this.paginationData = response.data
                this.requests = response.data.data;
            })
        }
    }
}
</script>