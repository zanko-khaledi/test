<template>
    <div class="card" v-if="!isLoading">
        <div class="card-header">
            <h4>Tasks</h4>
            <div class="row">
                <div class="mt-1 col-1">
                    <router-link to="/tasks/create" class="btn btn-primary btn-sm">Add task</router-link>
                </div>
                <div class="col-3">
                    <input class="form-control" v-model="searchForm.title" placeholder="Title...">
                </div>
                <div class="col-3">
                    <select class="form-control" v-model="searchForm.status">
                        <option value="-1" selected >Select...</option>
                        <option value="0">In Progress</option>
                        <option value="1">Done</option>
                    </select>
                </div>
                <div class="col-3" >
                    <button class="btn btn-info" @click="search">Search</button>
                    <button class="btn btn-outline-info mx-1" @click="removeFilter">Remove filter</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-borderless table-striped">
                <thead>
                <tr>
                    <th class="sort" @click="sort('id')"><span>#</span><span><i :class="['bi',searchForm.dir == 'desc' ? 'bi-sort-up' : 'bi-sort-down']"></i></span></th>
                    <th>Title</th>
                    <th>Description</th>
                    <th class="sort" @click="sort('status')"><span>Status</span><span><i :class="['bi',searchForm.dir == 'desc' ? 'bi-sort-up' : 'bi-sort-down']"></i></span></th>
                    <th>Started at</th>
                    <th>Ended at</th>
                    <th class="sort" @click="sort('created_at')"><span>Crated at</span><span><i :class="['bi',searchForm.dir == 'desc' ? 'bi-sort-up' : 'bi-sort-down']"></i></span></th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in  tasks" :key="index">
                    <td>{{ item.id }}</td>
                    <td>{{ item.title || '' }}</td>
                    <td>{{ item.description?.toString().substring(0, 50) + '...' }}</td>
                    <td>{{ getStatusText(item.status) }}</td>
                    <td>{{ item.started_at }}</td>
                    <td>{{ item.ended_at }}</td>
                    <td>{{ (new Date(item.created_at).toLocaleDateString()) }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm mx-2" data-bs-toggle="modal"
                                data-bs-target="#exampleModal" @click="triggerModal(item.id)">Update status
                        </button>
                        <router-link :to="{
                            path : `/tasks/${item.id}/edit`
                        }" class="btn btn-info btn-sm mx-1">Edit</router-link>
                        <button class="btn btn-danger btn-sm" @click="destroy(item.id)">delete</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <v-loading v-else/>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Status</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="btn-close"></button>
                </div>
                <Form @submit="handleStatus" :validation-schema="schema">
                    <div class="modal-body">
                        <div>
                            <Field name="status" v-slot="{filed}" as="select" class="form-control" model-value="-1"
                                   v-model="status">
                                <option value="-1" selected>Select status</option>
                                <option value="0">In Progress</option>
                                <option value="1">Done</option>
                            </Field>
                            <ErrorMessage name="status" class="text-danger-2"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
<script>

import {defineComponent, watchEffect, onMounted, ref, computed} from "vue";
import {useTaskStore} from "@/stores/useTaskStore.js";
import {toast} from "vue3-toastify";
import Swal from "sweetalert2";
import * as yup from "yup";
import {useForm} from "vee-validate";


export default defineComponent({

    setup() {

        watchEffect(() => {
            document.title = "Tasks";
        });

        const taskStore = useTaskStore();
        const isLoading = ref(false);
        const tasks = computed(() => {
            return taskStore.tasks;
        });
        const status = ref(-1);
        const taskId = ref(0);
        const searchForm = ref({
           sort_by : '',
           dir  : 'desc',
           title : '',
           status : -1
        });

        const sort = (column)=> {
            searchForm.value.sort_by = column;
            searchForm.value.dir = searchForm.value.dir === 'desc' ? 'asc' : 'desc';
            let url = `/tasks?sort_by=${searchForm.value.sort_by}&dir=${searchForm.value.dir}`;
            taskStore.setUrl(url);
            loadTasks(true);
        }

        const setLoading = (value = false) => {
            isLoading.value = value;
        }
        const loadTasks = async (isLoading) => {
            setLoading(isLoading);
            try {
                await taskStore.fetchTasks();
            } catch (e) {
                console.error("Error fetching tasks:", e);
                toast.warning(e.response.data.message || "You've got an error!");
            } finally {
                setLoading(false);
            }
        }

        const search = ()=> {
            let url = `/tasks?title=${searchForm.value.title}&status=${searchForm.value.status}`;
            taskStore.setUrl(url);
            loadTasks(true);
        }

        const removeFilter = ()=> {
            let url = `/tasks`;
            taskStore.setUrl(url);
            loadTasks(true);
        }

        onMounted(() => {
            loadTasks(true);
        });

        const getStatusText = (status) => {
            return status === 0 ? 'In Progress' : 'Done';
        };

        const destroy = (id) => {
            Swal.fire({
                title: "",
                text: "Do you want remote this task?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "yes",
                confirmButtonColor: "#293c70",
                cancelButtonText: "cancel",
                cancelButtonColor: "#ed1c24",
                heightAuto: false,
            }).then(async (result) => {
                console.log(result);
                if (result.isConfirmed) {
                    try {
                        await taskStore.destroyTask(id);
                    } catch (e) {
                        toast.error(e.response || "You've got an error!");
                        console.log(e);
                    } finally {
                        await loadTasks(true);
                    }
                }
            });
        }

        const schema = yup.object().shape({
            status: yup.number().required().oneOf([0, 1], "Select In Progress or Done!")
        });

        const triggerModal = id => {
            taskId.value = id;
        }

        const {handleSubmit} = useForm();
        const handleStatus = handleSubmit((values) => {
            setLoading(true);
            taskStore.updateStatus(taskId.value, status.value).then(res => {
                setLoading(false);
                closeModel();
            }).catch(err => {
                setLoading(false);
                toast.warning(err.response.data.message || "You've got an error!");
                console.log(err);
            }).finally(() => {
                setLoading(false);
                closeModel();
                loadTasks(true);
            })
        });

        const closeModel = () => {
            document.getElementById('btn-close').click();
        }

        return {
            tasks,
            isLoading,
            getStatusText,
            destroy,
            status,
            schema,
            triggerModal,
            handleStatus,
            sort,
            searchForm,
            search,
            removeFilter
        };
    }
})
</script>

<style scoped>
.text-danger-2 {
    color: darkred;
}
.sort:hover{
    cursor: pointer;
}
</style>
