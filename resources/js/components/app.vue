<template>
  <div class="container-fluid p-0 overflow-hidden mob_hide" style="height: 100vh">
    <div class="row m-0">
        <div class="p-0 shadow-sm" style="background-color:#fff; width:18%; height: 100%" v-if="authenticated">
            <div class="row m-0" style="background-color: #011b48; height: 12vh">
                <div @click="disconnect()" class="col-md-12 p-4">
                  <img class="img img-fluid d-block m-auto" src="/storage/settings/logo.png" style="width:auto; height: 45px;">
                </div>
            </div>
            <div class="row m-0" style="height: 88vh; overflow-y: auto">
              <div class="col-md-12 p-0"  >
                <nav v-if="authenticated" id="sidebar" class="border-end" >
                <ul class="list-unstyled components">
                  <li v-if="permissions.dashboard" :class="$route.path == `/dashboard` ? `active nav-item` : ``">
                    <router-link class="nav-link a-admin" to="/dashboard"><i data-feather="pie-chart"></i> Dashboard <span class="sr-only"></span></router-link>
                  </li>
                  <li v-if="permissions.productList" :class="$route.path == `/productList` ? `active nav-item` : ``">
                    <router-link class="nav-link a-admin" to="/productList"><i data-feather="box"></i> Products</router-link>
                  </li>
                  <li v-if="permissions.ordersList" :class="$route.path == `/ordersList` ? `active nav-item` : ``">
                    <router-link class="nav-link a-admin" to="/ordersList"><i data-feather="shopping-cart"></i> Orders</router-link>
                  </li>
                  <li v-if="$store.state.auth.permissions.categoryList" :class="$route.path == `/categoryList` ? `active nav-item` : ``">
                    <router-link class="nav-link a-admin" to="/categoryList"><i data-feather="grid"></i> Categories</router-link>
                  </li>
                  <li v-if="permissions.warehouseManagement">
                    <a data-bs-toggle="collapse" href="#w_ma" aria-expanded="false" aria-controls="collapseExample"><i data-feather="home"></i> Warehouse Management</a>
                    <div class="collapse" id="w_ma">
                      <ul class="collapse list-unstyled" id="w_ma">
                        <li v-if="permissions.warehouse" :class="$route.path == `/warehouse` ? `active nav-item` : ``">
                          <router-link class="nav-link a-admin" to="/warehouse"><i class="fa fa-warehouse"></i> Warehouses</router-link>
                        </li>
                        <li v-if="permissions.warehouseManagers" :class="$route.path == `/warehouseManagers` ? `active` : ``">
                            <router-link to="/warehouseManagers"><i class="fa fa-user-friends"></i> Warehouse Managers</router-link>
                        </li>                  
                      </ul>
                    </div>
                  </li>
                  <li v-if="permissions.zonesLocations">
                    <a data-bs-toggle="collapse" href="#z_ma" aria-expanded="false" aria-controls="collapseExample"><i data-feather="map"></i> Zones & Locations</a>
                    <div class="collapse" id="z_ma">
                      <ul class="collapse list-unstyled" id="z_ma">
                        <li v-if="permissions.zones" :class="$route.path == `/zones` ? `active nav-item` : ``">
                          <router-link class="nav-link a-admin" to="/zones"><i class="fa fa-map"></i> Zones</router-link>
                        </li>
                        <li v-if="permissions.locations" :class="$route.path == `/locations` ? `active` : ``">
                            <router-link to="/locations"><i class="fa fa-map-marked-alt"></i> Locations</router-link>
                        </li>                  
                      </ul>
                    </div>
                  </li>
                  <li v-if="permissions.fleetManagement">
                    <a data-bs-toggle="collapse" href="#us_ma" aria-expanded="false" aria-controls="collapseExample"><i data-feather="truck"></i> Fleet Management</a>
                    <div class="collapse" id="us_ma">
                      <ul class="collapse list-unstyled" id="us_ma">
                        <li v-if="permissions.fleetView" :class="$route.path == `/fleetView` ? `active` : ``">
                            <router-link to="/fleetView"><i class="fa fa-shipping-fast"></i> Fleet</router-link>
                        </li>
                        <li v-if="permissions.routeList" :class="$route.path == `/routeList` ? `active` : ``">
                            <router-link to="/routeList"><i class="fa fa-route"></i> Routes</router-link>
                        </li>
                        <li v-if="permissions.driversList" :class="$route.path == `/driversList` ? `active` : ``">
                            <router-link to="/driversList"><i class="fa fa-user-alt"></i> Drivers</router-link>
                        </li>
                        <li v-if="permissions.visitsList" :class="$route.path == `/visitsList` ? `active` : ``">
                            <router-link to="/visitsList"><i class="fa fa-store-alt"></i> Visits</router-link>
                        </li>                         
                      </ul>
                    </div>
                  </li>
                  <li v-if="permissions.userManagement">
                    <a data-bs-toggle="collapse" href="#u_m" aria-expanded="false" aria-controls="collapseExample"><i data-feather="user"></i> User Management</a>
                    <div class="collapse" id="u_m">
                      <ul class="collapse list-unstyled" id="u_m">
                        <li v-if="permissions.staffManagement"  :class="$route.path == `/staffManagement` ? `active` : ``">
                            <router-link to="/staffManagement"><i class="fa fa-users"></i> Staff Management</router-link>
                        </li>
                        <li v-if="permissions.rolePermission" :class="$route.path == `/rolePermission` ? `active` : ``">
                            <router-link to="/rolePermission"><i class="fa fa-key"></i> Role Permission</router-link>
                        </li>
                        <li v-if="permissions.agents"  :class="$route.path == `/agents` ? `active` : ``">
                            <router-link to="/agents"><i class="fa fa-user-tie"></i> Commission Agents</router-link>
                        </li>
                        <li v-if="permissions.paymentRequests" :class="$route.path == `/paymentRequests` ? `active` : ``">
                            <router-link to="/paymentRequests"><i class="fa fa-hand-holding-usd"></i> Payment Requests</router-link>
                        </li>
                        <li v-if="permissions.creditServices" :class="$route.path == `/creditServices` ? `active` : ``">
                            <router-link to="/creditServices"><i class="fa fa-credit-card"></i> Credit Services</router-link>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li v-if="permissions.salesReport" :class="$route.path == `/salesReport` ? `active nav-item` : ``">
                    <router-link class="nav-link a-admin" to="/salesReport"><i data-feather="trending-up"></i> Sales Report</router-link>
                  </li>
                  <li v-if="permissions.customers" :class="$route.path == `/customers` ? `active nav-item` : ``">
                    <router-link class="nav-link a-admin" to="/customers"><i data-feather="users"></i> Customers</router-link>
                  </li>
                </ul>
              </nav> 
              </div>
               
            </div>
          
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
                                      <span class="fa fa-bell pr-2" style="font-size: 20px"></span><span v-if="$store.state.auth.notifications.length > 0" class="badge bg-danger mx-1">{{$store.state.auth.notifications.length}}</span><strong></strong>
                                  </a>
                                  <ul class="dropdown-menu rounded-1" aria-labelledby="navbarDropdown" style="max-height:500px; min-width:400px; overflow-y: auto; overflow-x:hidden">
                                    <li>
                                      <div v-if="$store.state.auth.notifications.length > 0" class="row m-0">
                                        <div class="col-md-12">
                                          <h6 class="text-end"><span @click="markAllAsRead()" class="pt-3 px-2" style="cursor:pointer"><span class="fa fa-check-double"></span> Mark all as read.</span></h6>
                                        </div>
                                      </div>
                                      
                                    </li>
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
                                    <li v-if="$store.state.auth.notifications.length == 0">
                                      <div class="row m-0 bg-light border-bottom">
                                        <div class="col-md-1 align-self-center text-center">
                                          <span class="fa fa-bell-slash"></span>
                                        </div>
                                        <div class="col-md-11 px-3 py-2">
                                          <h6 class="m-0">There are 0 notifications.</h6>
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
          <notifications group="main"
                   :duration="5000"
                   :width="500"
                   animation-name="v-fade-left"
                   position="top right">

          <template slot="body" slot-scope="props">
            <div class="custom-template bg-light">
              <div class="custom-template-icon">
                <i class="fa fa-bell"></i>
              </div>
              <div class="custom-template-content">
                <div class="custom-template-title">
                  {{props.item.title}}

                  <p>
                    {{props.item.text}}
                  </p>
                </div>
                <div class="custom-template-text"
                    v-html="props.item.text"></div>
              </div>
              <div class="custom-template-close"
                  @click="props.close">
                <i class="fa fa-times"></i>
              </div>
            </div>
          </template>
        </notifications>
        </div>
    </div>
  </div>
  </template>
