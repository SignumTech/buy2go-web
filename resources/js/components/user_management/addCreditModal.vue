<template>
<form action="#" @submit.prevent="addCredit">
    <div class="row p-4">
        <div class="col-md-6">
            <label for="">Credit Name</label>
            <input required v-model="formData.credit_name" type="text" class="form-control" placeholder="Credit name">
        </div>
        <div class="col-md-6">
            <label for="">Credit Duration in days</label>
            <input required v-model="formData.credit_time" type="number" class="form-control" placeholder="Credit duration">
        </div>
        <div class="col-md-12 mt-3">
            <button type="submit" class="btn btn-primary form-control"><span class="fa fa-plus"></span> ADD</button>
        </div>
    </div> 
</form>

</template>
<script>
export default {
    data(){
        return{
            formData:{
                credit_name:null,
                credit_time:null,
            }
        }
    },
    methods:{
        async addCredit(){
            await axios.post('/credits', this.formData)
            .then( response =>{
                this.$notify({
                        group: 'foo',
                        type: 'success',
                        title: 'Credit Added',
                        text: 'Credit added successfully.'
                    });
                this.$emit('close')
            })
        },        
    }
}
</script>
    