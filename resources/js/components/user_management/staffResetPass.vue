<template>
    <div class="row p-2 m-0">
        <div class="col-md-12 border-bottom p-2 text-center">
            <h5>Reset password for {{userData.name}} ({{userData.phone_no}})</h5>
        </div>
        <div class="col-md-12 text-center mt-3">
            <h5>New Password</h5>
        </div>
        <div class="col-md-12 mt-2">
            <input v-model="passwordData.password" type="password" class="form-control">
        </div>
        <div class="col-md-12 mt-3 text-right">
            <button @click="$emit('close')" class="btn btn-secondary">Cancel</button>
            <button @click="resetPass()" class="btn btn-success">Reset</button>
        </div>
    </div>
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
        }
    }
}
</script>