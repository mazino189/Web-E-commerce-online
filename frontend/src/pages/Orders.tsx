import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Package, XCircle, ChevronRight } from 'lucide-react';
import api from '../apiClient';
import { productImage, handleImageError } from '../utils/categoryImages';

interface OrderItem {
    id: number;
    product_id: number;
    quantity: number;
    price: number;
    product: {
        id: number;
        name: string;
        slug: string;
        image: string;
    };
}

interface Order {
    id: number;
    total_amount: number;
    status: string;
    shipping_address: string;
    phone_number: string;
    payment_status: string;
    payment_method: string;
    items: OrderItem[];
    created_at: string;
}

interface ListResponse<T> {
    data: T[];
}

const statusConfig: Record<string, { label: string; class: string }> = {
    pending: { label: 'Pending', class: 'text-status-low bg-amber-300/10 border-amber-300/20' },
    processing: { label: 'Processing', class: 'text-indigo-400 bg-indigo-500/10 border-indigo-500/20' },
    shipping: { label: 'Shipping', class: 'text-indigo-400 bg-indigo-500/10 border-indigo-500/20' },
    delivered: { label: 'Delivered', class: 'text-status-in bg-emerald-400/10 border-emerald-400/20' },
    completed: { label: 'Completed', class: 'text-status-in bg-emerald-400/10 border-emerald-400/20' },
    cancelled: { label: 'Cancelled', class: 'text-status-out bg-rose-400/10 border-rose-400/20' },
};

const paymentStatusConfig: Record<string, { label: string; class: string }> = {
    unpaid: { label: 'Unpaid', class: 'text-amber-300 bg-amber-300/10' },
    pending: { label: 'Pending', class: 'text-amber-300 bg-amber-300/10' },
    paid: { label: 'Paid', class: 'text-emerald-400 bg-emerald-400/10' },
    failed: { label: 'Failed', class: 'text-rose-400 bg-rose-400/10' },
};

export default function Orders() {
    const navigate = useNavigate();
    const [orders, setOrders] = useState<Order[]>([]);
    const [loading, setLoading] = useState(true);
    const [cancelling, setCancelling] = useState<number | null>(null);

    useEffect(() => {
        api.get<ListResponse<Order>>('/orders')
            .then((res) => setOrders(res.data || []))
            .catch(() => setOrders([]))
            .finally(() => setLoading(false));
    }, []);

    const formatPrice = (vnd: number) =>
        new Intl.NumberFormat('vi-VN').format(vnd) + '₫';

    const formatDate = (date: string) => {
        if (!date) return '';
        try {
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
        } catch {
            return '';
        }
    };

    const handleCancel = async (orderId: number) => {
        setCancelling(orderId);
        try {
            const res = await api.post<{ data: Order }>(`/orders/${orderId}/cancel`);
            setOrders((prev) =>
                prev.map((o) => (o.id === orderId ? (res?.data ?? o) : o))
            );
        } catch { }
        finally {
            setCancelling(null);
        }
    };

    if (loading) {
        return (
            <div className="max-w-3xl mx-auto px-4 py-12">
                <div className="animate-pulse space-y-4">
                    {[1, 2].map((i) => (
                        <div key={i} className="h-32 bg-surface rounded-xl border border-border" />
                    ))}
                </div>
            </div>
        );
    }

    if (orders.length === 0) {
        return (
            <div className="max-w-3xl mx-auto px-4 py-20 text-center">
                <div className="w-20 h-20 mx-auto mb-6 rounded-full bg-surface border border-border flex items-center justify-center">
                    <Package className="w-10 h-10 text-muted" />
                </div>
                <h2 className="text-xl font-semibold text-foreground mb-2">No orders yet</h2>
                <p className="text-sm text-muted mb-6">Your order history will appear here once you place an order.</p>
                <button
                    onClick={() => navigate('/home')}
                    className="px-6 py-2.5 text-sm font-medium text-white bg-accent rounded-xl hover:bg-accent-hover transition-colors"
                >
                    Start Shopping
                </button>
            </div>
        );
    }

    return (
        <div className="max-w-3xl mx-auto px-4 sm:px-6 py-8">
            <h1 className="text-xl font-bold text-foreground mb-6">My Orders</h1>

            <div className="space-y-4">
                {orders.map((order) => {
                    const statusCfg = statusConfig[order.status] || { label: order.status, class: 'text-muted bg-surface' };
                    const paymentCfg = paymentStatusConfig[order.payment_status] || { label: order.payment_status, class: 'text-muted bg-surface' };

                    return (
                        <div key={order.id} className="p-5 bg-surface rounded-xl border border-border space-y-4">
                            <div className="flex items-center justify-between">
                                <div className="flex items-center gap-3">
                                    <span className="text-sm font-semibold text-foreground">
                                        Order #{order.id}
                                    </span>
                                    <span className={`px-2.5 py-0.5 text-[11px] font-medium rounded-full border ${statusCfg.class}`}>
                                        {statusCfg.label}
                                    </span>
                                    <span className={`px-2.5 py-0.5 text-[11px] font-medium rounded-full ${paymentCfg.class}`}>
                                        {paymentCfg.label}
                                    </span>
                                </div>
                                <span className="text-xs text-muted">{formatDate(order.created_at)}</span>
                            </div>

                            <div className="space-y-2">
                                {(order.items || []).map((item) => (
                                    <div key={item.id} className="flex items-center gap-3">
                                        <div className="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                                            <img
                                                src={productImage(item.product?.image)}
                                                alt={item.product?.name ?? ''}
                                                className="w-full h-full object-cover"
                                                loading="lazy"
                                                onError={handleImageError}
                                            />
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="text-sm text-foreground truncate">{item.product?.name ?? ''}</p>
                                            <p className="text-xs text-muted">
                                                {formatPrice(item.price)} x {item.quantity}
                                            </p>
                                        </div>
                                        <button
                                            onClick={() => navigate(`/products/${item.product?.slug ?? '#'}`)}
                                            className="text-muted hover:text-foreground transition-colors"
                                        >
                                            <ChevronRight className="w-4 h-4" />
                                        </button>
                                    </div>
                                ))}
                            </div>

                            <div className="flex items-center justify-between pt-2 border-t border-border">
                                <div className="text-xs text-muted space-y-0.5">
                                    <p>{order.shipping_address}</p>
                                    <p>{order.phone_number}</p>
                                    <p className="capitalize">{order.payment_method?.replace('_', ' ')}</p>
                                </div>
                                <div className="flex items-center gap-4">
                                    <span className="text-sm font-semibold text-accent">
                                        {formatPrice(order.total_amount)}
                                    </span>
                                    {order.status === 'pending' && (
                                        <button
                                            onClick={() => handleCancel(order.id)}
                                            disabled={cancelling === order.id}
                                            className="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-status-out bg-rose-400/10 border border-rose-400/20 rounded-lg hover:bg-rose-400/20 disabled:opacity-50 transition-colors"
                                        >
                                            <XCircle className="w-3.5 h-3.5" />
                                            {cancelling === order.id ? 'Cancelling...' : 'Cancel'}
                                        </button>
                                    )}
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}
