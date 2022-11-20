<template>
<form action="#" @submit.prevent="verifyShop">
    <div class="row p-3">
        <div class="col-md-12">
            <h5><strong>Add shop locations to verfiy account.</strong></h5>
        </div>
        <div class="col-md-12 mt-2">
            <label for="">Shop name and address.</label>
            <input v-model="formData.regular_address" type="text" class="form-control" placeholder="Eg. Shemsu shop, Ayat adebabay, Ayat">
        </div>
        <div class="col-md-12 mt-3">
            <h6>To use precise location click Geo Location <button class="btn btn-primary btn-sm float-end"><span class="fa fa-map-marker-alt "></span> Geo Location</button></h6>
        </div>
        <div class="col-md-6 mt-1">
            <label for="">Longitude</label>
            <input v-model="formData.lng" type="number" class="form-control" placeholder="Longitude">
        </div>
        <div class="col-md-6 mt-1">
            <label for="">Latitude</label>
            <input v-model="formData.lat" type="number" class="form-control" placeholder="Latitude">
        </div>
        <div class="col-md-12 mt-3">
            <button type="submit" class="btn btn-primary form-control"><span class="fa fa-check-circle"></span> Verify</button>
        </div>
    </div>    
</form>
</template>
<script>
export default {
    data(){
        return{
            formData:{
                regular_address:null,
                lat:null,
                lng:null,
            }
        }
    },
    methods:{
        async verifyAccount(){
            await axios.post('/verifyAccount', this.formData)
            .then( response =>{
                this.$emit('close')
            })
        }
    }
}
</script>