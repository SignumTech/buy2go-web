<template>
  <div class="container-fluid p-0 overflow-hidden mob_hide" style="height: 100vh">
    <div class="row m-0">
        <div class="p-0 shadow-sm" style="background-color:#fff; width:18%" v-if="authenticated">
            <div class="row m-0" style="background-color: #011b48;">
                <div class="col-md-12 p-4">
                  <img class="img img-fluid d-block m-auto" src="/storage/settings/logo.png" style="width:auto; height: 45px;">
                </div>
            </div>
            <nav v-if="authenticated" id="sidebar" class="border-end" style="height: 100vh; overflow-y: auto">
              <ul class="list-unstyled components">
                <li :class="$route.path == `/dashboard` ? `active nav-item` : ``">
                  <router-link class="nav-link a-admin" to="/dashboard"><i data-feather="pie-chart"></i> Dashboard <span class="sr-only"></span></router-link>
                </li>
                <li :class="$route.path == `productList` ? `active nav-item` : ``">
                  <router-link class="nav-link a-admin" to="/productList"><i data-feather="box"></i> Products</router-link>
                </li>
                <li :class="$route.path == `/ordersList` ? `active nav-item` : ``">
                  <router-link class="nav-link a-admin" to="/ordersList"><i data-feather="shopping-cart"></i> Orders</router-link>
                </li>
                <li :class="$route.path == `/categoryList` ? `active nav-item` : ``">
                  <router-link class="nav-link a-admin" to="/categoryList"><i data-feather="grid"></i> Categories</router-link>
                </li>
                <li :class="$route.path == `/warehouse` ? `active nav-item` : ``">
                  <router-link class="nav-link a-admin" to="/warehouse"><i data-feather="home"></i> Warehouses</router-link>
                </li>
                <li :class="$route.path == `/zones` ? `active nav-item` : ``">
                  <router-link class="nav-link a-admin" to="/zones"><i data-feather="map"></i> Zones</router-link>
                </li>
                <li>
                  <a data-bs-toggle="collapse" href="#us_ma" aria-expanded="false" aria-controls="collapseExample"><i data-feather="truck"></i> Fleet Management</a>
                  <div class="collapse" id="us_ma">
                    <ul class="collapse list-unstyled" id="us_ma">
                      <li :class="$route.path == `/driversList` ? `active` : ``">
                          <router-link to="/driversList"><i class="fa fa-user-alt"></i> Drivers</router-link>
                      </li>
                    </ul>
                  </div>
                </li>
                <li>
                  <a data-bs-toggle="collapse" href="#u_m" aria-expanded="false" aria-controls="collapseExample"><i data-feather="user"></i> User Management</a>
                  <div class="collapse" id="u_m">
                    <ul class="collapse list-unstyled" id="u_m">
                      <li  :class="$route.path == `/staffManagement` ? `active` : ``">
                          <router-link to="/staffManagement"><i class="fa fa-users"></i> Staff Management</router-link>
                      </li>
                      <li  :class="$route.path == `/rolePermission` ? `active` : ``">
                          <router-link to="/rolePermission"><i class="fa fa-key"></i> Role Permission</router-link>
                      </li>
                    </ul>
                  </div>
                </li>
                <li :class="$route.path == `/setting` ? `active nav-item` : ``">
                  <router-link class="nav-link a-admin" to="/setting"><i data-feather="trending-up"></i> Sales Report</router-link>
                </li>
                <li :class="$route.path == `/customers` ? `active nav-item` : ``">
                  <router-link class="nav-link a-admin" to="/customers"><i data-feather="users"></i> Customers</router-link>
                </li>
              </ul>
            </nav>            
        </div>
        <div class="p-0" :style="(authenticated)?`width: 82%`:`width:200%`">
            <nav v-if="authenticated" class="navbar navbar-expand-md navbar-dark shadow-sm" style="background-color: #fff; height: 55px">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
  
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
  
                        </ul>
  
                        <!-- Right Side Of Navbar -->
                        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                          <ul class="navbar-nav ml-auto">
                              <li class="nav-item px-3">
                                  <router-link class="a-admin" to="/profile">
                                      <span class="fa fa-user-cog pr-2" style="font-size: 20px"></span><strong>  {{$store.state.auth.user.f_name}} {{$store.state.auth.user.l_name}}</strong>
                                  </router-link>
                              </li>
                              <li class="nav-item dropdown px-3">
                                  <a class="nav-link dropdown-toggle p-0" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      <span class="fa fa-bell pr-2" style="font-size: 20px"></span><span class="badge bg-danger mx-1">{{$store.state.auth.notifications.length}}</span><strong></strong>
                                  </a>
                                  <ul class="dropdown-menu rounded-1" aria-labelledby="navbarDropdown" style="max-height:500px;overflow-y: auto; overflow-x:hidden">
                                    <li v-for="notification,index in $store.state.auth.notifications" :key="index" style="width:400px">
                                      <div class="row m-0 bg-light border-bottom">
                                        <div class="col-md-1 align-self-center text-center">
                                          <span class="fa fa-bell"></span>
                                        </div>
                                        <div class="col-md-11 px-3 py-2">
                                          <a class="p-0 bg-light" :href="notification.data.link">{{notification.data.message}}</a>
                                        </div>
                                      </div>
                                    </li>
                                  </ul>
                              </li>
                              <li class="nav-item px-3">
                                  <a @click="logout()" style="cursor:pointer">
                                  <span class="fa fa-power-off" style="font-size: 20px"></span>
                                  </a>
                              </li>
                          </ul>
                        </div>
                    </div>
                </div>
            </nav>
          <div class="row m-0 main_dis">
              <div class="col-md-12">   
                  <router-view></router-view>             
              </div>
          </div>
          <notifications group="foo" position="bottom right" width="400"/>
        </div>
    </div>
  </div>
  </template>
<script>
  export default {
    data(){
      return{
        authenticated:false
  
      }
    },
    mounted(){
      
      this.authenticated = this.$store.state.auth.authenticated
      this.getNotifications()
      this.connect();
      feather.replace();
    },
    updated(){
      feather.replace();
    },
    computed: {
      togggle(){
          this.authenticated = this.$store.state.auth.authenticated; 
      },
      
    },
    methods:{
      notif(){
        this.$notify({
                  group: 'foo',
                  type: 'success',
                  title: 'Warehouse Added',
                  text: "test"
              });
      },
      async getNotifications(){
        await axios.get('/getMyNotifications')
        .then( response =>{
          this.$store.state.auth.notifications = response.data
        })
      },
      connect(){
            window.Echo.private('App.Models.User.'+this.$store.state.auth.user.id)
            .notification((notification) => {
              this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Warehouse Added',
                    text: notification.message
                });
                this.getNotifications()
            });
        },
      logout(){
          axios.post("logout").then(response => { 
          window.location.replace("/home");
          })
          .catch(error => {
          console.log(error);
          });
      },
    }
  }
</script>