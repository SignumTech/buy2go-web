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
                        <th>route</th>
                        <th>area</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="zone,index in zones" :key="index">
                        <td>{{index+1}}</td>
                        <td>{{zone.city}}</td>
                        <td>{{zone.subcity}}</td>
                        <td></td>
                        <td><h6 @click="viewArea(zone.route)" class="m-0" style="cursor:pointer"><strong>View on map <span class="fa fa-external-link-alt"></span></strong></h6></td>
                        <td class="text-center">
                            <router-link to="#" class="float-end "><span class="fa fa-trash-alt"></span></router-link>
                            <router-link to="`/admin/editProduct/`" class="float-end me-3"><span class="fa fa-edit"></span></router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>    
</template>
<script>
import addZonesVue from './addZones.vue';
import viewAreaModalVue from './viewAreaModal.vue';
export default {
    data(){
        return{
            zones:{}
        }
    },
    mounted(){
        this.getZones();
    },
    methods:{
        addZonesModal(){
            this.$modal.show(
                addZonesVue,
                {},
                {height:"auto",width:"800px"}
            )
        },
        async getZones(){
            await axios.get('/getZones')
            .then( response =>{
                this.zones = response.data
            })
        },
        viewArea(){
            this.$modal.show(
                viewAreaModalVue,
                {},
                {width:"500px",height:"auto"}
            )
        }
    }
}
</script>