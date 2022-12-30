<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Products Sales Report</strong></h5>
    </div>
    <div class="col-md-12">
        <form action="#" @submit.prevent="getProductsRank">
            <div class="bg-white shadow-sm rounded-1 p-2">
                <div class="row">
                    <div class="col-md-3">
                        <label for="">Start Date</label>
                        <input required v-model="formData.start_date" type="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="">End Date</label>
                        <input required v-model="formData.end_date" type="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="">Sort By</label>
                        <select required class="form-select" v-model="formData.sort_by">
                            <option value="total_quantity">Total order</option>
                            <option value="total_sold">Total Sales</option>
                        </select>
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary"><span class="fa fa-chart-bar"></span> Generate Report</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <table class="table table-fixed px-2 table-sm mt-2">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Total Order</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product,index in products" :key="index">
                        <td class="align-middle">
                            <img :src="`/storage/products/`+product.p_image" class="img img-fluid img-thumb cat_img rounded-1" alt="">
                        </td>
                        <td class="align-middle" style="width: 20%">{{product.p_name}}</td>
                        <td class="align-middle">{{product.price}} Birr</td>
                        
                        <td class="align-middle">{{product.total_quantity}}</td>
                        <td class="align-middle">{{product.total_sold | numFormat}} ETB</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>    
</template>
<script>
export default {
    data(){
        return{
            products: {},
            formData:{
                start_date:null,
                end_date:null,
                sort_by:'total_quantity'
            }
        }
    },
    mounted(){
        this.getProductsRank()
    },
    methods:{
        async getProductsRank(){
            await axios.post('/productsRank', this.formData)
            .then( response =>{
                this.products = response.data
            })
        }
    }
}
</script>