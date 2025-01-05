export interface Params {
    page: number;
    perPage: number;
    search?: string | null;
    status?: string | null;
}

interface Role {
    id: number;
    description: string;
}

export interface Get {
    id?: number;
    name: string;
    email: string;
    password?: string;
    role: Role;
    status: string;
    created_at?: Date;
    updated_at?: Date;
}

export interface Store {
    id?: number;
    name: string;
    email: string;
    password?: string | null;
    role_id: number | null;
    status: boolean;
}
