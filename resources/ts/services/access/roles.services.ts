import api from "@/config/axios";
import { Params, Store } from "../interfaces/access/roles.interfaces";

const prefix = "roles/";

class rolesServices {
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
    async getPermissions() {
        const { data } = await api.get(`${prefix}permissions`);
        return data;
    }
}

export default new rolesServices();
