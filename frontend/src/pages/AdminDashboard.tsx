import { useEffect, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { Navigate } from 'react-router-dom';
import api from '../apiClient';
import { Package, Tags, Briefcase, Activity } from 'lucide-react';

export default function AdminDashboard() {
    const { user, loading } = useAuth();
    const [stats, setStats] = useState({ products: 0, categories: 0, brands: 0 });
    const [fetching, setFetching] = useState(true);

    useEffect(() => {
        if (!user || user.role !== 'admin') return;
        
        Promise.all([
            api.get<{ data: any[] }>('/admin/products'),
            api.get<{ data: any[] }>('/admin/categories'),
            api.get<{ data: any[] }>('/admin/brands')
        ]).then(([prodRes, catRes, brandRes]) => {
            setStats({
                products: prodRes.data?.length || 0,
                categories: catRes.data?.length || 0,
                brands: brandRes.data?.length || 0
            });
        }).finally(() => {
            setFetching(false);
        });
    }, [user]);

    if (loading) return null;

    if (!user || user.role !== 'admin') {
        return <Navigate to="/" replace />;
    }

    const statCards = [
        { name: 'Total Products', value: stats.products, icon: Package, color: 'text-blue-400', bg: 'bg-blue-400/10' },
        { name: 'Categories', value: stats.categories, icon: Tags, color: 'text-emerald-400', bg: 'bg-emerald-400/10' },
        { name: 'Brands', value: stats.brands, icon: Briefcase, color: 'text-purple-400', bg: 'bg-purple-400/10' },
        { name: 'System Status', value: 'Online', icon: Activity, color: 'text-amber-400', bg: 'bg-amber-400/10' },
    ];

    return (
        <div className="max-w-7xl mx-auto space-y-6">
            <div>
                <h1 className="text-2xl font-bold text-foreground">Dashboard Overview</h1>
                <p className="text-muted mt-1">Welcome back, {user.name}. Here's what's happening today.</p>
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
                                    {fetching ? '-' : stat.value}
                                </p>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
            
            <div className="bg-surface rounded-xl border border-border p-6 mt-6">
                <h2 className="text-lg font-semibold text-foreground mb-4">Quick Actions</h2>
                <p className="text-muted text-sm mb-4">Use the sidebar to manage different aspects of your store. You can add new products, update categories, and manage brand affiliations.</p>
            </div>
        </div>
    );
}
