<template>
<div class="row p-3">
    <form action="#" @submit.prevent="updateWarehouse">
        <div class="col-md-12">
            <label for="">Warehouse Name</label>
            <input required v-model="formData.w_name" type="text" class="form-control" placeholder="Warehouse name">
        </div>
        <div class="col-md-12 mt-3">
            <h5 for="" class="m-0 ">Location <span v-if="formData.location" class="fs-6">(longitude: {{formData.location.lng}} latitude: {{formData.location.lat}})</span></h5>
            <h6 class="mt-3 mb-3"><span class="fa fa-info-circle"></span> Drag the pin to the location of the warehouse.</h6>
            <input required type="hidden" v-model="formData.location">
            <google-map :center="center" :zoom="12" style="width: 100%; height: 400px;">
                <google-marker v-for="m,index in markers" :key="index" :position="m.position" :clickable="true" :draggable="true" @click="center=m.position" @drag="updateDraged($event.latLng)"></google-marker>
            </google-map>
        </div>
        <div class="col-md-12 mt-3">
            <button type="submit" class="form-control btn btn-primary">UPDATE WAREHOUSE</button>
        </div>
    </form>
</div>    
</template>
<script>
export default {
    props:["warehouse"],
    data(){
        return{
            formData:{
                w_name:null,
                location:{
                    lat: 8.9806,
                    lng: 38.7578
                }
            },
            center: {
                lat: 8.9806,
                lng: 38.7578
            },
            markers: [{
                position: {
                    lat: 8.9806,
                    lng: 38.7578
                }
            }]
        }
    },
    mounted(){
        this.formData.w_name = this.warehouse.w_name
        this.formData.location.lat = parseFloat(JSON.parse(this.warehouse.location).lat)
        this.formData.location.lng = parseFloat(JSON.parse(this.warehouse.location).lng)

        this.center.lat = parseFloat(JSON.parse(this.warehouse.location).lat)
        this.center.lng = parseFloat(JSON.parse(this.warehouse.location).lng)

        this.markers[0].position.lat = parseFloat(JSON.parse(this.warehouse.location).lat)
        this.markers[0].position.lng = parseFloat(JSON.parse(this.warehouse.location).lng)
    },
    methods:{
        async updateWarehouse(){
            await axios.put('/warehouses/'+this.warehouse.id, this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Warehouse Updated',
                    text: 'You have successfuly updated a warehouse!'
                });
                this.$emit('close')
            })
        },
        updateDraged(location){
            this.formData.location = {
                lat: location.lat().toFixed(4),
                lng: location.lng().toFixed(4)
            }
        }
    } 
}
</script>