<template>
<div class="row p-4">
    <form action="#" @submit.prevent="addSalesManager">
        <div class="col-md-12">
            <h5>Add Sales Manager</h5>
        </div>
        <div class="col-md-12 mt-2">
            <select required v-model="formData.sales_id" class="form-select">
                <option :value=null></option>
                <option v-for="sm,index in salesManagers" :key="index" :value="sm.id">{{sm.f_name}} {{sm.l_name}}</option>
            </select>
        </div>
        <div class="col-md-12 mt-3">
            <button type="submit" class="btn btn-primary form-control"><span class="fa fa-user-plus"></span> Assign</button>
        </div>    
    </form>
</div>    
</template>
<script>
export default {
    props:['shop_id'],
    data(){
        return{
            salesManagers:{},
            formData:{
                sales_id:null,
                shop_id:null
            }
        }
    },
    mounted(){
        this.formData.shop_id = this.shop_id
        this.getSalesManagers()
    },
    methods:{
        async getSalesManagers(){
            await axios.get('/getSalesManagers')
            .then( response =>{
                this.salesManagers = response.data
            })
        },
        async addSalesManager(){
            await axios.post('/assignSalesManager', this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Sales Manager Added',
                    text: 'Sales Manager Added Successfully!'
                });
                this.$emit('close')
            })
        }
    }
}
</script>