<template>
<div class="row p-4">
    <div class="col-md-12">
        <h5>Add Sales Manager</h5>
    </div>
    <div class="col-md-12">
        <select class="form-select">
            <option value=""></option>
            <option v-for="sm,index in salesManagers" :key="index" :value="sm.id">{{sm.f_name}} {{sm.l_name}}</option>
        </select>
    </div>
</div>    
</template>
<script>
export default {
    data(){
        return{
            salesManagers:{}
        }
    },
    mounted(){
        this.getSalesManagers()
    },
    methods:{
        async getSalesManagers(){
            await axios.get('/getSalesManagers')
            .then( response =>{
                this.salesManagers = response.data
            })
        }
    }
}
</script>