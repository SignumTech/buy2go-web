<template>
    <div class="row p-3">
        <form action="#" @submit.prevent="addDriver">
            <div class="col-md-12">
                <h5>Edit Driver</h5>
            </div>
            <div class="col-md-12 mt-2">
                <label for="">First Name</label>
                <input required v-model="formData.f_name" type="text" placeholder="First Name" class="form-control">
            </div>
            <div class="col-md-12 mt-2">
                <label for="">Last Name</label>
                <input required v-model="formData.l_name" type="text" placeholder="Last Name" class="form-control">
            </div>
            <div class="col-md-12 mt-2">
                <label for="">Phone Number</label>
                <input required v-model="formData.phone_no" type="number" class="form-control" placeholder="Phone Number">
            </div>
            <div class="col-md-12 mt-2">
                <label for="">License plate number</label>
                <input required v-model="formData.l_plate" type="text" class="form-control" placeholder="License plate number">
            </div>
            <div class="col-md-12 mt-2">
                <label for="">Zone</label>
                <select class="form-select" v-model="formData.zone_id">
                    <option value=""></option>
                    <option v-for="zone,index in zones" :key="index" :value="zone.id">{{zone.zone_name}}</option>
                </select>
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary form-control"><span class="fa fa-plus"></span> UPDATE DRIVER</button>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    props:["driver"],
    data(){
        return{
            formData:{
                f_name:"",
                l_name:"",
                l_plate:"",
                phone_no:"",
                zone_id:"",
            },
            zones:{}
        }
    },
    mounted(){
        this.formData = this.driver
        this.getZones()
    },
    methods:{
        async addDriver(){
            await axios.put('/drivers/'+this.formData.driver_id, this.formData)
            .then( response =>{
                this.$emit('close')
            })
        },
        async getZones(){
            await axios.get('/getZones')
            .then( response => {
                this.zones = response.data
            })
        }
    }
}
</script>