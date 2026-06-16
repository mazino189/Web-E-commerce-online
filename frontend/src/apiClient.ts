const API_BASE = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api';

interface ApiError extends Error {
    status?: number;
    data?: Record<string, unknown>;
}

async function request<T = unknown>(endpoint: string, options: RequestInit = {}): Promise<T> {
    const token = localStorage.getItem('token');

    const config: RequestInit = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...(token && { Authorization: `Bearer ${token}` }),
            ...(options.headers as Record<string, string>),
        },
        ...options,
    };

    if (config.body && typeof config.body === 'object' && !(config.body instanceof FormData)) {
        config.body = JSON.stringify(config.body);
    }

    const response = await fetch(`${API_BASE}${endpoint}`, config);
    let data: T;
    try {
        data = await response.json();
    } catch {
        throw new Error(`Invalid JSON response (HTTP ${response.status})`);
    }

    if (!response.ok) {
        const error = new Error((data as { message?: string })?.message || `Request failed with status ${response.status}`) as ApiError;
        error.status = response.status;
        error.data = data as Record<string, unknown>;
        throw error;
    }

    return data;
}

export const api = {
    get: <T = unknown>(endpoint: string) => request<T>(endpoint, { method: 'GET' }),
    post: <T = unknown>(endpoint: string, body?: unknown) => request<T>(endpoint, { method: 'POST', body: body as BodyInit }),
    put: <T = unknown>(endpoint: string, body?: unknown) => request<T>(endpoint, { method: 'PUT', body: body as BodyInit }),
    delete: <T = unknown>(endpoint: string) => request<T>(endpoint, { method: 'DELETE' }),
};

export default api;
