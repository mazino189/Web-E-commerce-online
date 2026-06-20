import { Link } from 'react-router-dom';
import { ShoppingCart, Check } from 'lucide-react';
import { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { useCart } from '../context/CartContext';
import { useNavigate } from 'react-router-dom';
import { productImage, handleImageError } from '../utils/categoryImages';

interface Product {
    id: number;
    name: string;
    slug: string;
    description: string;
    price: number;
    stock: number;
    image: string;
    category: { id: number; name: string; slug: string };
    brand: { id: number; name: string; slug: string };
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
            className="group block bg-surface rounded-2xl border border-border overflow-hidden hover:border-accent hover:-translate-y-0.5 hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition-all duration-300"
        >
            <div className="aspect-[4/3] overflow-hidden bg-gray-100">
                <img
                    src={productImage(product.image, product.category?.slug)}
                    alt={product.name}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    loading="lazy"
                    onError={(e) => handleImageError(e, product.category?.slug)}
                />
            </div>
            <div className="p-5 space-y-2">
                <span className="inline-block px-2 py-0.5 text-[10px] font-medium uppercase tracking-wider text-accent bg-accent/10 rounded-full">
                    {product.category?.name ?? ''}
                </span>
                <h3 className="text-sm font-semibold text-foreground leading-snug line-clamp-2">
                    {product.name}
                </h3>
                <p className="text-xs text-muted">{product.brand?.name ?? ''}</p>
                <p className="text-base font-bold text-accent">{formatPrice(product.price)}</p>
                <div className="flex items-center justify-between pt-2 mt-2 border-t border-border/50">
                    <span className={`text-[11px] font-medium ${stockStatus.class}`}>
                        {stockStatus.label}
                    </span>
                    <button
                        onClick={handleAddToCart}
                        disabled={product.stock === 0}
                        className={`px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-1 ${added
                                ? 'bg-status-in/20 text-status-in'
                                : product.stock === 0
                                    ? 'bg-status-out/10 text-status-out cursor-not-allowed'
                                    : 'bg-accent text-white hover:bg-accent-hover'
                            }`}
                    >
                        {added ? <Check className="w-4 h-4" /> : <ShoppingCart className="w-4 h-4" />}
                        {added ? 'Added' : 'Add'}
                    </button>
                </div>
            </div>
        </Link>
    );
}
