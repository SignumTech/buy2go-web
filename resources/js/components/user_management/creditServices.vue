<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Available Credits</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <button  @click="addCreditsModal()" class="btn btn-primary btn-sm float-end shadow-sm text-white"><span class="fa fa-plus"></span> Add Credits</button>
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
                            <span @click="editCreditsModal(credit)" class="fa fa-edit ms-3"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>   
</template>
<script>
import AddCreditModal from './addCreditModal.vue'
import editCreditModalVue from './editCreditModal.vue'

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
        addCreditsModal(){
            this.$modal.show(
                AddCreditModal,
                {},
                {height:'auto', width:'500px'},
                {'closed':this.getCredits}
            )
        },
        editCreditsModal(credit){
            this.$modal.show(
                editCreditModalVue,
                {credits:credit},
                {height:'auto', width:'500px'},
                {'closed':this.getCredits}
            )
        }
    }
}
</script>