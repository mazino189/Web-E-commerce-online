import { useEffect, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { Navigate } from 'react-router-dom';
import api from '../apiClient';
import { Package, Users, ShoppingCart, DollarSign } from 'lucide-react';

interface StatsResponse {
    total_revenue: string | number;
    total_orders: number;
    total_customers: number;
    total_products: number;
    recent_orders: Array<{
        id: number;
        total_amount: string;
        status: string;
        created_at: string;
        user?: { name: string; email: string };
    }>;
}

export default function AdminDashboard() {
    const { user, loading } = useAuth();
    const [stats, setStats] = useState<StatsResponse | null>(null);
    const [fetching, setFetching] = useState(true);

    useEffect(() => {
        if (!user || user.role !== 'admin') return;
        
        api.get<{ data: StatsResponse }>('/admin/stats')
            .then(res => setStats(res.data))
            .finally(() => setFetching(false));
    }, [user]);

    if (loading) return null;

    if (!user || user.role !== 'admin') {
        return <Navigate to="/" replace />;
    }

    const formatPrice = (amount: string | number) =>
        new Intl.NumberFormat('vi-VN').format(Number(amount)) + '₫';

    const statCards = [
        { name: 'Total Revenue', value: stats ? formatPrice(stats.total_revenue) : '-', icon: DollarSign, color: 'text-emerald-500', bg: 'bg-emerald-500/10' },
        { name: 'Total Orders', value: stats?.total_orders ?? '-', icon: ShoppingCart, color: 'text-blue-500', bg: 'bg-blue-500/10' },
        { name: 'Active Customers', value: stats?.total_customers ?? '-', icon: Users, color: 'text-purple-500', bg: 'bg-purple-500/10' },
        { name: 'Total Products', value: stats?.total_products ?? '-', icon: Package, color: 'text-accent', bg: 'bg-accent/10' },
    ];

    return (
        <div className="max-w-7xl mx-auto space-y-6">
            <div>
                <h1 className="text-2xl font-bold text-foreground">Dashboard Overview</h1>
                <p className="text-muted mt-1">Welcome back, {user.name}. Here's your platform status.</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {statCards.map((stat) => (
                    <div key={stat.name} className="p-6 bg-surface border border-border rounded-xl">
                        <div className="flex items-center gap-4">
                            <div className={`p-3 rounded-lg ${stat.bg}`}>
                                <stat.icon className={`w-6 h-6 ${stat.color}`} />
                            </div>
                            <div>
                                <p className="text-sm text-muted font-medium">{stat.name}</p>
                                <p className="text-2xl font-bold text-foreground">
                                    {fetching ? '...' : stat.value}
                                </p>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
            
            <div className="bg-surface rounded-xl border border-border overflow-hidden mt-6">
                <div className="p-6 border-b border-border">
                    <h2 className="text-lg font-semibold text-foreground">Recent Orders</h2>
                </div>
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm">
                        <thead className="bg-canvas border-b border-border text-muted">
                            <tr>
                                <th className="px-6 py-4 font-medium">Order ID</th>
                                <th className="px-6 py-4 font-medium">Customer</th>
                                <th className="px-6 py-4 font-medium">Date</th>
                                <th className="px-6 py-4 font-medium">Amount</th>
                                <th className="px-6 py-4 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {fetching ? (
                                <tr>
                                    <td colSpan={5} className="px-6 py-8 text-center text-muted">Loading...</td>
                                </tr>
                            ) : stats?.recent_orders.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="px-6 py-8 text-center text-muted">No recent orders.</td>
                                </tr>
                            ) : (
                                stats?.recent_orders.map(order => (
                                    <tr key={order.id} className="hover:bg-canvas transition-colors">
                                        <td className="px-6 py-4 font-medium text-foreground">#{order.id}</td>
                                        <td className="px-6 py-4">
                                            <p className="text-foreground">{order.user?.name || 'Guest'}</p>
                                        </td>
                                        <td className="px-6 py-4 text-muted">
                                            {new Date(order.created_at).toLocaleDateString()}
                                        </td>
                                        <td className="px-6 py-4 font-medium text-accent">
                                            {formatPrice(order.total_amount)}
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className={`inline-flex items-center px-2 py-1 rounded-md text-xs font-medium capitalize ${
                                                order.status === 'completed' || order.status === 'delivered' ? 'bg-status-in/10 text-status-in' :
                                                order.status === 'cancelled' ? 'bg-status-out/10 text-status-out' :
                                                order.status === 'shipping' ? 'bg-blue-500/10 text-blue-500' :
                                                'bg-accent/10 text-accent'
                                            }`}>
                                                {order.status}
                                            </span>
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
