<template>
<form action="#" @submit.prevent="addCity">
    <div class="row p-4">
        <div class="col-md-12">
            <h5 class="m-0">Add City</h5>
        </div>
        <div class="col-md-12 mt-3">
            <label for="">City Name</label>
            <input v-model="formData.city_name" required type="text" class="form-control" placeholder="City Name">
        </div>
        <div class="col-md-12 mt-3">
            <label for="">Country</label>
            <select required v-model="formData.country_id" class="form-select">
                <option value=""></option>
                <option v-for="country,index in countries" :key="index" :value="country.id">{{country.country_name}}</option>
            </select>
        </div>
        <div class="col-md-12 mt-3">
            <button class="btn btn-primary form-control"><span class="fa fa-plus"></span> Add City</button>
        </div>
    </div>
</form>
</template>
<script>
export default {
    data(){
        return{
            formData:{
                city_name:null,
                country_id:null
            },
            countries:{}
        }
    },
    mounted(){
        this.getCountries()
    },
    methods:{
        async getCountries(){
            await axios.get('/getCountries')
            .then( response =>{
                this.countries = response.data
            })
        },
        async addCity(){
            await axios.post('/addCity', this.formData)
            .then( response => {
                this.$emit('close')
            })
        }
    }
}
</script>