import axios from 'axios'
import VueAxios from 'vue-axios'
import Vue from 'vue'
Vue.use(VueAxios, axios)

class AxiosCalls {
    getRequest(baseURL,config) {
        return axios
            .get(baseURL, config)
    }

    postRequest(baseURL,params,config) {
        return axios
            .post(baseURL, params, config)
            .catch(err => {
                console.log(err)
            })
    }
}

export default new AxiosCalls();
