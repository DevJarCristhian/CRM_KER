import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import Login from "../pages/Login.vue";
import index from "../pages/index.vue";
import PageNotFound from "../pages/PageNotFound.vue";
import Dashboard from "../pages/Dashboard/index.vue";
import Users from "../pages/Access/Users.vue";
import Roles from "@/pages/Access/Roles.vue";
import Dependent from "@/pages/People/Dependent.vue";

const routes: Array<RouteRecordRaw> = [
    { path: "/login", component: Login },
    {
        path: "/",
        component: index,
        children: [
            { name: "home", path: "", component: Dashboard },
            { name: "users", path: "/users", component: Users },
            { name: "roles", path: "/roles", component: Roles },
            { name: "dependent", path: "/dependent", component: Dependent },
            { name: "doctors", path: "/doctors", component: Users },
            { name: "settings", path: "/settings", component: Dashboard },
        ],
    },
    { path: "/:catchAll(.*)", component: PageNotFound },
];

const mainRouter = createRouter({
    history: createWebHistory(),
    routes,
});

export default mainRouter;
