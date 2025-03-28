<template>
    <div class="col-md-6 mx-auto mt-5">
        <div class="card">
            <div class="card-header">
                <h5>Sign in</h5>
            </div>
            <div class="card-body">
               <Form @submit.prevent="true" :validation-schema="schema">
                   <div>
                       <Field name="email" type="email"  v-model="email" class="form-control" placeholder="Email..." />
                       <ErrorMessage class="text-danger" name="email" />
                   </div>
                   <div class="mt-2">
                       <Field name="password" type="password" class="form-control"  placeholder="Password..." v-model="password" />
                       <ErrorMessage class="text-danger" name="password" />
                   </div>
                   <div class="text-end">
                       <button type="submit" class="btn btn-primary btn-sm mt-2" @click="handleRequest">Sign in</button>
                       <router-link to="/register" class="btn btn-link btn-sm">Sign up</router-link>
                   </div>
               </Form>
            </div>
        </div>
    </div>
</template>
<script>
import {defineComponent, ref, watchEffect} from "vue";
import * as Yup from "yup";
import {Form,Field,ErrorMessage} from "vee-validate";
import {useAuthStore} from "@/stores/useAuthStore.js";
import {useRouter} from "vue-router";

export default defineComponent({
    components : {
        Form,Field,ErrorMessage
    },
    setup(){

        watchEffect(()=> {
            document.title = "Login";
        });

        const email = ref("");
        const password = ref("");
        const authStore = useAuthStore();
        const router = useRouter();

        const schema = Yup.object().shape({
            email : Yup.string().required().email(),
            password : Yup.string().required().min(6).max(20)
        });
        const handleRequest = ()=> {
            const data = {
                email : email.value,
                password : password.value
            };

            schema.validate(data,{abortEarly:false}).then(res => {
                authStore.login(data).then(res => {
                    if(res.status === 201){
                         router.push({
                            path : '/tasks'
                        });
                    }
                }).catch(err => {

                })
            }).catch(err => {
                const formattedErrors = err.inner.reduce((acc, curr) => {
                    acc[curr.path] = curr.message;
                    return acc;
                }, {});
                console.log(formattedErrors);
            });
        }

        return {
            schema,
            handleRequest,
            email,
            password
        }
    }
})
</script>
<style scoped>

</style>
