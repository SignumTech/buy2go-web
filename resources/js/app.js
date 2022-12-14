/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import store from './store'
import router from './router'
import VModal from 'vue-js-modal'
import 'vue-js-modal/dist/styles.css'
import OtpInput from "@bachdgvn/vue-otp-input";
import Notifications from 'vue-notification'
import {ColorPicker, ColorPanel} from 'one-colorpicker'
import numeral from 'numeral';
import numFormat from 'vue-filter-number-format';
import * as VueGoogleMaps from 'vue2-google-maps'
import VueQRCodeComponent from 'vue-qr-generator'
import VueGeolocation from 'vue-browser-geolocation';
import VueCountryCode from "vue-country-code";
import VueTelInput from 'vue-tel-input';
import 'vue-tel-input/dist/vue-tel-input.css';
import LvInput from 'lightvue/input' 
import LvButton from 'lightvue/button' 
/////////////////////////////////////////////
import Vue from 'vue';
import VueHtmlToPaper from 'vue-html-to-paper';

const options = {
  name: '_blank',
  specs: [
    'fullscreen=yes',
    'titlebar=no',
    'scrollbars=no'
  ],
  styles: [
    'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css',
    'https://unpkg.com/kidlat-css/css/kidlat.css',
    'https://use.fontawesome.com/releases/v5.7.0/css/all.css'
  ],
  timeout: 1000, // default timeout before the print window appears
  autoClose: true, // if false, the window will not close after printing
  windowTitle: window.document.title, // override the window title
}

Vue.use(VueHtmlToPaper, options);

// or, using the defaults with no stylesheet
Vue.use(VueHtmlToPaper);
/////////////////////////////////////////////
Vue.component('LvInput', LvInput);
Vue.component('LvButton', LvButton);
Vue.use(VueTelInput);
Vue.use(VueCountryCode);
Vue.use(VueGeolocation);
Vue.component('qr-code', VueQRCodeComponent)
Vue.component('google-map', VueGoogleMaps.Map);
Vue.component('google-marker', VueGoogleMaps.Marker);
Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyCRNebshVW6XSdv4X2Nxm3FGIt3qbA7UKU',
    libraries: 'places,directions,geometry,distance_matrix' // This is required if you use the Autocomplete plugin
    // OR: libraries: 'places,drawing'
    // OR: libraries: 'places,drawing,visualization'
    // (as you require)
 
    //// If you want to set the version, you can do so:
    // v: '3.26',
  },
 
  //// If you intend to programmatically custom event listener code
  //// (e.g. `this.$refs.gmap.$on('zoom_changed', someFunc)`)
  //// instead of going through Vue templates (e.g. `<GmapMap @zoom_changed="someFunc">`)
  //// you might need to turn this on.
  // autobindAllEvents: false,
 
  //// If you want to manually install components, e.g.
  //// import {GmapMarker} from 'vue2-google-maps/src/components/marker'
  //// Vue.component('GmapMarker', GmapMarker)
  //// then disable the following:
  // installComponents: true,
})
Vue.filter('numFormat', numFormat(numeral));
Vue.use(ColorPanel)
Vue.use(ColorPicker)
Vue.use(Notifications)
Vue.component("v-otp-input", OtpInput);
Vue.use(VModal)
Vue.use(require('vue-moment'));
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('app', require('./components/app.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 store.dispatch('auth/permissions')
 store.dispatch('auth/me').then(() => {
    router.beforeEach((to, from, next) => {
        
        if (to.name !== 'Login' && !store.state.auth.authenticated ){
          next({ name: 'Login' })
        } 
        else{
          next()
        
        } 
     })
    const app = new Vue({
        el: '#app',
        store,
        router
    });
});