<script>
  export default {
    data(){
      return{
        authenticated:false,
        notificationAudio:'/storage/settings/notif.mp3',
        permissions:{}
      }
    },
    mounted(){
      
      this.authenticated = this.$store.state.auth.authenticated
      
      this.$store.dispatch('auth/permissions')
      .then( () =>{
          
        this.permissions = this.$store.state.auth.permissions
      })
      this.getNotifications()
      this.connect();
      this.connectOnline();
      feather.replace();
    },
    updated(){
      feather.replace();
    },
    created() {
      window.addEventListener("beforeunload", this.leaving);
    }, 
    computed: {
      togggle(){
          this.authenticated = this.$store.state.auth.authenticated; 
      },
      
    },
    methods:{
      leaving(){
        this.goOffline()
      },
      connectOnline(){
        window.Echo.join(`online.0`)
        .here(()=>{
          this.goOnline();
        } )
        .joining((me)=>{
          this.goOnline();
        })
        .leaving((me)=>{
          this.goOffline();
        })
        .listen('NewMessage', (e) => {
            //
        });
      },
      async goOnline(){
        await axios.post('/goOnline')
        .then( response =>{

        })
      },
      async goOffline(){
        await axios.post('/goOffline')
        .then( response =>{

        })
      },
      disconnect(){
        window.Echo.leave(`online.0`);
      },
      async markAllAsRead(){
        await axios.get('/markAllAsRead')
        .then( response =>{
          this.getNotifications()
        })
      },
      notif(){
        this.$notify({
                  group: 'main',
                  type: 'warning',
                  title: 'Notification',
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
                    group: 'main',
                    type: 'success',
                    title: 'Notification',
                    text: notification.message
                });
                this.playSound()
                this.getNotifications()
            });
        },
        playSound(){
              var audio = new Audio(this.notificationAudio)
              //audio.muted = true;
              audio.play();
        },
      logout(){
          axios.post("logout").then(response => { 
          window.location.replace("/home");
          })
          .catch(error => {
          
          });
      },
    }
  }
