<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Available Credits</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <table class="table px-2 mt-2">

            </table>
        </div>
    </div>
</div>   
</template>
<script>
export default {
    data(){
        return{
            credits:{}
        }
    },
    mounted(){
        this.getCredits()
    },
    methods:{
        async getCredits(){
            await axios.get('/credits')
            .then( response =>{
                this.credits = response.data
            })

        }
    }
}
</script>