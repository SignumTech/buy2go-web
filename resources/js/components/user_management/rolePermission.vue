<template>
<div class="main_dis">
    <div class="row m-0 pt-lg-5">
        <div class="col-md-12 mb-5">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="row m-0">
                    <div class="col-md-12">
                        <button class="btn shadow-sm btn-primary btn-sm float-end mt-n3" @click="showAddRoleModal()"><strong><span class="fa fa-plus"></span> Add Role</strong></button>
                    </div>
                    
                </div>
                <div v-if="!loading" class="row m-0 p-3 border-bottom">
                    <table class="table table-data table-sm">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Role</th>
                                <th>Access Permission</th>
                                <th></th>
                            </tr>
                            
                        </thead>
                        <tbody>
                            <tr
                            v-for="td,index in tableData"
                            :key="index"
                            >
                                <td>{{index+1}}</td>
                                <th>{{td.role}}</th>
                                <td style="width: 300px">
                                    <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).Dashboard">Dashboard</span>
                                    <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).Products">Products</span>
                                    <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).Orders">Orders</span>
                                    <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).Categories">Categories</span>
                                    <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).Marketing">Marketing</span>
                                    <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).FlashSales">Flash Sales</span>
                                    <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).RegularSales">Regular Sales</span>
                                    <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).UserManagement">User Management</span>
                                    <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).RoleManagement">Role Management</span>
                                    <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).StaffManagement">Staff Management</span>
                                    <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).SalesReport">SalesReport</span>
                                    <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).Customers">Customers</span>
                                </td>
                                <td>
                                    <span @click="showEditRoleModal(td.id)" class="fa fa-edit float-end me-3"></span>
                                    <span @click="deleteRole(td.id)" class="fa fa-trash-alt float-end me-3"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="loading" class="d-flex justify-content-center">
                        <pulse-loader :color="`#BF7F25`" :size="`15px`"></pulse-loader> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import addrolemodal from './addRoleModal.vue'
export default {
    data(){
        return{
            loading: true,
            isDeleted: false,
            tableData:[], 
        }
    },
    components: {
        addrolemodal,
        PulseLoader
    },
    mounted(){
        this.getRoles()
        
    },
    methods:{
        async deleteRole(id){
            this.isDeleted = true
            var check = confirm("Are you sure you want to delete this Role?")
            if(check == true){
                await axios.delete("/deleteRole/"+id)
                .then( response =>{
                    this.getRoles();
                    this.isDeleted = false
                     this.$notify({
                        group: 'foo',
                        title: 'Role delete successful',
                        text: ''
                    });
                })
            }
        },
        async getRoles(){
            this.loading = true
            await axios.get('/getRoles')
            .then( response => {
                this.tableData = response.data;
                this.loading = false
            })
        },
        showAddRoleModal(){
            this.$modal.show(
                addrolemodal,
                {"modalType" : "Add"},
                { height: "auto", width: "700px"},
                {"closed" : this.closeAdd}    
            );
        },
        showEditRoleModal(id){
            this.$modal.show(
                addrolemodal,
                {"modalType" : "Edit", "id" : id},
                { height: "auto", width: "700px"},
                {"closed" : this.closeAdd}    
            );
        },
        closeAdd(event){
            this.getRoles();
        }
    }
}
</script>