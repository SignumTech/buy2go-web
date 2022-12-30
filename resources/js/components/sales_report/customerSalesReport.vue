<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Customer Sales Report</strong></h5>
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
        <LvProgressBar v-if="loading" mode="indeterminate" color="#011b48" />
        <div class="bg-white rounded-1 p-3 shadow-sm">
            
        </div>
    </div>
    
</div>    
</template>
<script>
export default {
    data(){
        return{
            currentPage:1,
            paginationData:{},
            loading:true,
            products: {},
            formData:{
                start_date:null,
                end_date:null,
                sort_by:'total_quantity'
            }
        }
    }
}
</script>