import { Link } from 'react-router-dom';
import { ShoppingCart, Check } from 'lucide-react';
import { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { useCart } from '../context/CartContext';
import { useNavigate } from 'react-router-dom';

interface Product {
    id: number;
    name: string;
    slug: string;
    description: string;
    price: number;
    stock: number;
    image: string;
    category: { name: string };
    brand: { name: string };
}

export default function ProductCard({ product }: { product: Product }) {
    const { user } = useAuth();
    const { addItem } = useCart();
    const navigate = useNavigate();
    const [added, setAdded] = useState(false);

    const formatPrice = (vnd: number) =>
        new Intl.NumberFormat('vi-VN').format(vnd) + '₫';

    const stockStatus = product.stock > 5
        ? { label: 'In Stock', class: 'text-status-in' }
        : product.stock > 0
            ? { label: 'Low Stock', class: 'text-status-low' }
            : { label: 'Out of Stock', class: 'text-status-out' };

    const handleAddToCart = async (e: React.MouseEvent) => {
        e.preventDefault();
        e.stopPropagation();
        if (!user) {
            navigate('/login');
            return;
        }
        if (product.stock === 0) return;
        try {
            await addItem(product.id);
            setAdded(true);
            setTimeout(() => setAdded(false), 1500);
        } catch { }
    };

    return (
        <Link
            to={`/products/${product.slug}`}
            className="group block bg-surface rounded-xl border border-border overflow-hidden hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/10 transition-all duration-300"
        >
            <div className="aspect-[4/3] overflow-hidden bg-slate-900">
                <img
                    src={product.image}
                    alt={product.name}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    loading="lazy"
                />
            </div>
            <div className="p-4 space-y-2">
                <span className="inline-block px-2 py-0.5 text-[10px] font-medium uppercase tracking-wider text-cyan-400 bg-cyan-400/10 rounded-full">
                    {product.category?.name ?? ''}
                </span>
                <h3 className="text-sm font-semibold text-foreground leading-snug line-clamp-2">
                    {product.name}
                </h3>
                <p className="text-xs text-muted">{product.brand?.name ?? ''}</p>
                <p className="text-base font-bold text-gradient-tech">{formatPrice(product.price)}</p>
                <div className="flex items-center justify-between pt-1">
                    <span className={`text-[11px] font-medium ${stockStatus.class}`}>
                        {stockStatus.label}
                    </span>
                    <button
                        onClick={handleAddToCart}
                        disabled={product.stock === 0}
                        className={`p-1.5 rounded-lg transition-all duration-200 ${added
                                ? 'bg-emerald-400/20 text-emerald-400'
                                : product.stock === 0
                                    ? 'bg-rose-400/10 text-rose-400 cursor-not-allowed'
                                    : 'bg-accent/10 text-accent hover:bg-accent hover:text-white'
                            }`}
                    >
                        {added ? <Check className="w-4 h-4" /> : <ShoppingCart className="w-4 h-4" />}
                    </button>
                </div>
            </div>
        </Link>
    );
}
