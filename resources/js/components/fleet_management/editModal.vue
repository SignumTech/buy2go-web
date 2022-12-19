<template>
    <div class="row p-3 overflow-auto" style="height:100%">
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
                <vue-tel-input
                v-if="loaded"
                @country-changed="onSelect"
                :autoFormat="false" 
                v-model="phone_no"
                :inputOptions="inputOptions"
                :dropdownOptions="dropDownOptions"
                :autoDefaultCountry="false"
                :defaultCountry="formData.country_code"
                ></vue-tel-input>
            </div>
            <div class="col-md-12 mt-2">
                <label for="">License plate number</label>
                <input required v-model="formData.l_plate" type="text" class="form-control" placeholder="License plate number">
            </div>
            <div class="col-md-12 mt-2">
                <label for="">Routes</label>
                <treeselect
                :multiple="true"
                :options="routes"
                placeholder="Routes"
                v-model="formData.route_id"
                />
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary form-control"><span class="fa fa-plus"></span> UPDATE DRIVER</button>
            </div>
        </form>
    </div>
</template>
<script>
import Treeselect from '@riophae/vue-treeselect'
export default {
    components:{
        Treeselect
    },
    props:["driver","l_plate"],
    data(){
        return{
            loaded:false,
            phone_no: null,
            inputOptions:{
                required:true,
                placeholder:'Phone Number'
            },
            dropDownOptions:{
                showSearchBox:true,
                showDialCodeInList:true,
                showDialCodeInList:true,
                showDialCodeInSelection:true,
                width:'390px',
                showFlags:true,
            },
            formData:{
                f_name:"",
                l_name:"",
                l_plate:"",
                phone_no:"",
                zone_id:"",
                route_id:[],
                country_code:""
            },
            routes:[]
        }
    },
    mounted(){
        this.formData = this.driver
        this.phone_no = this.driver.phone_no
        this.formData.l_plate = this.l_plate
        this.formData.route_id = []
        this.driver.routes.forEach(route=>{
            this.formData.route_id.push(route.id)
        })
        this.getRoutes()
        this.loaded = true
    },
    methods:{
        onSelect({name, iso2, dialCode}){
            console.log(dialCode)
            this.formData.country_code = dialCode
        },
        formatPhoneNo(phone_no){ 
            if(phone_no.length == 10 || phone_no.charAt(0)=='0'){
                
                return phone_no.substring(1)
            }
            else{
                return phone_no
            }
        },
        async addDriver(){
            this.formData.phone_no = this.formatPhoneNo(this.phone_no)
            await axios.put('/drivers/'+this.formData.id, this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Driver Updated',
                    text: 'Driver updated successfully'
                });
                this.$emit('close')
            })
        },
        async getRoutes(){
            await axios.get('/routes')
            .then( response => {
                response.data.data.forEach(route=>{
                    this.routes.push({
                        id:route.id,
                        label:route.route_name+' | '+route.zone_name
                    })
                    
                })
            })
        }
    }
}
</script>