<template>
    <h4>Home Component</h4>
    <button @click="logout">Logout</button>
</template>

<script>

import {defineComponent,watchEffect} from "vue";
import {useAuthStore} from "@/stores/useAuthStore.js";
import {useRoute} from "vue-router";
import {useRouter} from "vue-router";

export default defineComponent({
    name : "Home",

    setup(){

        watchEffect(()=> {
            document.title = "Home Page";
        });

        const useAuth = useAuthStore();
        const router = useRouter();

        const logout = ()=> {
            useAuth.logout().then(res => {
                localStorage.removeItem('token');
                router.push({
                    path : "/"
                });
            });
        }

        return {
            logout
        }
    }
})

</script>

<style scoped>

</style>
