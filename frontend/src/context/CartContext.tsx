import { createContext, useContext, useState, useEffect, type ReactNode } from 'react';
import api from '../apiClient';
import { useAuth } from './AuthContext';

interface Product {
    id: number;
    name: string;
    slug: string;
    price: number;
    image: string;
    stock: number;
}

interface CartItem {
    id: number;
    product_id: number;
    quantity: number;
    product: Product;
}

interface CartResponse {
    data: CartItem[];
}

interface CartContextType {
    items: CartItem[];
    loading: boolean;
    count: number;
    addItem: (productId: number, quantity?: number) => Promise<void>;
    updateItem: (itemId: number, quantity: number) => Promise<void>;
    removeItem: (itemId: number) => Promise<void>;
    clearCart: () => void;
}

const CartContext = createContext<CartContextType | null>(null);

export function CartProvider({ children }: { children: ReactNode }) {
    const [items, setItems] = useState<CartItem[]>([]);
    const [loading, setLoading] = useState(false);
    const { user } = useAuth();

    useEffect(() => {
        if (user) {
            setLoading(true);
            api.get<CartResponse>('/cart')
                .then((res: CartResponse) => setItems(res.data || []))
                .catch(() => setItems([]))
                .finally(() => setLoading(false));
        } else {
            setItems([]);
        }
    }, [user]);

    const count = items.reduce((sum, item) => sum + item.quantity, 0);

    const addItem = async (productId: number, quantity = 1) => {
        const res = await api.post<{ data: CartItem }>('/cart', { product_id: productId, quantity });
        setItems((prev) => {
            const existing = prev.find((i) => i.product_id === productId);
            if (existing) {
                return prev.map((i) => (i.product_id === productId ? res.data : i));
            }
            return [...prev, res.data];
        });
    };

    const updateItem = async (itemId: number, quantity: number) => {
        await api.put(`/cart/${itemId}`, { quantity });
        setItems((prev) =>
            prev.map((i) => (i.id === itemId ? { ...i, quantity } : i))
        );
    };

    const removeItem = async (itemId: number) => {
        await api.delete(`/cart/${itemId}`);
        setItems((prev) => prev.filter((i) => i.id !== itemId));
    };

    const clearCart = () => setItems([]);

    return (
        <CartContext.Provider value={{ items, loading, count, addItem, updateItem, removeItem, clearCart }}>
            {children}
        </CartContext.Provider>
    );
}

export function useCart() {
    const context = useContext(CartContext);
    if (!context) throw new Error('useCart must be used within CartProvider');
    return context;
}
