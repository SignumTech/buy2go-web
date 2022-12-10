<template>
    <form action="#" @submit.prevent="resetPass">
        <div class="row p-2 m-0">
            <div class="col-md-12 border-bottom p-2 text-center">
                <h5>Reset password for {{userData.name}} ({{userData.phone_no}})</h5>
            </div>
            <div class="col-md-12 mt-2">
                <label for="">Password</label>
                <input required v-model="passwordData.password" type="password" class="form-control" placeholder="Password">
                <h6 class="text-danger" v-for="pe,index in passErrors.password" :key="index">{{pe}}</h6>
            </div>
            <div class="col-md-12 mt-2">
                <label for="">Confirm Password</label>
                <input required v-model="passwordData.password_confirmation" type="password" class="form-control" placeholder="Confirm Password">
            </div>
            <div class="col-md-6 mt-3 mb-3 text-right">
                <button @click="$emit('close')" class="btn btn-secondary form-control rounded-1">Cancel</button>
                
            </div>
            <div class="col-md-6 mt-3 mb-3">
                <button type="submit" class="btn btn-primary form-control rounded-1">Reset Password</button>
            </div>
        </div>
    </form>

</template>
<script>
export default {
    props:["userData"],
    data(){
        return{
            passwordData:{
                user_id : this.userData.id,
                password : ""
            },
            passErrors:{}
        }
    },
    mounted(){
        console.log(this.userData);
    },
    methods:{
        async resetPass(){
            await axios.post('/resetStaffPass', this.passwordData)
            .then( response => {
                this.$emit('close')
                this.$notify({
                    group: 'foo',
                    title: 'Password reset successful',
                    text: ''
                });
            })
            .catch( error => {
                if(error.response.status == 422){
                    this.passErrors = error.response.data.errors
                }
            })
        }
    }
}
</script>