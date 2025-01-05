export interface Params {
    page: number;
    perPage: number;
    search?: string | null;
    status?: string | null;
}

export interface Get {
    id?: number;
    description: string;
    permissions: number[];
    created_at?: Date;
    updated_at?: Date;
}

export interface Store {
    id?: number;
    description: string;
    permissions: number[];
}
export interface Permission {
    id: number;
    description: string;
    parent: number;
}

export interface Permissions {
    id: number;
    description: string;
    parent: number | null;
    children: Permissions[];
}
