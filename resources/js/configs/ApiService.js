import HTTPRequest from "@/configs/HTTPRequest.js";
import {useAuthStore} from "@/stores/useAuthStore.js";
import {Axios} from "axios";
class ApiService {

    constructor(basePath = "/api/v1") {
        this.authStore = useAuthStore();
        this.request = HTTPRequest({
            baseURL : basePath,
            headers : {
                Authorization: `Bearer ${this.authStore.getJwtAuth()}`
            }
        })
    }
    get(url="",configs = {}){
        return this.request.get(url,configs);
    }

    post(url = "",params = {},configs = {}){
        return this.request.post(url,params,configs);
    }

    put(url="",params={},configs = {}){
         return this.request.put(url,params,configs);
    }

    patch(url = "",params = {},configs = {}){
         return this.request.patch(url,params,configs)
    }

    remove(url = "",configs = {}){
        return this.request.delete(url,configs);
    }
}

export default ApiService;
