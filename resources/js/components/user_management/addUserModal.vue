<template>
<div class="row m-0 p-2">
    <div class="col-md-12 border-bottom p-2">
        <div class="row">
            <div class="col-md-10">
                <h5 v-if="add">Add User</h5>
                <h5 v-if="edit">Edit User</h5>
            </div>
            <div class="col-md-2 text-end">
                <span @click="$emit('close')" class="fa fa-times" style="font-size: 20px"></span>
            </div>
        </div>
    </div>
    <div class="col-md-12 pt-2 ">
        <div class="row p-2">
            <div class="col-md-3 text-right">
                <label>* First Name</label>
            </div>
            <div class="col-md-7">
                <input placeholder="First Name" type="text" v-model="formData.f_name" class="form form-control">
                <input  type="hidden" v-model="formData.id" class="form form-control">
                <h6 class="text-danger m-0" v-for="an in valErrors.f_name" :key="an.id">{{an}}</h6>
            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="row p-2">
            <div class="col-md-3 text-right">
                <label>* Last Name</label>
            </div>
            <div class="col-md-7">
                <input placeholder="Last Name" type="text" v-model="formData.l_name" class="form form-control">
                <input  type="hidden" v-model="formData.id" class="form form-control">
                <h6 class="text-danger m-0" v-for="an in valErrors.l_name" :key="an.id">{{an}}</h6>
            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="row p-2">
            <div class="col-md-3 text-right">
                <label>* Title</label>
            </div>
            <div class="col-md-7">
                <input placeholder="Job Title" type="text" v-model="formData.title" class="form form-control">
                <h6 class="text-danger m-0" v-for="an in valErrors.title" :key="an.id">{{an}}</h6>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-md-3 text-right">
                <label>* Phone Number</label>
            </div>
            <div class="col-md-7">
                <div class="input-group">
                    <vue-country-code
                        @onSelect="onSelect"
                        :enabledCountryCode="true"
                        :selectedCountryCode="true"
                        :enableSearchField="true"
                    >
                    </vue-country-code>
                    <input type="number" v-model="formData.phone_no" class="form form-control">
                </div>
                
                <h6 class="text-danger m-0" v-for="an in valErrors.phone_no" :key="an.id">{{an}}</h6>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-md-3 text-right">
                <label>* Email Address</label>
            </div>
            <div class="col-md-7">
                <input placeholder="Email address" type="email" v-model="formData.email" class="form form-control">
                <h6 class="text-danger m-0" v-for="an in valErrors.email" :key="an.id">{{an}}</h6>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-md-3 text-right">
                <label>* User role</label>
            </div>
            <div class="col-md-7">
                <select v-model="formData.user_role" class="form-select">
                    <option value=""></option>
                    <option 
                    v-for="role in roles"
                    :key="role.id"
                    :value="role.role">{{role.role}}</option>
                </select>
                <h6 class="text-danger m-0" v-for="an in valErrors.user_role" :key="an.id">{{an}}</h6>
            </div>
        </div>
    </div>
    <div class="col-md-12 border-top p-2">
        <div class="row">
            <div class="col-md-12 text-end">
                <button :disabled="isClicked" @click="$emit('close')" class="btn btn-secondary btn-sm">Cancel</button>
                <button :disabled="isClicked" v-if="add" @click="addUser()" class="btn btn-primary btn-sm">Save</button>
                <button :disabled="isClicked" v-if="edit" @click="editUser()" class="btn btn-primary btn-sm">Edit</button>
            </div>
        </div>
    </div>
    
</div>
</template>
<script>

export default {
    props:["user_data", "modalType"],
    data (){
        return{
            isClicked: false,
            valErrors: {},
            formData :{
                id : "",
                title : "",
                f_name : "",
                l_name : "",
                phone_no : "",
                email : "",
                user_role : "",
                country_code: null,
            },
            roles:[],
            activation :{},
            edit : false,
            add : false,
        }
    },
    mounted(){
        if(this.modalType == "Edit"){
            this.formData = this.user_data
            this.edit = true
        }
        else{
            this.add = true
        }
        this.getRoles()
    },
    methods:{
        onSelect({name, iso2, dialCode}){
            this.formData.country_code = dialCode
        },
        async getRoles(){
            await axios.get('/getRoles')
            .then( response =>{
                this.roles = response.data
            })
        },
        formatPhoneNo(phone_no){ 
            if(phone_no.length == 10 || phone_no.charAt(0)=='0'){
                
                return phone_no.substring(1)
            }
            else{
                return phone_no
            }
        },
        async addUser(){
            this.isClicked = true
            this.formData.phone_no = this.formatPhoneNo(this.formData.phone_no)
            await axios.post('/registerStaff', this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    title: 'User added',
                    text: 'Successfully Added a User!'
                });
                
                this.$emit('close')
            })
            .catch( error =>{
                this.isClicked = false
                if( error.response.status == 422){
                    this.valErrors = error.response.data.errors
                    
                }
            })
        },
        async editUser(){
            this.isClicked = true
            await axios.post('/editStaff', this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    title: 'Successfully Edited a User!',
                    text: ''
                });
                this.$emit('close')
            })
            .catch( error =>{
                this.isClicked = false
                if( error.response.status == 422){
                    this.valErrors = error.response.data.errors
                    
                }
            })
        }
    }
}
</script>