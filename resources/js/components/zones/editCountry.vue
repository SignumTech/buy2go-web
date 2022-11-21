<template>
<form action="#" @submit.prevent="updateCountry">
    <div class="row p-4">
        <div class="col-md-12">
            <h5>Edit Country</h5>
        </div>
        <div class="col-md-12 mt-3">
            <input v-model="formData.country_name" required type="text" class="form-control" placeholder="Country Name">
        </div>
        <div class="col-md-12 mt-3">
            <button class="btn btn-primary form-control">Update Country</button>
        </div>
    </div>
</form>

</template>
<script>
export default {
    props:['country'],
    data(){
        return{
            formData:{
                country_name:null
            }
        }
    },
    mounted(){
        this.formData = this.country
    },
    methods:{
        async updateCountry(){
            await axios.put('/updateCountry/'+this.formData.id, this.formData)
            .then( response => {
                this.$notify({
                    group: 'foo',
                    title: 'Country updated',
                    text: 'Country updated successfully'
                });
                this.$emit('close')
            })
        }
    }
}
</script>