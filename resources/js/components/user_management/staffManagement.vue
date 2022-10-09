<template>
<div class="main_dis">
    <div class="row m-0 pt-lg-5">
        <div class="col-md-12">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="row m-0 p-3 border-bottom">
                    <div class="col-md-4">
                        <input type="text" v-model="queryData.queryItem" class="form-control" placeholder="Phone/Name">
                    </div>
                    <div class="col-md-4">
                        <button @click="searchUser()" class="btn btn-primary"><span class="fa fa-search"></span> Search</button>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm rounded-lg mt-5">
                <div class="row m-0 p-2 border-bottom">
                    <div class="col-md-12">
                        <button @click="showAddUserModal()" class="btn float-end btn-primary btn-sm mt-n4"><span class="fa fa-plus"></span >Add staff member</button>
                    </div>
                    <div class="col-md-12 mt-2">
                        <table class="table table-sm table-data">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Title</th>
                                    <th>E-mail</th>
                                    <th>Phone Number</th>
                                    <th>User Role</th>
                                    <th class="text-center">Operate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                v-for="td,index in tableData"
                                :key="td.id"
                                >
                                <td>{{index+1}}</td>
                                <td>{{td.f_name}}</td>
                                <td>{{td.l_name}}</td>
                                <td>{{td.title}}</td>
                                <td>{{td.email}}</td>
                                <td>{{td.phone_no}}</td>
                                <td>{{td.user_role}}</td>
                                <td class="text-center">
                                    <span @click="editUser(td.id)" class="fa fa-edit me-5" style="cursor:pointer"></span>
                                    <span @click="deleteUser(td.id)" class="fa fa-trash-alt me-5" style="cursor:pointer"></span>
                                    <span @click="showResetModal(td.id)" class="fa fa-key" style="cursor:pointer"></span>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                        <Circle8 class="m-auto pt-5 pb-5" v-if="Loaded" style="width: 70px; height: 70px"></Circle8>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import addusermodal from './addUserModal.vue'
import resetstaffpass from './staffResetPass.vue'
export default {
    components:{
        addusermodal,
        resetstaffpass,
        PulseLoader
    },
    data(){
        return{
            Loaded: true,
            tableData:[],
            queryData:{
                queryItem: ""
            }
        }
    },
    mounted(){
        this.getStaff()
    },
    methods:{
        async searchUser(){
            await axios.post('/searchStaff', this.queryData)
            .then( response =>{
                this.tableData = response.data;
            })
        },
        async deleteUser(id){
            var check = confirm("Are you sure you want to delete this user?")
            if(check == true){
                await axios.delete("/deleteStaff/"+id)
                .then( response =>{
                    this.getStaff();
                     this.$notify({
                        group: 'foo',
                        title: 'User delete successful',
                        text: ''
                    });
                })
            }

        },
        async editUser(id){
            await axios.get('/showStaff/'+id)
            .then(response =>{
                this.$modal.show(
                    addusermodal,
                    {"user_data" : response.data,"modalType" : "Edit"},
                    { height: "450", width: "700px"},
                    {"closed" : this.closeAdd}
                );
            })
        },
        async showResetModal(id){
            await axios.get('/showStaff/'+id)
            .then(response =>{
                this.$modal.show(
                    resetstaffpass,
                    {"userData" : response.data},
                    { height: "250", width: "350px"},
                    {"closed" : this.closeAdd}
                );
            });
        },
        showAddUserModal(){
            this.$modal.show(
                addusermodal,
                {"modalType" : "Add"},
                { height: "auto", width: "700px"},
                {"closed" : this.closeAdd}    
            );
        },
        closeAdd(event){
            this.getStaff()
        },
        async getStaff(){
            await axios.get('/getStaff')
            .then( response =>{
                this.tableData = response.data;
                this.Loaded = false
            })
        }
    }
}
</script>