import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { ShoppingCart, Check, ChevronLeft, Package } from 'lucide-react';
import { useAuth } from '../context/AuthContext';
import { useCart } from '../context/CartContext';
import { productImage, handleImageError } from '../utils/categoryImages';
import api from '../apiClient';

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

interface SingleResponse<T> {
    data: T;
}

export default function ProductDetail() {
    const { slug } = useParams<{ slug: string }>();
    const { user } = useAuth();
    const { addItem } = useCart();
    const [product, setProduct] = useState<Product | null>(null);
    const [loading, setLoading] = useState(true);
    const [added, setAdded] = useState(false);

    useEffect(() => {
        if (!slug) return;
        setLoading(true);
        api.get<SingleResponse<Product>>(`/products/${slug}`)
            .then((res) => setProduct(res.data ?? null))
            .catch(() => setProduct(null))
            .finally(() => setLoading(false));
    }, [slug]);

    if (!slug) return null;

    const formatPrice = (vnd: number) =>
        new Intl.NumberFormat('vi-VN').format(vnd) + '₫';

    const handleAddToCart = async () => {
        if (!product || product.stock === 0) return;
        try {
            await addItem(product.id);
            setAdded(true);
            setTimeout(() => setAdded(false), 2000);
        } catch { /* handled by context */ }
    };

    if (loading) {
        return (
            <div className="max-w-5xl mx-auto px-4 py-12">
                <div className="animate-pulse flex gap-8">
                    <div className="w-1/2 aspect-square bg-surface rounded-xl border border-border" />
                    <div className="w-1/2 space-y-4">
                        <div className="h-4 w-16 bg-surface rounded" />
                        <div className="h-8 w-3/4 bg-surface rounded" />
                        <div className="h-4 w-1/3 bg-surface rounded" />
                        <div className="h-6 w-1/4 bg-surface rounded" />
                        <div className="h-20 w-full bg-surface rounded" />
                    </div>
                </div>
            </div>
        );
    }

    if (!product) {
        return (
            <div className="max-w-5xl mx-auto px-4 py-20 text-center">
                <Package className="w-16 h-16 mx-auto text-muted mb-4" />
                <h2 className="text-xl font-semibold text-foreground mb-2">Product Not Found</h2>
                <p className="text-muted mb-6">This product doesn't exist or has been removed.</p>
                <Link to="/" className="text-accent hover:underline text-sm">Back to products</Link>
            </div>
        );
    }

    const stockStatus = product.stock > 5
        ? { label: 'In Stock', class: 'text-status-in bg-emerald-400/10' }
        : product.stock > 0
            ? { label: 'Low Stock', class: 'text-status-low bg-amber-300/10' }
            : { label: 'Out of Stock', class: 'text-status-out bg-rose-400/10' };

    return (
        <div className="max-w-5xl mx-auto px-4 sm:px-6 py-8">
            <Link to="/" className="inline-flex items-center gap-1 text-sm text-muted hover:text-foreground transition-colors mb-6">
                <ChevronLeft className="w-4 h-4" /> Back to products
            </Link>

            <div className="grid md:grid-cols-2 gap-8">
                <div className="aspect-square rounded-xl overflow-hidden bg-surface border border-border">
                    <img
                        src={productImage(product.image, product.category?.slug)}
                        alt={product.name}
                        className="w-full h-full object-cover"
                        onError={(e) => handleImageError(e, product.category?.slug)}
                    />
                </div>

                <div className="space-y-5">
                    <div className="flex items-center gap-3">
                        <span className="px-3 py-1 text-xs font-medium uppercase tracking-wider text-cyan-400 bg-cyan-400/10 rounded-full">
                            {product.category?.name ?? ''}
                        </span>
                        <span className="text-xs text-muted">{product.brand?.name ?? ''}</span>
                    </div>

                    <h1 className="text-2xl sm:text-3xl font-bold text-foreground leading-tight">
                        {product.name}
                    </h1>

                    <p className="text-3xl font-bold text-gradient-tech">
                        {formatPrice(product.price)}
                    </p>

                    <div className="flex items-center gap-2">
                        <span className={`px-3 py-1 text-xs font-medium rounded-full ${stockStatus.class}`}>
                            {stockStatus.label}
                        </span>
                        {product.stock > 0 && (
                            <span className="text-xs text-muted">{product.stock} units available</span>
                        )}
                    </div>

                    <p className="text-sm text-muted leading-relaxed">
                        {product.description}
                    </p>

                    {product.stock > 0 && (
                        <button
                            onClick={handleAddToCart}
                            className={`w-full flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-medium text-sm transition-all duration-200 ${added
                                    ? 'bg-emerald-400/20 text-emerald-400 border border-emerald-400/30'
                                    : 'bg-accent text-white hover:bg-accent-hover'
                                }`}
                        >
                            {added ? (
                                <><Check className="w-4 h-4" /> Added to Cart</>
                            ) : (
                                <><ShoppingCart className="w-4 h-4" /> Add to Cart</>
                            )}
                        </button>
                    )}

                    {!user && (
                        <p className="text-xs text-muted text-center">
                            <Link to="/login" className="text-accent hover:underline">Sign in</Link> to add items to your cart
                        </p>
                    )}
                </div>
            </div>
        </div>
    );
}
