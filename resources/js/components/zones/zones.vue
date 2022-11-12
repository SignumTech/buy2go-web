<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Zones</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <button  @click="addZonesModal()" class="btn btn-primary btn-sm float-end shadow-sm text-white"><span class="fa fa-plus"></span> Add Zone</button>
            <table class="table px-2 mt-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>City</th>
                        <th>Subcity</th>
                        <th>area</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="zone,index in zones" :key="index">
                        <td>{{index+1}}</td>
                        <td>{{zone.city}}</td>
                        <td>{{zone.subcity}}</td>
                        <td><h6 @click="viewArea(zone.route)" class="m-0" style="cursor:pointer"><strong>View on map <span class="fa fa-external-link-alt"></span></strong></h6></td>
                        <td class="text-center">
                            <span class="fa fa-trash-alt"></span>
                            <span @click="editZonesModal(zone)" class="fa fa-edit ms-3"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
                <ul class="pagination justify-content-center">
                    <li v-for="pd,index in paginationData" :key="index" :class="(pd.active)?`page-item active text-white`:`page-item`">
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
import addZonesVue from './addZones.vue';
import editZonesVue from './editZones.vue';
import viewAreaModalVue from './viewAreaModal.vue';
export default {
    data(){
        return{
            zones:{},
            paginationData:{}
        }
    },
    mounted(){
        this.getZones();
    },
    methods:{
        async getPage(pageUrl){
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data.links
                this.zones = response.data.data
            })
        },
        editZonesModal(zone){
            this.$modal.show(
                editZonesVue,
                {"zone":zone},
                {height:"auto",width:"800px"},
                {"closed":this.getZones}
            )
        },
        addZonesModal(){
            this.$modal.show(
                addZonesVue,
                {},
                {height:"auto",width:"800px"},
                {"closed":this.getZones}
            )
        },
        async getZones(){
            await axios.get('/getZones')
            .then( response =>{
                this.zones = response.data.data
                this.paginationData = response.data.links
            })
        },
        viewArea(route){
            this.$modal.show(
                viewAreaModalVue,
                {"route":route},
                {width:"800px",height:"auto"}
            )
        }
    }
}
</script>