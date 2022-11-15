<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Locations</strong></h5>
    </div>
    <div class="col-md-4 mt-3">
        <div class="bg-white rounded-1 p-2 shadow-sm">
            <h5>Countries <span @click="addCountryModal()" class="fa fa-plus float-end fs-6" style="cursor:pointer"></span></h5>
            <table class="table px-1 mt-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Country</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="country,index in countries" :key="`co`+index">
                        <td>{{index+1}}</td>
                        <td>{{country.country_name}}</td>
                        <td>
                            <span class="fa fa-trash-alt"></span>
                            <span @click="editCountryModal(country)" class="fa fa-edit ms-3"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4 mt-3">
        <div class="bg-white rounded-1 p-2 shadow-sm">
            <h5>Cities <span @click="addCityModal()" class="fa fa-plus float-end fs-6" style="cursor:pointer"></span></h5>
            <table class="table px-1 mt-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>City</th>
                        <th>Country</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="city,index in cities" :key="`ci`+index">
                        <td>{{index+1}}</td>
                        <td>{{city.city_name}}</td>
                        <td>{{city.country_name}}</td>
                        <td>
                            <span class="fa fa-trash-alt"></span>
                            <span @click="editCityModal(city)" class="fa fa-edit ms-3"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4 mt-3">
        <div class="bg-white rounded-1 p-2 shadow-sm">
            <h5>Sub Cities <span @click="addSubCityModal()" class="fa fa-plus float-end fs-6" style="cursor:pointer"></span></h5>
            <table class="table px-1 mt-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sub-city</th>
                        <th>City</th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="sc,index in subCities" :key="`sc`+index">
                        <td>{{index+1}}</td>
                        <td>{{sc.sub_city_name}}</td>
                        <td>{{sc.city_name}}</td>
                        <td>
                            <span class="fa fa-trash-alt"></span>
                            <span @click="editSubCityModal(sc)" class="fa fa-edit ms-3"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>    
</template>
<script>
import addCityVue from './addCity.vue'
import addCountryVue from './addCountry.vue'
import addSubCityVue from './addSubCity.vue'
import editCityVue from './editCity.vue'
import editCountryVue from './editCountry.vue'
import editSubCityVue from './editSubCity.vue'
export default {
    data(){
        return{
            countries:{},
            cities:{},
            subCities:{}
        }
    },
    mounted(){
        this.getCities()
        this.getCountries()
        this.getSubCities()
    },
    methods:{
        async getCities(){
            await axios.get('/getCities')
            .then( response =>{
                this.cities = response.data
            })
        },
        async getSubCities(){
            await axios.get('/getSubCities')
            .then( response =>{
                this.subCities = response.data
            })
        },
        async getCountries(){
            await axios.get('/getCountries')
            .then( response =>{
                this.countries = response.data
            })
        },
        addCountryModal(){
            this.$modal.show(
                addCountryVue,
                {},
                {height:'auto', width:'500px'},
                {'closed':this.getCountries}
            )
        },
        addCityModal(){
            this.$modal.show(
                addCityVue,
                {},
                {height:'auto', width:'500px'},
                {'closed':this.getCities}
            )
        },
        addSubCityModal(){
            this.$modal.show(
                addSubCityVue,
                {},
                {height:'auto', width:'500px'},
                {'closed':this.getSubCities}
            )
        },
        editSubCityModal(subCity){
            this.$modal.show(
                editSubCityVue,
                {subCity:subCity},
                {height:'auto', width:'500px'},
                {'closed':this.getSubCities}
            )
        },
        editCountryModal(country){
            this.$modal.show(
                editCountryVue,
                {country:country},
                {height:'auto', width:'500px'},
                {'closed':this.getCountries}
            )
        },
        editCityModal(city){
            this.$modal.show(
                editCityVue,
                {city:city},
                {height:'auto', width:'500px'},
                {'closed':this.getCountries}
            )
        }
        
    }
}
</script>