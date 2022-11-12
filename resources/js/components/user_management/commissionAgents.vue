<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Comission Agents</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white p-3 rounded-1 shadow-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Agent name</th>
                        <th>Phone Number</th>
                        <th>Balance</th>
                        <th>Registration Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="agent,index in agents" :key="index">
                        <td>{{index+1}}</td>
                        <td>{{agent.f_name}} {{agent.l_name}}</td>
                        <td>{{agent.phone_no}}</td>
                        <td>{{agent.balance}}</td>
                        <td>{{agent.created_at | moment("MMM Do YYYY")}}</td>
                        <td><router-link :to="`/agentDetail/`+agent.user_id">Agent Details <span class="fa fa-external-link-alt"></span></router-link></td>
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
            agents:{},
            paginationData:{}
        }
    },
    mounted(){
        this.getAgents()
    },
    methods:{
        async getPage(pageUrl){
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data
                this.agents = response.data.data;
            })
        },
        async getAgents(){
            await axios.get('/getAgents')
            .then( response =>{
                this.paginationData = response.data
                this.agents = response.data.data;
            })
        }
    }
}
</script>