import { useEffect, useState } from 'react';
import api from '../apiClient';
import { ShoppingCart, Search } from 'lucide-react';

interface OrderItem {
    id: number;
    quantity: number;
    price: string;
    product: {
        id: number;
        name: string;
    };
}

interface Order {
    id: number;
    total_amount: string;
    status: string;
    shipping_address: string;
    phone_number: string;
    payment_method: string;
    created_at: string;
    user?: { name: string; email: string };
    items: OrderItem[];
}

export default function AdminOrders() {
    const [orders, setOrders] = useState<Order[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');

    useEffect(() => {
        fetchOrders();
    }, []);

    const fetchOrders = () => {
        setLoading(true);
        api.get<{ data: any }>('/admin/orders')
            .then(res => {
                // Laravel paginate returns items in `data.data` usually, or just `data` depending on resource wrapper
                const ordersData = res.data?.data || res.data || [];
                setOrders(Array.isArray(ordersData) ? ordersData : []);
            })
            .finally(() => setLoading(false));
    };

    const formatPrice = (amount: string | number) =>
        new Intl.NumberFormat('vi-VN').format(Number(amount)) + '₫';

    const handleStatusChange = async (id: number, newStatus: string) => {
        try {
            await api.put(`/admin/orders/${id}`, { status: newStatus });
            setOrders(orders.map(o => o.id === id ? { ...o, status: newStatus } : o));
        } catch (err) {
            console.error('Failed to update order status', err);
            alert('Failed to update order status');
        }
    };

    const filteredOrders = orders.filter(o => 
        o.id.toString().includes(search) || 
        o.user?.name.toLowerCase().includes(search.toLowerCase())
    );

    return (
        <div className="space-y-6">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-foreground">Orders</h1>
                    <p className="text-sm text-muted">Manage and track customer orders.</p>
                </div>
            </div>

            <div className="bg-surface border border-border rounded-xl overflow-hidden flex flex-col">
                <div className="p-4 border-b border-border flex items-center gap-4">
                    <div className="relative flex-1 max-w-md">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" />
                        <input
                            type="text"
                            placeholder="Search by Order ID or Customer Name..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm bg-canvas border border-border rounded-lg text-foreground placeholder:text-muted focus:outline-none focus:border-accent transition-colors"
                        />
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="border-b border-border bg-gray-50 text-xs text-muted uppercase tracking-wider">
                                <th className="px-6 py-4 font-medium">Order ID</th>
                                <th className="px-6 py-4 font-medium">Customer</th>
                                <th className="px-6 py-4 font-medium">Date</th>
                                <th className="px-6 py-4 font-medium">Items</th>
                                <th className="px-6 py-4 font-medium">Total</th>
                                <th className="px-6 py-4 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {loading ? (
                                <tr>
                                    <td colSpan={6} className="px-6 py-8 text-center text-muted">Loading orders...</td>
                                </tr>
                            ) : filteredOrders.length === 0 ? (
                                <tr>
                                    <td colSpan={6} className="px-6 py-8 text-center text-muted">
                                        <ShoppingCart className="w-8 h-8 mx-auto mb-2 opacity-50" />
                                        No orders found.
                                    </td>
                                </tr>
                            ) : (
                                filteredOrders.map((order) => (
                                    <tr key={order.id} className="hover:bg-gray-50 transition-colors">
                                        <td className="px-6 py-4 text-sm font-medium text-foreground">#{order.id}</td>
                                        <td className="px-6 py-4 text-sm">
                                            <p className="font-medium text-foreground">{order.user?.name || 'Guest'}</p>
                                            <p className="text-muted text-xs">{order.user?.email}</p>
                                            <p className="text-muted text-xs mt-1">{order.phone_number}</p>
                                        </td>
                                        <td className="px-6 py-4 text-sm text-muted">
                                            {new Date(order.created_at).toLocaleDateString()}
                                        </td>
                                        <td className="px-6 py-4 text-sm text-muted">
                                            {order.items?.length || 0} items
                                        </td>
                                        <td className="px-6 py-4 text-sm font-medium text-accent">
                                            {formatPrice(order.total_amount)}
                                        </td>
                                        <td className="px-6 py-4 text-sm">
                                            <select 
                                                value={order.status}
                                                onChange={(e) => handleStatusChange(order.id, e.target.value)}
                                                className={`px-3 py-1.5 rounded-lg text-xs font-medium border focus:outline-none transition-colors cursor-pointer ${
                                                    order.status === 'completed' || order.status === 'delivered' ? 'bg-status-in/10 text-status-in border-status-in/20' :
                                                    order.status === 'cancelled' ? 'bg-status-out/10 text-status-out border-status-out/20' :
                                                    order.status === 'shipping' ? 'bg-blue-500/10 text-blue-500 border-blue-500/20' :
                                                    'bg-accent/10 text-accent border-accent/20'
                                                }`}
                                            >
                                                <option value="pending" className="text-foreground bg-canvas">Pending</option>
                                                <option value="processing" className="text-foreground bg-canvas">Processing</option>
                                                <option value="shipping" className="text-foreground bg-canvas">Shipping</option>
                                                <option value="delivered" className="text-foreground bg-canvas">Delivered</option>
                                                <option value="completed" className="text-foreground bg-canvas">Completed</option>
                                                <option value="cancelled" className="text-foreground bg-canvas">Cancelled</option>
                                            </select>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
