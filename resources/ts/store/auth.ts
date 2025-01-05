import { defineStore } from "pinia";
import authServices from "@/services/auth.services";

export interface User {
    id: number;
    name: string;
    email: string;
    role: string;
}

export const authStores = defineStore("auth", {
    state: () => ({
        user: {} as User,
    }),
    actions: {
        async getUser() {
            const { data } = await authServices.getUser();
            // console.log("here", data);
            this.user = data as User;
        },
    },
});
