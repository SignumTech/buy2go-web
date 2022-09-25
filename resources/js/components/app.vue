<template>
    <div class="container-fluid p-0 overflow-hidden mob_hide" style="height: 100vh">
      <div class="row m-0">
          <div class="p-0 shadow-sm" style="background-color:#fff; width:18%" >
              <div class="row m-0" style="background-color: #ff6600;">
                  <div class="col-md-12 mt-5 mb-5">
                      <div class="rounded-circle shadow-sm m-auto d-flex align-items-center overflow-hidden" style="width: 100px; height: 100px;">
                          <img class="img img-fluid d-block m-auto" src="/storage/settings/logo.jpg" style="width:200px; height: auto">
                      </div>
                  </div>
              </div>
              <nav id="sidebar" class="border-end" style="height: 100vh; overflow-y: auto">
                <ul class="list-unstyled components">
                  <li :class="$route.path == `/admin/adminDash` ? `active nav-item` : ``">
                    <router-link class="nav-link a-admin" to="/admin/adminDash"><i data-feather="home"></i> Dashboard <span class="sr-only">(current)</span></router-link>
                  </li>
                  <li :class="$route.path == `/admin/products` ? `active nav-item` : ``">
                    <router-link class="nav-link a-admin" to="/admin/products"><i data-feather="box"></i> Products</router-link>
                  </li>
                  <li :class="$route.path == `/admin/orders` ? `active nav-item` : ``">
                    <router-link class="nav-link a-admin" to="/admin/orders"><i data-feather="shopping-cart"></i> Orders</router-link>
                  </li>
                  <li :class="$route.path == `/admin/categories` ? `active nav-item` : ``">
                    <router-link class="nav-link a-admin" to="/admin/categories"><i data-feather="grid"></i> Categories</router-link>
                  </li>
                  <li>
                    <a data-bs-toggle="collapse" href="#us_ma" aria-expanded="false" aria-controls="collapseExample"><i data-feather="gift"></i> Marketing</a>
                    <div class="collapse" id="us_ma">
                      <ul class="collapse list-unstyled" id="us_ma">
                        <li :class="$route.path == `/admin/flashSale` ? `active` : ``">
                            <router-link to="/admin/flashSale"><i data-feather="zap"></i> Flash Sale</router-link>
                        </li>
                        <li :class="$route.path == `/admin/regularSale` ? `active` : ``">
                            <router-link to="/admin/regularSale"><i data-feather="award"></i> Regular Sale</router-link>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li :class="$route.path == `/users` ? `active nav-item` : ``">
                    <router-link class="nav-link a-admin" to="/users"><i data-feather="user"></i> Users Management</router-link>
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
          <div class="p-0" style="width: 82%">
              <nav class="navbar navbar-expand-md navbar-dark shadow-sm" style="background-color: #fff; height: 55px">
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
                                <li class="px-5">
                                    <router-link class="a-admin" to="/profile">
                                        <span class="fa fa-user-cog pr-2" style="font-size: 20px"></span><strong>  {{$store.state.auth.user.f_name}} {{$store.state.auth.user.l_name}}</strong>
                                    </router-link>
                                </li>
                                <li>
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
            
          </div>
      </div>
    </div>
    </template>
    <script>
    export default {
        mounted(){
            feather.replace();
        },
        methods:{
            logout: function(){
                axios.post("logout").then(response => { 
                    window.location.replace("/signin");
                })
                .catch(error => {
                
                });
            },
        }
    }
    </script>