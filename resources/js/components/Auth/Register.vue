<template>
    <div class="col-md-6 mx-auto mt-5" v-if="!isLoading">
        <div class="card">
            <div class="card-header">
                <h5>Sign up</h5>
            </div>
            <div class="card-body">
                <Form @submit="handleRequest" :validation-schema="schema">
                    <div>
                        <Field type="text" name="first_name" placeholder="First name..." class="form-control" v-model="state.first_name" />
                        <ErrorMessage name="first_name" class="text-danger" />
                    </div>
                    <div class="mt-2">
                        <Field type="text" name="last_name" placeholder="Last name..." class="form-control" v-model="state.last_name" />
                        <ErrorMessage name="last_name" class="text-danger" />
                    </div>
                    <div class="mt-2">
                        <Field type="email" name="email" placeholder="Email..." class="form-control" v-model="state.email" />
                        <ErrorMessage name="email" class="text-danger" />
                    </div>
                    <div class="mt-2">
                        <Field type="password" name="password" placeholder="Password..." class="form-control" v-model="state.password" />
                        <ErrorMessage name="password" class="text-danger" />
                    </div>
                    <div class="text-end mt-2">
                        <button type="submit" class="btn btn-primary btn-sm" >Sign up</button>
                        <router-link to="/login" class="btn btn-link mx-1 btn-sm">Sign in</router-link>
                    </div>
                </Form>
            </div>
        </div>
    </div>
    <v-loading v-else />
</template>

<script>
import {defineComponent, reactive, ref} from "vue";
import * as Yup from "yup";
import {Form, Field, ErrorMessage, useForm} from "vee-validate";
import {useAuthStore} from "@/stores/useAuthStore.js";
import router from "@/configs/routes.js";
import { useRouter} from "vue-router";
import {toast} from "vue3-toastify";

export default defineComponent({
   components : {
       Form,Field,ErrorMessage
   },
   setup(){

       const authStore = useAuthStore();
       const router = useRouter();
       const state = reactive({
          first_name : "",
          last_name : "",
          email : "",
          password : ""
       });
       const isLoading = ref(false);

       const setLoading = (loading=false) => {
           isLoading.value = loading;
       }

       const schema = Yup.object().shape({
           first_name : Yup.string().required("First name required"),
           last_name : Yup.string().required("Last name required"),
           email : Yup.string().email().required(),
           password : Yup.string().required().min(6).max(20)
       });

       const {handleSubmit} = useForm();
       const handleRequest = handleSubmit(()=> {
           setLoading(true);
           authStore.register(state).then(res => {
               if(res){
                   router.push({
                       path : '/tasks'
                   });
               }else {
                   router.push({
                       path : '/register'
                   });
               }
           }).catch(err => {
               router.push({
                   path : '/register'
               });
               toast.warning(err.response.data.message ?? "You've got an error!");
           }).finally(()=> {
               setLoading(false);
           })
       });

       return {
           state,
           schema,
           handleRequest,
           isLoading
       }
   }
});
</script>

<style scoped>

</style>
