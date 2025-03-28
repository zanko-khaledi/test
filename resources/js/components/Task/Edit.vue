<template>
   <div class="container" v-if="!isLoading">
       <div class="card">
           <div class="card-header">
               <h4>Task edit</h4>
               <div class="mt-1">
                   <router-link to="/tasks" class="btn btn-primary btn-sm">Task list</router-link>
               </div>
           </div>
           <div class="card-body">
               <Form @submit="handleForm" :validation-schema="schema">
                   <div class="col-8">
                       <Field name="title" class="form-control" placeholder="Title..." v-model="state.title"/>
                       <ErrorMessage name="title" class="text-danger-2"/>
                   </div>
                   <div class="mt-2">
                       <Field name="description" as="textarea" class="form-control" placeholder="Description..." v-model="state.description" />
                       <ErrorMessage name="description" class="text-danger-2"/>
                   </div>
                   <div class="mt-2 col-8">
                       <Field name="status" as="select" class="form-control" placeholder="Status..." model-value="-1"
                              v-slot="{options}" v-model="state.status">
                           <option value="-1" disabled selected>Status</option>
                           <option value="0">In Progress</option>
                           <option value="1">Done</option>
                       </Field>
                       <ErrorMessage name="status" class="text-danger-2"/>
                   </div>
                   <div class="mt-2 col-8">
                       <Field name="started_at" v-slot="{ field }" v-model="state.started_at">
                           <div class="d-flex text-center">
                               <label class="col-2 my-auto">Started at</label>
                               <input
                                   type="datetime-local"
                                   class="form-control"
                                   v-bind="field"
                               >
                           </div>
                       </Field>
                       <ErrorMessage name="started_at" class="text-danger-2"/>
                   </div>
                   <div class="mt-2 col-8">
                       <Field name="ended_at" v-slot="{field}" label="Ended at" v-model="state.ended_at">
                           <div class="d-flex text-center">
                               <label class="col-2 my-auto text-center">Ended at</label>
                               <input type="datetime-local" class="form-control" v-bind="field">
                           </div>
                       </Field>
                       <ErrorMessage name="ended_at" class="text-danger-2"/>
                   </div>
                   <div class="text-end">
                       <button class="btn btn-primary btn-sm" type="submit">Save</button>
                       <router-link to="/tasks" class="btn btn-outline-primary btn-sm mx-1">Cancel</router-link>
                   </div>
               </Form>
           </div>
       </div>
   </div>
   <v-loading v-else />
</template>
<script>
import {computed, defineComponent,onBeforeMount, reactive, ref, watchEffect} from "vue";
import {useRoute,useRouter} from "vue-router";
import {useTaskStore} from "@/stores/useTaskStore.js";
import {useForm} from "vee-validate";
import * as yup from "yup";
import {toast} from "vue3-toastify";

export default defineComponent({

    setup(){

        watchEffect(()=> {
            document.title = "Tasks | edit";
        });

        const route = useRoute();
        const router = useRouter();
        const taskStore = useTaskStore();
        const isLoading = ref(false);

        const state = reactive({
            title: '',
            description: '',
            status:  -1,
            started_at: '',
            ended_at: ''
        });

        const setLoading = (value = false)=> {
            isLoading.value = value;
        }

        onBeforeMount(()=> {
           loadTask(true);
        });

        const setTask = ({data})=> {
            state.title = data.title;
            state.description = data.description;
            state.status = data.status;
            state.started_at = data.started_at;
            state.ended_at = data.ended_at;
        }
        const loadTask = (isLoading = false) => {
              setLoading(isLoading);
              taskStore.fetchTask(route.params.id).then(res => {
                  setLoading(false);
                  setTask(res);
              }).catch(err => {
                  setLoading(false);
              });
        }

        const schema = yup.object().shape({
            title: yup.string().required('Title required!'),
            description: yup.string().required('Description required!'),
            status: yup.number().required().oneOf([0, 1], "Select In progress or Done"),
            started_at: yup.date()
                .typeError('Invalid date')
                .required('Started at is required'),
            ended_at: yup.date()
                .typeError('Invalid date')
                .min(yup.ref('started_at'), 'Ended at must be after Started at')
        });
        const handleForm = () => {
            setLoading(true);
            taskStore.update(route.params.id,state).then(res => {
                if(res.status === 200){
                    toast.success('Task successfully updated.')
                    router.push({
                        path : "/tasks"
                    });
                    setLoading(false);
                }
            }).catch(err => {
                toast.warning(err.response?.data?.message ?? "You've fot an error!");
                console.log(err);
            }).finally(()=> {
                setLoading(false);
            });
        };

        return {
            state,
            isLoading,
            handleForm,
            schema
        }
    }
});
</script>

<style scoped>

</style>
