<template>
    <div class="row p-3">
        <form action="#" @submit.prevent="updateManager">
            <div class="col-md-12">
                <h5>Edit Warehouse Manager</h5>
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
                <button type="submit" class="btn btn-primary form-control">UPDATE MANAGER</button>
            </div>
        </form>
    </div>
</template>
<script>
import Treeselect from '@riophae/vue-treeselect'
export default {
    props:['manager'],
    components:{
        Treeselect
    },
    data(){
        return{
            formData:{
                f_name:"",
                l_name:"",
                phone_no:"",
            },
            phone_no: null,
            valErrors:{},
            country_code:null,
            routes:[]
        }
    },
    mounted(){
        this.formData = this.manager
        this.phone_no = this.manager.phone_no
    },
    methods:{
        onSelect({name, iso2, dialCode}){
            this.country_code = dialCode
        },
        formatPhoneNo(phone_no){    
            if(phone_no.length == 10 && phone_no.charAt(0)=='0'){
                
                return this.country_code+phone_no.substring(1)
            }
            else{
                return this.country_code+phone_no
            }
        },
        async updateManager(){
            this.formData.phone_no = this.formatPhoneNo(this.phone_no)
            await axios.put('/updateWarehouseManager/'+this.manager.id, this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Warehouse Manager updated',
                    text: 'Warehouse Manager was updated successfully'
                });
                this.$emit('close')
            })
        },
    }
}
</script>