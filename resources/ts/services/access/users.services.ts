import api from "@/config/axios";
import { Params, Store } from "../interfaces/access/users.interfaces";

const prefix = "users/";

class usersServices {
    async get(params: Params) {
        const { data } = await api.get(`${prefix}`, {
            params,
        });
        return data;
    }
    async store(data: Store) {
        const { data: response } = await api.post(`${prefix}store`, data);
        return response;
    }
    async update(id: number, data: Store) {
        const { data: response } = await api.put(`${prefix}update/${id}`, data);
        return response;
    }
    async getRoles() {
        const { data } = await api.get(`${prefix}roles`);
        return data;
    }
}

export default new usersServices();
