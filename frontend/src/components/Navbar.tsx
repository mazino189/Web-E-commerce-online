import { useState } from 'react';
import { Link } from 'react-router-dom';
import { ShoppingCart, User, LogOut, Menu, X, Zap, Package } from 'lucide-react';
import { useAuth } from '../context/AuthContext';
import { useCart } from '../context/CartContext';

export default function Navbar() {
    const { user, logout } = useAuth();
    const { count } = useCart();
    const [menuOpen, setMenuOpen] = useState(false);

    return (
        <nav className="sticky top-0 z-50 bg-canvas/80 backdrop-blur-xl border-b border-border">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between h-16">
                    <Link to="/" className="flex items-center gap-2 group">
                        <Zap className="w-6 h-6 text-cyber group-hover:text-cyber-light transition-colors" />
                        <span className="text-lg font-semibold text-foreground tracking-tight">
                            VOLTAIRE<span className="text-cyber">/</span>TECH
                        </span>
                    </Link>

                    <div className="hidden md:flex items-center gap-6">
                        <Link to="/" className="text-muted hover:text-foreground transition-colors text-sm">
                            Products
                        </Link>
                        {user ? (
                            <>
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
                                        <span className="absolute -top-2 -right-2 bg-cyber text-canvas text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                                            {count > 9 ? '9+' : count}
                                        </span>
                                    )}
                                </Link>
                                <button
                                    onClick={logout}
                                    className="text-muted hover:text-rose-400 transition-colors"
                                >
                                    <LogOut className="w-5 h-5" />
                                </button>
                                <div className="flex items-center gap-2 text-sm text-foreground">
                                    <User className="w-4 h-4 text-muted" />
                                    {user.name}
                                </div>
                            </>
                        ) : (
                            <Link
                                to="/login"
                                className="px-4 py-1.5 text-sm font-medium text-foreground bg-accent rounded-lg hover:bg-accent-hover transition-colors"
                            >
                                Sign In
                            </Link>
                        )}
                    </div>

                    <button
                        className="md:hidden text-muted hover:text-foreground"
                        onClick={() => setMenuOpen(!menuOpen)}
                    >
                        {menuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
                    </button>
                </div>
            </div>

            {menuOpen && (
                <div className="md:hidden border-t border-border bg-surface/95 backdrop-blur-xl">
                    <div className="px-4 py-4 space-y-3">
                        <Link to="/" className="block text-muted hover:text-foreground text-sm" onClick={() => setMenuOpen(false)}>
                            Products
                        </Link>
                        {user ? (
                            <>
                                <Link to="/orders" className="block text-muted hover:text-foreground text-sm" onClick={() => setMenuOpen(false)}>
                                    My Orders
                                </Link>
                                <Link to="/cart" className="block text-muted hover:text-foreground text-sm" onClick={() => setMenuOpen(false)}>
                                    Cart {count > 0 && `(${count})`}
                                </Link>
                                <button onClick={() => { logout(); setMenuOpen(false); }} className="block text-sm text-rose-400">
                                    Sign Out
                                </button>
                                <p className="text-xs text-muted">{user.email}</p>
                            </>
                        ) : (
                            <Link to="/login" className="block text-sm text-accent" onClick={() => setMenuOpen(false)}>
                                Sign In
                            </Link>
                        )}
                    </div>
                </div>
            )}
        </nav>
    );
}
