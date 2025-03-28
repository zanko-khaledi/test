import {useAuthStore} from "@/stores/useAuthStore.js";
import axios, {Axios} from "axios";
class ApiService {

    constructor(basePath = "/api/v1") {
        this.authStore = useAuthStore();
        this.request = axios.create({
            baseURL : basePath
        });

        this.setAuthHeader();
    }

    setAuthHeader() {
        const token = localStorage.getItem("token");
        if (token) {
            this.request.defaults.headers.Authorization = `Bearer ${token}`;
        }
    }
    get(url = "", configs = {}) {
        this.setAuthHeader();
        return this.request.get(url, configs);
    }

    post(url = "", params = {}, configs = {}) {
        this.setAuthHeader();
        return this.request.post(url, params, configs);
    }

    put(url = "", params = {}, configs = {}) {
        this.setAuthHeader();
        return this.request.put(url, params, configs);
    }

    patch(url = "", params = {}, configs = {}) {
        this.setAuthHeader();
        return this.request.patch(url, params, configs);
    }

    remove(url = "", configs = {}) {
        this.setAuthHeader();
        return this.request.delete(url, configs);
    }
}

export default ApiService;
