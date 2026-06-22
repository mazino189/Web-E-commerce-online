import { useState, useRef, useEffect } from 'react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { ShoppingCart, LogOut, Menu, X, Search, ChevronDown } from 'lucide-react';
import { useAuth } from '../context/AuthContext';
import { useCart } from '../context/CartContext';
import logoImg from '../assets/logo.png';

export default function Navbar() {
    const { user, logout } = useAuth();
    const { count } = useCart();
    const [menuOpen, setMenuOpen] = useState(false);
    const [dropdownOpen, setDropdownOpen] = useState(false);
    const dropdownRef = useRef<HTMLDivElement>(null);
    
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();
    const [searchQuery, setSearchQuery] = useState(searchParams.get('search') || '');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        if (searchQuery.trim()) {
            navigate(`/home?search=${encodeURIComponent(searchQuery.trim())}`);
        } else {
            navigate('/home');
        }
    };

    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
                setDropdownOpen(false);
            }
        };
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    const userInitials = user?.name?.substring(0, 2).toUpperCase() || 'VT';

    return (
        <nav className="sticky top-0 z-50 bg-slate-900/80 backdrop-blur-md border-b border-slate-800">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between h-16 gap-4">
                    {/* LOGO */}
                    <Link to="/home" className="flex items-center group shrink-0">
                        <img
                            src={logoImg}
                            alt="VOLTAIRE/TECH"
                            className="h-9 w-auto object-contain group-hover:opacity-90 transition-opacity"
                        />
                    </Link>

                    {/* SEARCH BAR (Desktop) */}
                    <div className="hidden md:flex flex-1 max-w-xl px-4">
                        <form onSubmit={handleSearch} className="w-full relative">
                            <input
                                type="text"
                                placeholder="Search products..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="w-full pl-10 pr-4 py-2 bg-slate-800/50 border border-slate-700 rounded-xl text-sm text-slate-200 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all placeholder:text-slate-500"
                            />
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                        </form>
                    </div>

                    {/* NAV LINKS (Desktop) */}
                    <div className="hidden md:flex items-center gap-6">
                        <Link
                            to="/home"
                            className="text-sm font-medium text-slate-400 hover:text-slate-200 transition-colors"
                        >
                            Products
                        </Link>
                        <Link
                            to="/sale"
                            className="flex items-center gap-1.5 text-sm font-bold text-status-out hover:text-rose-300 transition-colors"
                        >
                            🔥 Sale
                        </Link>
                    </div>

                    {/* CART & USER */}
                    <div className="hidden md:flex items-center gap-6 shrink-0">
                        <Link
                            to="/cart"
                            className="relative text-slate-400 hover:text-cyan-400 transition-colors"
                        >
                            <ShoppingCart className="w-5 h-5" />
                            {count > 0 && (
                                <span className="absolute -top-2 -right-2 bg-cyan-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center shadow-[0_0_10px_rgba(6,182,212,0.6)]">
                                    {count > 9 ? '9+' : count}
                                </span>
                            )}
                        </Link>

                        {user ? (
                            <div className="relative" ref={dropdownRef}>
                                <button 
                                    onClick={() => setDropdownOpen(!dropdownOpen)}
                                    className="flex items-center gap-2 focus:outline-none"
                                >
                                    <div className="w-9 h-9 rounded-full bg-slate-800 border-2 border-slate-700 overflow-hidden flex items-center justify-center shadow-lg hover:border-cyan-500 transition-colors">
                                        {user.avatar ? (
                                            <img src={user.avatar} alt={user.name} className="w-full h-full object-cover" />
                                        ) : (
                                            <span className="text-xs font-bold text-cyan-400">{userInitials}</span>
                                        )}
                                    </div>
                                    <ChevronDown className={`w-4 h-4 text-slate-400 transition-transform ${dropdownOpen ? 'rotate-180' : ''}`} />
                                </button>

                                {dropdownOpen && (
                                    <div className="absolute right-0 mt-2 w-48 bg-slate-900 border border-slate-800 rounded-xl shadow-2xl overflow-hidden py-1">
                                        <div className="px-4 py-2 border-b border-slate-800 mb-1">
                                            <p className="text-sm font-medium text-slate-200 truncate">{user.name}</p>
                                            <p className="text-xs text-slate-500 truncate">{user.email}</p>
                                        </div>
                                        <Link 
                                            to="/profile" 
                                            onClick={() => setDropdownOpen(false)}
                                            className="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-cyan-400 transition-colors"
                                        >
                                            Edit Profile
                                        </Link>
                                        <Link 
                                            to="/profile/avatar" 
                                            onClick={() => setDropdownOpen(false)}
                                            className="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-cyan-400 transition-colors"
                                        >
                                            Change Avatar
                                        </Link>
                                        <Link 
                                            to="/profile/password" 
                                            onClick={() => setDropdownOpen(false)}
                                            className="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-cyan-400 transition-colors"
                                        >
                                            Change Password
                                        </Link>
                                        <Link 
                                            to="/orders" 
                                            onClick={() => setDropdownOpen(false)}
                                            className="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-cyan-400 transition-colors"
                                        >
                                            My Orders
                                        </Link>
                                        {user.role === 'admin' && (
                                            <Link 
                                                to="/admin/dashboard" 
                                                onClick={() => setDropdownOpen(false)}
                                                className="block px-4 py-2 text-sm font-medium text-fuchsia-400 hover:bg-slate-800 transition-colors"
                                            >
                                                Admin Panel
                                            </Link>
                                        )}
                                        <div className="border-t border-slate-800 mt-1 pt-1">
                                            <button 
                                                onClick={() => { logout(); setDropdownOpen(false); }}
                                                className="w-full text-left px-4 py-2 text-sm text-rose-400 hover:bg-slate-800 transition-colors flex items-center gap-2"
                                            >
                                                <LogOut className="w-4 h-4" /> Sign Out
                                            </button>
                                        </div>
                                    </div>
                                )}
                            </div>
                        ) : (
                            <Link
                                to="/login"
                                className="px-5 py-2 text-sm font-medium text-slate-900 bg-cyan-400 rounded-xl hover:bg-cyan-300 transition-colors shadow-[0_0_15px_rgba(34,211,238,0.4)] hover:shadow-[0_0_25px_rgba(34,211,238,0.6)]"
                            >
                                Sign In
                            </Link>
                        )}
                    </div>

                    <button
                        className="md:hidden text-slate-400 hover:text-slate-200 shrink-0"
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
                            className="w-full pl-10 pr-4 py-2 bg-slate-800 border border-slate-700 rounded-xl text-sm text-slate-200 focus:outline-none focus:border-cyan-500 transition-colors"
                        />
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                    </form>
                </div>
            </div>

            {menuOpen && (
                <div className="md:hidden border-t border-slate-800 bg-slate-900/95 backdrop-blur-md">
                    <div className="px-4 py-4 space-y-4">
                        <Link to="/home" className="block text-slate-300 font-medium text-sm" onClick={() => setMenuOpen(false)}>
                            Products
                        </Link>
                        <Link to="/sale" className="block text-sm font-bold text-status-out" onClick={() => setMenuOpen(false)}>
                            🔥 Sale
                        </Link>
                        {user ? (
                            <>
                                <Link to="/profile" className="block text-slate-300 text-sm" onClick={() => setMenuOpen(false)}>
                                    Edit Profile
                                </Link>
                                <Link to="/orders" className="block text-slate-300 text-sm" onClick={() => setMenuOpen(false)}>
                                    My Orders
                                </Link>
                                <Link to="/cart" className="block text-slate-300 text-sm" onClick={() => setMenuOpen(false)}>
                                    Cart {count > 0 && <span className="text-cyan-400">({count})</span>}
                                </Link>
                                {user.role === 'admin' && (
                                    <Link to="/admin/dashboard" className="block text-fuchsia-400 font-medium text-sm" onClick={() => setMenuOpen(false)}>
                                        Admin Panel
                                    </Link>
                                )}
                                <button onClick={() => { logout(); setMenuOpen(false); }} className="block text-sm text-rose-400 font-medium w-full text-left">
                                    Sign Out
                                </button>
                                <div className="border-t border-slate-800 pt-3 flex items-center gap-3">
                                    <div className="w-8 h-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center overflow-hidden">
                                        {user.avatar ? (
                                            <img src={user.avatar} alt="Avatar" className="w-full h-full object-cover" />
                                        ) : (
                                            <span className="text-[10px] font-bold text-cyan-400">{userInitials}</span>
                                        )}
                                    </div>
                                    <div>
                                        <p className="text-sm font-medium text-slate-200 leading-none">{user.name}</p>
                                        <p className="text-xs text-slate-500 mt-1 leading-none">{user.email}</p>
                                    </div>
                                </div>
                            </>
                        ) : (
                            <Link to="/login" className="block text-sm font-medium text-cyan-400" onClick={() => setMenuOpen(false)}>
                                Sign In
                            </Link>
                        )}
                    </div>
                </div>
            )}
        </nav>
    );
}
