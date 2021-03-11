// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
// import './bootstrap';
import Vue from 'vue'
import App from './App.vue'

// router
import VueRouter from 'vue-router'
import router from "./router/app.js";
Vue.use(VueRouter);

// Bootstrap vue
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// font awesome
import '@fortawesome/fontawesome-free/css/all.css'
import '@fortawesome/fontawesome-free/js/all.js'

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
new Vue({
    el: '#app',
    router,
    // store,
    components: { App },
});
Vue.filter('fixDateTimezone',function(date){
    return   new Date(new Date(date).getTime() + (new Date().getTimezoneOffset() * 60000))
})
