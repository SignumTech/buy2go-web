<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Zones</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-2 shadow-sm">
            <div class="row">
                <div class="col-md-2">
                    <label for="">Zone Name</label>
                    <input v-model="queryData.zone_name" type="text" class="form-control rounded-1" placeholder="Zone Name">
                </div>
                <div class="col-md-2">
                    <label for="">Sub city</label>
                    <select v-model="queryData.sub_city_id" class="form-select rounded-1">
                        <option :value="null">All sub cities</option>
                        <option v-for="subCity,index in subCities" :key="index" :value="subCity.id">{{subCity.sub_city_name}}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">City</label>
                    <select v-model="queryData.city_id" class="form-select rounded-1">
                        <option :value="null">All Cities</option>
                        <option v-for="city,index in cities" :key="index" :value="city.id">{{city.city_name}}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">Country</label>
                    <select v-model="queryData.country_id" class="form-select rounded-1">
                        <option :value="null">All Countries</option>
                        <option v-for="country,index in countries" :key="index" :value="country.id">{{country.country_name}}</option>
                    </select>
                </div>
                <div class="col-md-2 align-self-end">
                    <button @click="filterZones()" class="btn btn-success form-control rounded-1"><span class="fa fa-filter"></span> Filter</button>
                </div>
                <div class="col-md-2 align-self-end">
                    <form action="#" @submit.prevent="exportZones">
                        <button type="submit" class="btn btn-primary form-control rounded-1"><span class="fa fa-file-export"></span> Export</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <button  @click="addZonesModal()" class="btn btn-primary btn-sm float-end shadow-sm text-white"><span class="fa fa-plus"></span> Add Zone</button>
            <table class="table px-2 mt-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Zone</th>
                        <th>Subcity</th>
                        <th>City</th>
                        <th>Country</th>
                        <th>area</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="zone,index in zones" :key="index">
                        <td>{{index+1}}</td>
                        <td>{{zone.zone_name}}</td>
                        <td>{{zone.sub_city_name}}</td>
                        <td>{{zone.city_name}}</td>
                        <td>{{zone.country_name}}</td>
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
            paginationData:{},
            countries:{},
            cities:{},
            subCities:{},
            queryData:{
                zone_name:null,
                sub_city_id:null,
                city_id:null,
                country_id:null
            }
        }
    },
    mounted(){
        this.getZones();
        this.getCities();
        this.getSubCities();
        this.getCountries();
    },
    methods:{
    async exportZones(){
        await axios.post('/exportZones', this.queryData, {responseType: 'blob'})
        .then( response =>{
            const href = URL.createObjectURL(response.data);

            // create "a" HTML element with href to file & click
            const link = document.createElement('a');
            link.href = href;
            link.setAttribute('download', 'zones'+Date.now()+'.xlsx'); //or any other extension
            document.body.appendChild(link);
            link.click();

            // clean up "a" element & remove ObjectURL
            document.body.removeChild(link);
            URL.revokeObjectURL(href);
        })
    },
        async filterZones(){
            await axios.post('/filterZones', this.queryData)
            .then( response =>{
                this.zones = response.data.data
                this.paginationData = response.data.links
            })
        },
        async getSubCities(){
            await axios.get('/getSubCities')
            .then( response =>{
                this.subCities = response.data
            })
        },
        async getCities(){
            await axios.get('/getCities')
            .then( response =>{
                this.cities = response.data
            })
        },
        async getCountries(){
            await axios.get('/getCountries')
            .then( response =>{
                this.countries = response.data
            })
        },
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