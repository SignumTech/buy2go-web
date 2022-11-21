<template>
<form action="#" @submit.prevent="updateCredit">
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
            <button type="submit" class="btn btn-primary form-control">UPDATE</button>
        </div>
    </div> 
</form>

</template>
<script>
export default {
    props:['credits'],
    data(){
        return{
            formData:{}
        }
    },
    mounted(){
        this.formData = this.credits
    },
    methods:{
        async updateCredit(){
            await axios.put('/credits/'+this.formData.id, this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    title: 'Credit updated',
                    text: 'Credit updated successfully.'
                });
                this.$emit('close')
            })
        },        
    }
}
</script>
        
        