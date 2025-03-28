import {defineStore} from "pinia";
import ApiService from "@/configs/ApiService.js";
import {toast} from "vue3-toastify";
import {ref} from "vue";
import {Axios} from "axios";

export const useTaskStore = defineStore('tasks', () => {

    const tasks = ref([]);
    const task = ref({});
    const tasksPagination = ref({});
    const url = ref("/tasks");

    const getUrl = () => {
        return url.value;
    }

    const setUrl = (endpoint) => {
        url.value = endpoint;
    }


    const setTask = (values) => {
        return new Promise((resolve, reject) => {
            const request = new ApiService();
            request.post('/tasks', values).then((res) => {
                if (res.status === 201) {
                    toast.success('New task successfully created.');
                    resolve(res);
                }
            }).catch(err => {
                toast.warning(err.response?.data?.message);
                reject(err);
            });
        })
    }

    const setTasksPagination = (payload) => {
        tasksPagination.value = {
            current_page: payload["current_page"],
            from: payload["from"],
            to: payload["to"],
            per_page: payload["per_page"],
            total: payload["total"],
        };
    }

    const fetchTasks = () => {
        return new Promise((resolve, reject) => {
            const request = new ApiService();
            request.get(getUrl()).then(res => {
                tasks.value = res.data;
                resolve(res);
            }).catch(err => {
                reject(err);
            })
        });
    }

    const fetchTask = (id) => {
        return new Promise((resolve, reject) => {
            const request = new ApiService();
            request.get(`/tasks/${id}`).then(res => {
                task.value = res.data;
                resolve(res);
            }).catch(err => {
                reject(err);
            });
        });
    }

    const destroyTask = (id) => {

        return new Promise((resolve, reject) => {
            const request = new ApiService();
            request.remove(`/tasks/${id}`, {
                headers: {
                    "Content-Type": "application/json"
                }
            }).then(res => {
                resolve(res);
            }).catch(err => {
                reject(err);
            });
        });
    }

    const updateStatus = (id, status) => {
        return new Promise((resolve, reject) => {
            const request = new ApiService();
            request.patch(`/tasks/${id}/update-status`, {status}).then(res => {
                resolve(res);
            }).catch(err => {
                reject(err);
            });
        })
    }

    const update = (id, data = {}) => {
        return new Promise((resolve, reject) => {
            const request = new ApiService();
            request.patch(`/tasks/${id}`, data, {
                headers: {
                    "Content-Type": "application/json"
                }
            }).then(res => {
                resolve(res);
            }).catch(err => {
                reject(err);
            });
        });
    }

    return {
        setTask,
        fetchTasks,
        fetchTask,
        tasks,
        task,
        tasksPagination,
        destroyTask,
        updateStatus,
        url,
        setUrl,
        update
    }
});
