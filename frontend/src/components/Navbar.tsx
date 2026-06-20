import { useState } from 'react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { ShoppingCart, User, LogOut, Menu, X, Zap, Package, Search } from 'lucide-react';
import { useAuth } from '../context/AuthContext';
import { useCart } from '../context/CartContext';

export default function Navbar() {
    const { user, logout } = useAuth();
    const { count } = useCart();
    const [menuOpen, setMenuOpen] = useState(false);
    
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();
    const [searchQuery, setSearchQuery] = useState(searchParams.get('search') || '');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        if (searchQuery.trim()) {
            navigate(`/?search=${encodeURIComponent(searchQuery.trim())}`);
        } else {
            navigate('/');
        }
    };

    return (
        <nav className="sticky top-0 z-50 bg-white border-b border-border">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between h-16 gap-4">
                    {/* LOGO */}
                    <Link to="/" className="flex items-center gap-2 group shrink-0">
                        <Zap className="w-6 h-6 text-foreground group-hover:text-accent transition-colors" />
                        <span className="text-lg font-bold text-foreground tracking-tight">
                            VOLTAIRE<span className="text-accent">/</span>TECH
                        </span>
                    </Link>

                    {/* SEARCH BAR (Desktop) */}
                    <div className="hidden md:flex flex-1 max-w-xl px-4">
                        <form onSubmit={handleSearch} className="w-full relative">
                            <input
                                type="text"
                                placeholder="Search products..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="w-full pl-10 pr-4 py-2 bg-canvas border border-border rounded-xl text-sm text-foreground focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all"
                            />
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" />
                        </form>
                    </div>

                    {/* CART & USER */}
                    <div className="hidden md:flex items-center gap-6 shrink-0">
                        {user ? (
                            <>
                                {user.role === 'admin' && (
                                    <Link to="/admin/dashboard" className="text-sm font-medium text-accent hover:text-accent-hover transition-colors">
                                        Admin Panel
                                    </Link>
                                )}
                                <Link
                                    to="/orders"
                                    className="text-muted hover:text-foreground transition-colors"
                                >
                                    <Package className="w-5 h-5" />
                                </Link>
                                <Link
                                    to="/cart"
                                    className="relative text-muted hover:text-foreground transition-colors"
                                >
                                    <ShoppingCart className="w-5 h-5" />
                                    {count > 0 && (
                                        <span className="absolute -top-2 -right-2 bg-accent text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                                            {count > 9 ? '9+' : count}
                                        </span>
                                    )}
                                </Link>
                                <div className="flex items-center gap-2 text-sm font-medium text-foreground">
                                    <User className="w-4 h-4 text-muted" />
                                    {user.name}
                                </div>
                                <button
                                    onClick={logout}
                                    className="text-muted hover:text-rose-400 transition-colors"
                                >
                                    <LogOut className="w-5 h-5" />
                                </button>
                            </>
                        ) : (
                            <Link
                                to="/login"
                                className="px-5 py-2 text-sm font-medium text-white bg-accent rounded-xl hover:bg-accent-hover transition-colors"
                            >
                                Sign In
                            </Link>
                        )}
                    </div>

                    <button
                        className="md:hidden text-muted hover:text-foreground shrink-0"
                        onClick={() => setMenuOpen(!menuOpen)}
                    >
                        {menuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
                    </button>
                </div>
                
                {/* Mobile Search */}
                <div className="md:hidden pb-3">
                    <form onSubmit={handleSearch} className="w-full relative">
                        <input
                            type="text"
                            placeholder="Search products..."
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            className="w-full pl-10 pr-4 py-2 bg-canvas border border-border rounded-xl text-sm text-foreground focus:outline-none focus:border-accent transition-colors"
                        />
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" />
                    </form>
                </div>
            </div>

            {menuOpen && (
                <div className="md:hidden border-t border-border bg-white">
                    <div className="px-4 py-4 space-y-4">
                        <Link to="/" className="block text-foreground font-medium text-sm" onClick={() => setMenuOpen(false)}>
                            Products
                        </Link>
                        {user ? (
                            <>
                                {user.role === 'admin' && (
                                    <Link to="/admin/dashboard" className="block text-accent font-medium text-sm" onClick={() => setMenuOpen(false)}>
                                        Admin Panel
                                    </Link>
                                )}
                                <Link to="/orders" className="block text-foreground text-sm" onClick={() => setMenuOpen(false)}>
                                    My Orders
                                </Link>
                                <Link to="/cart" className="block text-foreground text-sm" onClick={() => setMenuOpen(false)}>
                                    Cart {count > 0 && `(${count})`}
                                </Link>
                                <button onClick={() => { logout(); setMenuOpen(false); }} className="block text-sm text-rose-400 font-medium">
                                    Sign Out
                                </button>
                                <p className="text-xs text-muted">{user.email}</p>
                            </>
                        ) : (
                            <Link to="/login" className="block text-sm font-medium text-accent" onClick={() => setMenuOpen(false)}>
                                Sign In
                            </Link>
                        )}
                    </div>
                </div>
            )}
        </nav>
    );
}
