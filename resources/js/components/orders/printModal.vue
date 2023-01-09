<template>
    <div class="col-md-12 p-4">
        <div id="toPrint" style="max-height: 500px; overflow-y:auto; overflow-x: hidden;">
            <div class="row p-2 border-bottom border-3">
                <div class="col-6">
                    <img style="width:50px;height:auto" src="/storage/settings/afra.png" class="img img-fluid" alt="">
                </div>
                <div class="col-6">
                    <img style="width:40px;height:auto" src="/storage/settings/buy2go.png" class="img img-fluid float-end" alt="">
                </div>
            </div>
        
            <div class="row p-2 mt-3">
                <div class="col-md-12">
                    <h6 class="float-end">Invoice Date: {{currentDate()}}</h6>
                </div>
                <div class="col-md-12">
                    <h6 class="float-end">Invoice number:</h6>
                </div>
                <div class="col-6 mt-3">
                    <h6>Order Date: {{ order.created_at | moment("MMM Do YYYY")}}</h6>
                    <h6>Order Number: {{ order.order_no }}</h6>
                </div>
                <div class="col-6 mt-3">
                    <h6 class="float-end">Payment Type: <span v-if="order.payment_method == 'Credit'">Credit</span> <span v-if="order.payment_method != 'Credit'">Cash</span></h6>
                </div>
                <div class="col-md-12">
                    <h6>Buyer: {{ address.f_name+' '+address.l_name }}</h6>
                </div>
            </div>
            <div class="row p-2 mt-3">
                <div class="col-md-12">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Unit price</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ot,index in orderItems" :key="index">
                                <td>{{ ot.p_name }}</td>
                                <td>{{ ot.unit }}</td>
                                <td>{{ ot.quantity }}</td>
                                <td>{{ ot.price | numFormat }}</td>
                                <td>{{ ot.price * ot.quantity  | numFormat}}</td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">
                                    Sub Total
                                </th>
                                <td>{{taxCalculations.subTotal | numFormat}}</td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">
                                    15% VAT
                                </th>
                                <td>{{taxCalculations.vat | numFormat}}</td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">
                                    Total
                                </th>
                                <td>{{order.total | numFormat}}</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row p-2 mt-3">
                <div class="col-md-12">
                    <h6>Shipping Info</h6>
                    <h6>{{ address.f_name+' '+address.l_name }}</h6>
                    <h6>+{{address.country_code}}-{{ address.phone_no }}</h6>
                </div>
            </div>
            <div class="row p-2 mt-3">
                <div class="col-6">
                    <h6 class="pt-3 border-top border-3">Receiver Signiture</h6>
                </div>
                <div class="col-6">
                    <h6 class="pt-3 border-top border-3">Cashier Signiture</h6>
                </div>
            </div>        
        </div>

        <div class="row p-2 mt-3">
            <div class="col-md-12">
                <button @click="print()" class="btn btn-primary btn-sm px-4 rounded-1 float-end text-white"><span class="fa fa-print"></span> Print Reciept</button>
            </div>
        </div>
    </div>

</template>
<script>
export default {
    props:['address', 'orderItems', 'order', 'taxCalculations'],
    methods:{
        async print(){
            await this.$htmlToPaper('toPrint')
        },
        currentDate() {
            const current = new Date();
            const date = `${current.getDate()}/${current.getMonth()+1}/${current.getFullYear()}`;
            return date;
        }
    }
}
</script>