<template>
<form action="#" @submit.prevent="verifyShop">
    <div class="row p-3">
        <div class="col-md-12">
            <h5><strong>Add shop locations to verfiy account.</strong></h5>
        </div>
        <div class="col-md-12 mt-2">
            <label for="">Shop name and address.</label>
            <input required v-model="formData.regular_address" type="text" class="form-control" placeholder="Eg. Shemsu shop, Ayat adebabay, Ayat">
        </div>
        <div v-if="!locationLoading" class="col-md-12 mt-3">
            <h6>To use precise location click Geo Location <button type="button" @click="getGeoLocation()" class="btn btn-primary btn-sm float-end"><span class="fa fa-map-marker-alt "></span> Geo Location</button></h6>
        </div>
        <div v-if="locationLoading" class="col-md-12 mt-3">
            <div class="d-flex justify-content-center align-self-center">
                <pulse-loader :color="`#011b48`" :size="`15px`"></pulse-loader> 
            </div>            
        </div> 
        <div class="col-md-6 mt-1">
            <label for="">Longitude</label>
            <input required v-model="formData.lng" type="number" step="any" class="form-control" placeholder="Longitude">
        </div>
        <div class="col-md-6 mt-1">
            <label for="">Latitude</label>
            <input required v-model="formData.lat" type="number" step="any" class="form-control" placeholder="Latitude">
        </div>
        <div class="col-md-12 mt-3">
            <button type="submit" class="btn btn-primary form-control"><span class="fa fa-check-circle"></span> Add</button>
        </div>
    </div>    
</form>
</template>
<script>
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
export default {
    components:{
            PulseLoader
        },
    data(){
        return{
            formData:{
                regular_address:null,
                lat:null,
                lng:null,
            },
            coordinates:{},
            locationLoading:false
        }
    },
    methods:{
        async getGeoLocation(){
            this.locationLoading = true
            await this.$getLocation({})
            .then(coordinates => {
                this.formData.lat =coordinates.lat
                this.formData.lng = coordinates.lng
                this.locationLoading = false
            })
            .catch(error => {
                alert(error)
                this.locationLoading = false
            });
        },
        async verifyShop(){
            await axios.put('/verfiyShop/'+this.$route.params.id, this.formData)
            .then( response =>{
                this.$emit('close')
            })
        }
    }
}
</script>