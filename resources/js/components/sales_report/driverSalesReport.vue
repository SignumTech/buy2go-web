<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Drivers Report</strong></h5>
    </div>
    <div class="col-md-12">
        <form action="#" @submit.prevent="getRtmRank">
            <div class="bg-white shadow-sm rounded-1 p-2">
                <div class="row">
                    <div class="col-md-3">
                        <label for="">Start Date</label>
                        <input v-model="formData.start_date" type="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="">End Date</label>
                        <input v-model="formData.end_date" type="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="">Sort By</label>
                        <select required class="form-select" v-model="formData.sort_by">
                            <option value="total_quantity">Total order</option>
                            <option value="total_sold">Total Sales</option>
                            <option value="visits">Total Completed Visits</option>
                            <option value="commission">Total Commission</option>
                            <option value="distance_covered">Total Distance Traveled</option>
                        </select>
                    </div>
                    <div class="col-md-3 align-self-end">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary form-control">
                                <span class="fa fa-chart-bar"></span> Generate</button>
                            </div>
                            <div class="col-md-6">
                                <button @click="exportReport()" class="btn btn-primary form-control"><span class="fa fa-file-export"></span> Export</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12 mt-3">
        <LvProgressBar v-if="loading" mode="indeterminate" color="#011b48" />
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <table class="table table-fixed px-2 table-sm mt-2">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Total Orders Delivered</th>
                        <th>Total Sales</th>
                        <th>Total Visits</th>
                        <th>Total Commission</th>
                        <th>Total Distance Traveled</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user,index in users" :key="index">
                        <td>{{ user.rank }}</td>
                        <td class="align-middle" style="width: 20%">{{user.f_name}} {{ user.l_name }}</td>
                        <td class="align-middle">+{{user.country_code  }}-{{ user.phone_no }}</td>
                        
                        <td class="align-middle">{{user.total_quantity}}</td>
                        <td class="align-middle">{{user.total_sold | numFormat}} ETB</td>
                        <td class="align-middle">{{user.visits}}</td>
                        <td class="align-middle">{{user.commission}} ETB</td>
                        <td class="align-middle">{{Math.round(user.distance_covered*100)/100}} KM</td>
                    </tr>
                </tbody>
            </table>
            <nav v-if="paginationData.length > 1" aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
                <ul class="pagination justify-content-center">
                    <li v-if="currentPage > 1" @click="prev()" class="page-item"><a aria-label="Previous" class="page-link"><span aria-hidden="true" class="">« Previous</span></a></li>
                    <li v-for="pd,index in paginationData" :key="index" :class="(index+1 == currentPage)?`page-item active text-white`:`page-item`">
                        <a class="page-link" @click="getPage(index+1)" aria-label="Previous">
                            <span :class="(index+1 == currentPage)?`text-white`:``" aria-hidden="true" v-html="index+1"></span>
                        </a>
                    </li>
                    <li v-if="currentPage < paginationData.length" @click="next()" class="page-item"><a aria-label="Previous" class="page-link"><span aria-hidden="true" class="">Next »</span></a></li>
                </ul>
            </nav>
        </div>
    </div>
    
</div>    
</template>
<script>
import LvProgressBar from 'lightvue/progress-bar';
import paginationVue from './pagination.vue';
export default {
    components: {
        LvProgressBar: LvProgressBar,
        paginationVue
    },
    data(){
        return{
            currentPage:1,
            paginationData:{},
            loading:true,
            users: {},
            formData:{
                start_date:null,
                end_date:null,
                sort_by:'total_quantity'
            }
        }
    },
    mounted(){
        this.getRtmRank()
    },
    methods:{
        async exportReport(){
            await axios.post('/exportDriverSales', this.formData, {responseType: 'blob'})
            .then( response =>{
                const href = URL.createObjectURL(response.data);

                // create "a" HTML element with href to file & click
                const link = document.createElement('a');
                link.href = href;
                link.setAttribute('download', 'DriverSalesReport-'+Date.now()+'.xlsx'); 
                document.body.appendChild(link);
                link.click();

                // clean up "a" element & remove ObjectURL
                document.body.removeChild(link);
                URL.revokeObjectURL(href);
            })
        },
        prev(){
            this.loading = true
            this.currentPage--
            this.products = this.paginationData[this.currentPage-1]
            this.loading = false
        },
        next(){
            this.loading = true
            this.currentPage++
            this.products = this.paginationData[this.currentPage-1]
            this.loading = false
        },
        getPage(index){
            this.loading = true
            this.currentPage = index
            this.products = this.paginationData[index-1]
            this.loading = false
        },
        async getRtmRank(){
            this.loading = true
            await axios.post('/getDriversRank', this.formData)
            .then( response =>{
                this.paginationData = this.sliceIntoChunks(response.data, 10);
                this.users = this.paginationData[0]
                this.loading = false
            })
            .catch( error=>{
                this.loading = false
            })
        }
        ,
        sliceIntoChunks(arr, chunkSize) {
            const res = [];
            for (let i = 0; i < arr.length; i += chunkSize) {
                const chunk = arr.slice(i, i + chunkSize);
                res.push(chunk);
            }
            return res;
        }
    }
}
</script>