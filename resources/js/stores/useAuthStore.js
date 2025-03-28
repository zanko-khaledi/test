import {defineStore} from "pinia";
import {ref} from "vue";
import ApiService from "@/configs/ApiService.js";
import {toast} from "vue3-toastify";
export const useAuthStore = defineStore('auth',()=> {

    const jwtAuth = ref("");

    const getJwtAuth = ()=> {
        return jwtAuth.value || localStorage.getItem('token');
    }

    const isAuthenticated = ()=> {
        return !!getJwtAuth();
    }
    const setJwtAuth = (token)=> {
        localStorage.setItem('token',token);
        jwtAuth.value = localStorage.getItem('token');
    }
    const login = (params = {})=> {
        return new Promise((resolve,reject)=> {
            const request = axios.create({
                baseURL : "/api/v1",
                headers : {
                    "Content-Type" : "application/json"
                }
            }).post('/login',params).then(res => {
                if(res.status === 201){
                    setJwtAuth(res.data.token);
                    resolve(res);
                }else {
                    reject(res);
                }
            }).catch(err => {
                reject(err);
                toast.warning(err.response.data.message);
            })
        });
    }

    const register = (params = {})=> {
        return new Promise((resolve,reject)=> {
            const request = axios.create({
                baseURL : "/api/v1",
                headers : {
                    "Content-Type" : "application/json"
                }
            }).post('/register',params).then(res => {
                if(res.status === 201){
                    setJwtAuth(res.data.token);
                    resolve(true);
                }else {
                    reject(false);
                }
            }).catch(err => {
                reject(err);
            })
        });
    }

    const logout = ()=> {
        return new Promise((resolve,reject)=> {
            const request = new ApiService();
            request.get('/logout').then(res => {
                localStorage.removeItem('token');
                resolve(res);
            }).catch(err => {
                console.log(err);
            });
        })
    }

    return {
        jwtAuth,
        getJwtAuth,
        register,
        isAuthenticated,
        login,
        logout
    }
});
