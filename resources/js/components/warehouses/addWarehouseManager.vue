<template>
    <div class="row p-3">
        <form action="#" @submit.prevent="addManager">
            <div class="col-md-12">
                <h5>Add Warehouse Manager</h5>
            </div>
            <div class="col-md-12 mt-2">
                <label for="">First Name</label>
                <input required v-model="formData.f_name" type="text" placeholder="First Name" class="form-control">
                <h6 class="text-danger m-0" v-for="an in valErrors.f_name" :key="an.id">{{an}}</h6>
            </div>
            <div class="col-md-12 mt-2">
                <label for="">Last Name</label>
                <input required v-model="formData.l_name" type="text" placeholder="Last Name" class="form-control">
                <h6 class="text-danger m-0" v-for="an in valErrors.l_name" :key="an.id">{{an}}</h6>
            </div>
            <div class="col-md-12 mt-2">
                <label for="">Phone Number</label>
                <div class="input-group">
                    <vue-country-code
                        @onSelect="onSelect"
                        :enabledCountryCode="true"
                    >
                    </vue-country-code>
                    <input required v-model="phone_no" type="number" class="form-control" placeholder="Phone Number">
                </div>
                <h6 class="text-danger m-0" v-for="an in valErrors.phone_no" :key="an.id">{{an}}</h6>
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary form-control"><span class="fa fa-plus"></span> ADD MANAGER</button>
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
    data(){
        return{
            formData:{
                f_name:"",
                l_name:"",
                phone_no:"",
                country_code:""
            },
            phone_no: null,
            valErrors:{},
            country_code:null,
            routes:[],
            valErrors:{}
        }
    },
    mounted(){
        this.getRoutes()
    },
    methods:{
        onSelect({name, iso2, dialCode}){
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
        async addManager(){
            this.formData.phone_no = this.formatPhoneNo(this.phone_no)
            await axios.post('/addWarehouseManager', this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Warehouse Manager added',
                    text: 'Warehouse Manager was added successfully'
                });
                this.$emit('close')
            })
            .catch( error => {
                if(error.response.status == 422){
                    this.valErrors = error.response.data.errors
                }
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