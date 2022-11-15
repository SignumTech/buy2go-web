<template>
<form action="#" @submit.prevent="addCountry">
    <div class="row p-4">
        <div class="col-md-12">
            <h5>Add Country</h5>
        </div>
        <div class="col-md-12 mt-3">
            <input v-model="formData.country_name" required type="text" class="form-control" placeholder="Country Name">
        </div>
        <div class="col-md-12 mt-3">
            <button class="btn btn-primary form-control"><span class="fa fa-plus"></span> Add Country</button>
        </div>
    </div>
</form>

</template>
<script>
export default {
    data(){
        return{
            formData:{
                country_name:null
            }
        }
    },
    methods:{
        async addCountry(){
            await axios.post('/addCountry', this.formData)
            .then( response => {
                this.$emit('close')
            })
        }
    }
}
</script>