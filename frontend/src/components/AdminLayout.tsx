import { Link, Outlet, useLocation, Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { LayoutDashboard, Package, Tags, Briefcase, LogOut, Zap } from 'lucide-react';

export default function AdminLayout() {
    const { user, loading, logout } = useAuth();
    const location = useLocation();

    if (loading) return null;

    if (!user || user.role !== 'admin') {
        return <Navigate to="/" replace />;
    }

    const navigation = [
        { name: 'Dashboard', href: '/admin/dashboard', icon: LayoutDashboard },
        { name: 'Products', href: '/admin/products', icon: Package },
        { name: 'Categories', href: '/admin/categories', icon: Tags },
        { name: 'Brands', href: '/admin/brands', icon: Briefcase },
    ];

    return (
        <div className="min-h-screen bg-canvas flex">
            {/* Sidebar */}
            <div className="w-64 border-r border-border bg-surface flex flex-col hidden md:flex shrink-0">
                <div className="h-16 flex items-center px-6 border-b border-border">
                    <Link to="/" className="flex items-center gap-2 group">
                        <Zap className="w-6 h-6 text-accent" />
                        <span className="text-lg font-semibold text-foreground tracking-tight">
                            VOLTAIRE<span className="text-accent">/</span>ADMIN
                        </span>
                    </Link>
                </div>
                <div className="flex-1 py-6 px-4 space-y-1 overflow-y-auto">
                    {navigation.map((item) => {
                        const isActive = location.pathname === item.href || location.pathname.startsWith(`${item.href}/`);
                        return (
                            <Link
                                key={item.name}
                                to={item.href}
                                className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors ${
                                    isActive
                                        ? 'bg-accent/10 text-accent'
                                        : 'text-muted hover:text-foreground hover:bg-gray-100'
                                }`}
                            >
                                <item.icon className="w-5 h-5" />
                                {item.name}
                            </Link>
                        );
                    })}
                </div>
                <div className="p-4 border-t border-border">
                    <div className="flex items-center gap-3 mb-4 px-2">
                        <div className="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold text-xs shrink-0">
                            {user.name.charAt(0)}
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-foreground truncate">{user.name}</p>
                            <p className="text-xs text-muted truncate">{user.email}</p>
                        </div>
                    </div>
                    <button
                        onClick={logout}
                        className="flex items-center gap-2 w-full px-3 py-2 text-sm font-medium text-rose-400 hover:bg-rose-50 rounded-lg transition-colors"
                    >
                        <LogOut className="w-4 h-4" />
                        Sign Out
                    </button>
                </div>
            </div>

            {/* Main Content */}
            <div className="flex-1 flex flex-col min-w-0">
                <div className="md:hidden h-16 border-b border-border bg-surface flex items-center px-4">
                    {/* Mobile header (placeholder) */}
                    <span className="text-lg font-semibold text-foreground tracking-tight">
                        VOLTAIRE<span className="text-accent">/</span>ADMIN
                    </span>
                </div>
                <main className="flex-1 overflow-y-auto bg-canvas p-6 lg:p-8">
                    <Outlet />
                </main>
            </div>
        </div>
    );
}
