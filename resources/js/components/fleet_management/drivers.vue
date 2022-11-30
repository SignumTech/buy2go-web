<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Drivers</strong></h5>
    </div>
    <div class="col-md-12">
        <div class="bg-white rounded-1 shadow-sm p-3">
            <button @click="addDriverModal()" class="btn btn-primary btn-sm float-end shadow-sm text-white"><span class="fa fa-plus"></span> Add Driver</button>
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
                        <td>{{driver.f_name}} {{driver.l_name}}</td>
                        <td>+{{driver.phone_no}}</td>
                        <td>{{driver.l_plate}}</td>
                        <td><p v-for="route,index in driver.routes" :key="index">{{route.route_name}}</p></td>
                        <td class="text-center">
                            <span @click="editModal(driver)" class="fa fa-edit "></span>
                            <span  class="fa fa-trash-alt ms-3"></span>
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
export default {
    data(){
        return{
            drivers:{},
            paginationData:{}
        }
    },
    mounted(){
        this.getDrivers()
    },
    methods:{
        async getPage(pageUrl){
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data
                this.drivers = response.data.data
            })
        },
        async getDrivers(){
            await axios.get('/drivers')
            .then( response =>{
                this.paginationData = response.data
                this.drivers = response.data.data
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