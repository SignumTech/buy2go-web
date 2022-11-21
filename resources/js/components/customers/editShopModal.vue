<template>
<form action="#" @submit.prevent="updateShop">
    <div class="row p-3">
        <div class="col-md-12">
            <h5><strong>Update Shop Location</strong></h5>
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
            <button type="submit" class="btn btn-primary form-control"><span class="fa fa-check-circle"></span> Update</button>
        </div>
    </div>    
</form>
</template>
<script>
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
export default {
    props:["address"],
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
    mounted(){
        this.formData.lat = JSON.parse(this.address.geolocation).lat
        this.formData.lng = JSON.parse(this.address.geolocation).lng
        this.formData.regular_address = this.address.regular_address
        this.formData.user_id = this.address.user_id
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
        async updateShop(){
            await axios.put('/updateShop/'+this.address.id, this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Shop Updated',
                    text: 'Shop updated successfully'
                });
                this.$emit('close')
            })
        }
    }
}
</script>