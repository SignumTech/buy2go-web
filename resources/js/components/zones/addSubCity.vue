<template>
<form action="#" @submit.prevent="addSubCity">
    <div class="row p-4">
        <div class="col-md-12">
            <h5 class="m-0">Add Sub City</h5>
        </div>
        <div class="col-md-12 mt-3">
            <label for="">Sub City Name</label>
            <input v-model="formData.sub_city_name" required type="text" class="form-control" placeholder="Sub City Name">
        </div>
        <div class="col-md-12 mt-3">
            <label for="">City</label>
            <select required v-model="formData.city_id" class="form-select">
                <option value=""></option>
                <option v-for="city,index in cities" :key="index" :value="city.id">{{city.city_name}}</option>
            </select>
        </div>
        <div class="col-md-12 mt-3">
            <button class="btn btn-primary form-control"><span class="fa fa-plus"></span> Add Sub City</button>
        </div>
    </div>    
</form>

</template>
<script>
export default {
    data(){
        return{
            formData:{
                sub_city_name:null,
                city_id:null
            },
            cities:null
        }
    },
    mounted(){
        this.getCities()
    },
    methods:{
        async getCities(){
            await axios.get('/getCities')
            .then( response =>{
                this.cities = response.data
            })
        },
        async addSubCity(){
            await axios.post('/addSubCity', this.formData)
            .then( response => {
                this.$notify({
                    group: 'foo',
                    title: 'Sub City Added',
                    text: 'Sub City added successfully'
                });
                this.$emit('close')
            })
        }
    }
}
</script>