<template>
<div class="row p-4">
    <form action="#" @submit.prevent="updateSalesManager">
        <div class="col-md-12">
            <h5>Edit Sales Manager</h5>
        </div>
        <div class="col-md-12 mt-2">
            <select required v-model="formData.sales_id" class="form-select">
                <option :value=null></option>
                <option v-for="sm,index in salesManagers" :key="index" :value="sm.id">{{sm.f_name}} {{sm.l_name}}</option>
            </select>
        </div>
        <div class="col-md-12 mt-3">
            <button type="submit" class="btn btn-primary form-control"><span class="fa fa-user-plus"></span> Update</button>
        </div>    
    </form>
</div>    
</template>
<script>
export default {
    props:['sales_manager', 'shop_id'],
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
        this.formData.sales_id = this.sales_manager.id
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
        async updateSalesManager(){
            await axios.put('/updateSalesManager/'+this.shop_id, this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Sales Manager Updated',
                    text: 'Shop Manager Updated Successfully!'
                });
                this.$emit('close')
            })
        }
    }
}
</script>