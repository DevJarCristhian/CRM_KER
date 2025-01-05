import { createRouter, createWebHistory } from "vue-router";
import Login from "../pages/Login.vue";
import PageNotFound from "../pages/PageNotFound.vue";

const routes = [
    { path: "/login", component: Login },
    { path: "/:catchAll(.*)", component: PageNotFound },
];

const loginRouter = createRouter({
    history: createWebHistory(),
    routes,
});
export default loginRouter;
