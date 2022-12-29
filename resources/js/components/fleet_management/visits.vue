<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Visits</strong></h5>
    </div>
    <div class="col-md-12">
        <LvProgressBar v-if="loading" mode="indeterminate" color="#011b48" />
        <div class="bg-white rounded-1 shadow-sm p-3">
            <button v-if="permission.addVisit" @click="addVisitModal()" class="btn btn-primary btn-sm float-end shadow-sm text-white"><span class="fa fa-plus"></span> Add Visit</button>
            <table class="table table-sm mt-3">
                <thead>
                    <tr>
                        <th>Visit #</th>
                        <th>Route</th>
                        <th>Driver</th>
                        <th>Status</th>
                        <th>Assigned</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="visit,index in visits" :key="index">
                        <td>{{visit.visit_no}}</td>
                        <td>{{visit.route_name}}</td>
                        <td>{{visit.f_name}} {{visit.l_name}}</td>
                        <td>{{visit.visit_status}}</td>
                        <td>{{visit.created_at | moment("MMM Do YYYY")}}</td>
                        <td>
                            <span v-if="visit.visit_status == `PENDING_CONFIRMATION` && permission.updateVisit" @click="editVisitModal(visit)" class="fa fa-edit"></span>
                            <span v-if="visit.visit_status == `PENDING_CONFIRMATION` && permission.deleteVisit" @click="deleteVisit(visit.id)" class="fa fa-trash-alt"></span>
                        </td>
                        <th>
                            <router-link :to="`/visitDetails/`+visit.id"><span class="fa fa-external-link-alt"></span> Visit Details</router-link>
                        </th>
                    </tr>
                </tbody>
            </table>
            <nav v-if="paginationData.total > paginationData.per_page" aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
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
import addVisitModalVue from './addVisitModal.vue'
import editVisitModalVue from './editVisitModal.vue'
import LvProgressBar from 'lightvue/progress-bar';
export default {
        components: {
        LvProgressBar: LvProgressBar
    },
    data(){
        return{
            loading:true,
            visits:{},
            permission:{},
            locations:{
                lat:9.012982,
                lng:38.9485312
            },
            paginationData:{},
        }
    },
    mounted(){
        this.$store.dispatch('auth/permissions')
        .then( () =>{
            this.permission = this.$store.state.auth.permissions
        })
        this.getVisits()
    },
    methods:{
        editVisitModal(visit){
            this.$modal.show(
                editVisitModalVue,
                {visit:visit},
                {height:"auto", width:"500px"},
                {"closed":this.getVisits}
            )
        },
        async deleteVisit(id){
            if(confirm("Are you sure you want to delete this visit?")){
                await axios.delete('/visits/'+id)
                .then( response =>{
                    this.getVisits()
                })
            }
            
        },
        async getVisits(){
            this.loading = true
            await axios.get('/visits')
            .then( response =>{
                this.paginationData = response.data
                this.visits = response.data.data;
                this.loading = false
            })
        },
        async getPage(pageUrl){
            this.loading = true
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data
                this.visits = response.data.data;
                this.loading = false
            })
        },
        addVisitModal(){
            this.$modal.show(
                addVisitModalVue,
                {},
                {height:"auto", width:"500px"},
                {"closed":this.getVisits}
            )
        }
    }
}
</script>