<template>
<div class="row p-4">
    <div class="col-md-12">
        <h5 class="text-center"><strong>{{agent.agent_details.f_name}} {{agent.agent_details.l_name}}</strong></h5>
        <h4 class="text-center"><strong>Balance : {{agent.agent_balance | numFormat}} ETB</strong></h4>
    </div>
    <div class="col-md-12 mt-4 mb-2">
        <label for="">Enter amount (Must be less than balance)</label>
        <input v-model="amount" type="number" :class="(amount < agent.agent_balance)?`form-control`:`form-control border border-danger-inv border-2`" placeholder="amount" :max="agent.balance">
    </div>
    <div v-if="amount && amount < agent.agent_balance" class="col-md-12 d-flex justify-content-center p-4">
        <qr-code :text="amount"></qr-code>
    </div>
</div>
</template>
<script>
export default {
    props:['agent'],
    data(){
        return{
            amount:null
        }
    }
}
</script>