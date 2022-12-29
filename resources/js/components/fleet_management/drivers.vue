<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Drivers</strong></h5>
    </div>
    <div class="col-md-12">
        <LvProgressBar v-if="loading" mode="indeterminate" color="#011b48" />
        <div class="bg-white rounded-1 shadow-sm p-3">
            <button v-if="permission.addDriver" @click="addDriverModal()" class="btn btn-primary btn-sm float-end shadow-sm text-white"><span class="fa fa-plus"></span> Add Driver</button>
            <table class="table table-sm mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Driver Name</th>
                        <th>Phone Number</th>
                        <th>Lisence Plate</th>
                        <th>Route</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="driver,index in drivers" :key="index">
                        <td>{{index+1}}</td>
                        <td>{{driver.f_name}}-{{driver.l_name}}</td>
                        <td>+{{driver.country_code}}-{{driver.phone_no}}</td>
                        <td>{{driver.l_plate}}</td>
                        <td><p v-for="route,index in driver.routes" :key="index">{{route.route_name}}</p></td>
                        <td class="text-center">
                            <span v-if="permission.updateDriver" @click="editModal(driver)" class="fa fa-edit "></span>
                            <span v-if="driver.canbe_deleted && permission.deleteDriver" @click="deleteDriver(driver.id)"  class="fa fa-trash-alt ms-3"></span>
                        </td>
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
import addDriverModalVue from './addDriverModal.vue'
import editModalVue from './editModal.vue'
import LvProgressBar from 'lightvue/progress-bar';
export default {
    components: {
        LvProgressBar: LvProgressBar
    },
    data(){
        return{
            loading:true,
            drivers:{},
            permission:{},
            paginationData:{}
        }
    },
    mounted(){
        this.$store.dispatch('auth/permissions')
        .then( () =>{
            this.permission = this.$store.state.auth.permissions
        })
        this.getDrivers()
    },
    methods:{
        async deleteDriver(id){
            if(confirm("Are you sure you want to delete the driver?")){
                await axios.delete('/deleteStaff/'+id)
                .then( response =>{
                    this.getDrivers()
                })
            }
            
        },
        async getPage(pageUrl){
            this.loading = true
            await axios.get(pageUrl)
            .then( response => {
                
                this.paginationData = response.data
                this.drivers = response.data.data
                this.loading = false
            })
            .catch( error =>{
                this.loading = false
            })
        },
        async getDrivers(){
            this.loading = true
            await axios.get('/drivers')
            .then( response =>{
                
                this.paginationData = response.data
                this.drivers = response.data.data
                this.loading = false
            })
        },
        addDriverModal(){
            this.$modal.show(
                addDriverModalVue,
                {},
                {height:"auto", width:"500px"},
                {"closed":this.getDrivers}
            )
        },
        editModal(driver){
            this.$modal.show(
                editModalVue,
                {driver:driver, l_plate:driver.routes[0].l_plate},
                {height:"auto", width:"500px"},
                {"closed":this.getDrivers}
            )
        }
    }
}
</script>