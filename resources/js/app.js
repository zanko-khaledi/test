import './bootstrap';
import {createApp} from "vue";
import router from "@/configs/routes.js";
import {createPinia} from "pinia";
import  {configure} from "vee-validate";

import App from "@/App.vue";

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import {useAuthStore} from "@/stores/useAuthStore.js";
import 'vue3-toastify/dist/index.css';


import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';


import {Form,Field,ErrorMessage} from "vee-validate";
import LoadingBar from "@/components/utils/LoadingBar.vue";

const app = createApp(App);

configure({
    validateOnBlur: true,
    validateOnChange: true,
    validateOnInput: true,
    validateOnModelUpdate: true,
});


app.component('Form',Form);
app.component('Field',Field);
app.component('ErrorMessage',ErrorMessage);
app.component('v-loading',LoadingBar);

app.use(createPinia());
app.use(router);
app.use(VueSweetalert2);

// global middleware

const authStore = useAuthStore();
const isAuthenticate = ()=> !!authStore.isAuthenticated();

router.beforeEach((to,from,next)=> {
    if(to.meta?.requiredAuth && !isAuthenticate()){
        next("/login");
    }else {
        next();
    }
});

app.mount("#app");
