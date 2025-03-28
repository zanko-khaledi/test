
import {createRouter,createWebHistory} from "vue-router";

import Home from "@/components/Home/Index.vue";
import Login from "@/components/Auth/Login.vue";
import Register from "@/components/Auth/Register.vue";
import TaskList from "@/components/Task/Index.vue";
import TaskCreate from "@/components/Task/Create.vue";
import TaskEdit from "@/components/Task/Edit.vue";

const routes = [
    {
       path : "/login",component: Login
    },
    {
        path : "/register",component: Register
    },
    {
        path: "/tasks",component: TaskList, meta: {
            requiredAuth: true
        }
    },
    {
        path : "/tasks/create",component: TaskCreate,meta: {
            requiredAuth: true
        }
    },
    {
        path : "/tasks/:id/edit",component: TaskEdit,meta: {
            requiredAuth: true
        }
    }
];

const router = createRouter({
    history : createWebHistory(),
    routes
});

export default router;