</script>
<style lang="scss">
/*
  EXAMPLES
*/
.notification.n-light {
  margin: 10px;
  margin-bottom: 0;
  border-radius: 3px;
  font-size: 13px;
  padding: 10px 20px;
  color: #495061;
  background: #EAF4FE;
  border: 1px solid #D4E8FD;
  .notification-title {
    letter-spacing: 1px;
    text-transform: uppercase;
    font-size: 10px;
    color: #2589F3;
  }
}
.custom-template {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
  text-align: left;
  font-size: 13px;
  margin: 5px;
  margin-bottom: 0;
  align-items: center;
  justify-content: center;
  &, & > div {
    box-sizing: border-box;
  }
  background: #E8F9F0;
  border: 2px solid #D0F2E1;
  .custom-template-icon {
    flex: 0 1 auto;
    color: #15C371;
    font-size: 32px;
    padding: 0 10px;
  }
  .custom-template-close {
    flex: 0 1 auto;
    padding: 0 20px;
    font-size: 16px;
    opacity: 0.2;
    cursor: pointer;
    &:hover {
      opacity: 0.8;
    }
  }
  .custom-template-content {
    padding: 10px;
    flex: 1 0 auto;
    .custom-template-title {
      letter-spacing: 1px;
      text-transform: uppercase;
      font-size: 10px;
      font-weight: 600;
    }
  }
}
.v-fade-left-enter-active,
.v-fade-left-leave-active,
.v-fade-left-move {
  transition: all .5s;
}
.v-fade-left-enter,
.v-fade-left-leave-to {
  opacity: 0;
  transform: translateX(-500px) scale(0.2);
}
</style>