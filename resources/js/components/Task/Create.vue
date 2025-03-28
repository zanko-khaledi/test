<template>
    <div class="container card col-md-10 mx-auto" v-if="!isLoading">
        <div class="card-header">
            <h4>Create you'r task</h4>
            <div class="mt-1">
                <router-link to="/tasks" class="btn btn-primary btn-sm">Task list</router-link>
            </div>
        </div>
        <div class="card-body">
            <div>
                <Form @submit="saveEvent" :validation-schema="schema">
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
    </div >
    <v-loading v-else />
</template>

<script>
import {defineComponent, reactive, watchEffect,ref} from "vue";
import * as yup from 'yup';
import {useForm} from "vee-validate";
import {useTaskStore} from "@/stores/useTaskStore.js";
import {useRouter} from "vue-router";
import {toast} from "vue3-toastify";

export default defineComponent({

    setup() {

        watchEffect(() => {
            document.title = 'Tasks | create';
        });

        const state = reactive({
            title: '',
            description: '',
            status: -1,
            started_at: '',
            ended_at: ''
        });
        const {handleSubmit, errors} = useForm();
        const taskStore = useTaskStore();
        const router = useRouter();
        const isLoading = ref(false);

        const setLoading = (value)=> {
            isLoading.value = value;
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

        const saveEvent = handleSubmit(values => {
             setLoading(true);
             taskStore.setTask(state).then((res)=> {
                 router.push({
                      path : '/tasks'
                  });
             }).catch(err => {
                 toast.warning(err.response.data.message || "You've got an error!");
             }).finally(()=> {
                 setLoading(false);
             });
        });

        return {
            schema,
            saveEvent,
            state,
            isLoading
        }
    }
})
</script>

<style scoped>
.text-danger-2 {
    color: darkred;
}
</style>
