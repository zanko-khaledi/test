<template>
        <div>
            <nav class="navbar navbar-expand-lg bg-body-tertiary" v-if="!!userAuth.isAuthenticated() && !['/login','/register'].includes(route.path)">
                <div class="container">
                    <a class="navbar-brand" href="#">Navbar</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav">
                            <router-link to="/tasks" class="nav-link">Tasks</router-link>
                        </div>
                        <div class="navbar-nav">
                            <a class="nav-link" href="#" @click="logout">Exit</a>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="container-fluid">
                <div class="p-2">
                    <router-view></router-view>
                </div>
            </div>
        </div>
</template>

<script>

import {computed, defineComponent} from "vue";
import {useAuthStore} from "@/stores/useAuthStore.js";
import {useRoute, useRouter} from "vue-router";
import LoadingBar from "@/components/utils/LoadingBar.vue";
export default defineComponent({
    components : {
        'v-loading' : LoadingBar
    },
    setup(){
        const userAuth = useAuthStore();
        const router = useRouter();
        const route = useRoute();


        const logout = ()=> {
            userAuth.logout().then(res => {
                if(res.status === 200){
                    router.push({
                        path : '/login'
                    });
                }
            })
        }

        return {
            userAuth,
            route,
            logout
        }
    }
})

</script>

<style scoped>

</style>
