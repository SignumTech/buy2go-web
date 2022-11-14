<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Available Credits</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <table class="table px-2 mt-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Credit name</th>
                        <th>Credit duration in days</th>
                        <th>Created at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="credit,index in credits" :key="index">
                        <td>{{index+1}}</td>
                        <td>{{credit.credit_name}}</td>
                        <td>{{credit.credit_time}} days</td>
                        <td>{{credit.created_at | moment("ddd, MMM Do YYYY")}}</td>
                        <td>
                            <span class="fa fa-trash-alt"></span>
                            <span @click="editCredit(credit)" class="fa fa-edit ms-3"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>   
</template>
<script>
export default {
    data(){
        return{
            credits:{}
        }
    },
    mounted(){
        this.getCredits()
    },
    methods:{
        async getCredits(){
            await axios.get('/credits')
            .then( response =>{
                this.credits = response.data
            })
        },
        async editCredit(){

        }
    }
}
</script>