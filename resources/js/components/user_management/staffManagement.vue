<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Staff Management</strong></h5>
    </div>
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
                        <button v-if="permission.addStaff" @click="showAddUserModal()" class="btn float-end btn-primary btn-sm mt-n4"><span class="fa fa-plus"></span >Add staff member</button>
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
                                <td>+{{td.country_code}}-{{td.phone_no}}</td>
                                <td>{{td.user_role}}</td>
                                <td class="text-end">
                                    
                                    <span v-if="(permission.addStaff && td.id != $store.state.auth.user.id)" @click="deleteUser(td.id)" class="fa fa-trash-alt me-3" style="cursor:pointer"></span>
                                    <span v-if="permission.updateStaff" @click="editUser(td)" class="fa fa-edit me-3" style="cursor:pointer"></span>
                                    
                                    <span v-if="permission.updateStaff" @click="showResetModal(td.id)" class="fa fa-key" style="cursor:pointer"></span>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                        <Circle8 class="m-auto pt-5 pb-5" v-if="Loaded" style="width: 70px; height: 70px"></Circle8>
                        <nav aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
                            <ul class="pagination justify-content-center">
                                <li v-for="pd,index in paginationData.links" :key="index" :class="(pd.active)?`page-item active text-white`:`page-item`">
                                    <a class="page-link" @click="getPage(pd.url)" aria-label="Previous">
                                        <span :class="(pd.active)?`text-white`:``" aria-hidden="true" v-html="pd.label"></span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
</div>
</template>
<script>
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import addusermodal from './addUserModal.vue'
import editUserModalVue from './editUserModal.vue'
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
            permission:{},
            queryData:{
                queryItem: ""
            },
            paginationData:{}
        }
    },
    mounted(){
        this.$store.dispatch('auth/permissions')
        .then( () =>{
            this.permission = this.$store.state.auth.permissions
        })
        this.getStaff()
    },
    methods:{
        async getPage(pageUrl){
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data
                this.tableData = response.data.data;
            })
        },
        async searchUser(){
            await axios.post('/searchStaff', this.queryData)
            .then( response =>{
                this.paginationData = response.data
                this.tableData = response.data.data;
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
        async editUser(staff){
            this.$modal.show(
                editUserModalVue,
                {"user_data" : staff,"modalType" : "Edit"},
                { height: "450", width: "700px"},
                {"closed" : this.closeAdd}
            );
        },
        async showResetModal(id){
            await axios.get('/showStaff/'+id)
            .then(response =>{
                this.$modal.show(
                    resetstaffpass,
                    {"userData" : response.data},
                    { height: "auto", width: "450px"},
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
                this.paginationData = response.data
                this.tableData = response.data.data;
                this.Loaded = false
            })
        }
    }
}
</script>