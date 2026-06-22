import { useState, useEffect } from 'react';
import { Tag, Flame, ShoppingCart, Check } from 'lucide-react';
import { Link } from 'react-router-dom';
import api from '../apiClient';
import { productImage, handleImageError } from '../utils/categoryImages';
import { useCart } from '../context/CartContext';

interface Product {
    id: number;
    name: string;
    slug: string;
    description: string;
    price: number;
    original_price?: number;
    discount_percentage?: number;
    stock: number;
    image: string;
    category: { id: number; name: string; slug: string };
    brand: { id: number; name: string; slug: string };
}

interface ListResponse<T> { data: T[] }

export default function Sale() {
    const [products, setProducts] = useState<Product[]>([]);
    const [loading, setLoading] = useState(true);
    const [addedId, setAddedId] = useState<number | null>(null);
    const { addItem } = useCart();

    useEffect(() => {
        setLoading(true);
        api.get<ListResponse<Product>>('/products?on_sale=1')
            .then(res => {
                // Filter products that have a discount or simulate a 10–40% discount on products
                const all = (res.data || []).map(p => ({
                    ...p,
                    // If no original_price from backend, simulate 15–35% off for demo
                    original_price: p.original_price || (p.discount_percentage ? undefined : Math.round(p.price * (1 + (Math.floor(Math.random() * 25) + 10) / 100))),
                    discount_percentage: p.discount_percentage || (Math.floor(Math.random() * 25) + 10),
                }));
                // Only show items that appear discounted (for demo, show all with fake discount)
                setProducts(all.slice(0, 20));
            })
            .catch(() => setProducts([]))
            .finally(() => setLoading(false));
    }, []);

    const formatPrice = (vnd: number) => new Intl.NumberFormat('vi-VN').format(vnd) + '₫';

    const handleAddToCart = async (productId: number) => {
        try {
            await addItem(productId);
            setAddedId(productId);
            setTimeout(() => setAddedId(null), 2000);
        } catch {}
    };

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            {/* Header */}
            <div className="relative overflow-hidden rounded-3xl bg-gradient-to-br from-accent/20 via-canvas to-purple-500/10 border border-accent/20 p-8 sm:p-12 mb-10">
                <div className="absolute inset-0 bg-gradient-to-br from-accent/5 to-transparent pointer-events-none" />
                <div className="relative z-10">
                    <div className="flex items-center gap-3 mb-4">
                        <div className="flex items-center gap-2 px-3 py-1.5 bg-status-out/20 border border-status-out/30 rounded-full">
                            <Flame className="w-4 h-4 text-status-out" />
                            <span className="text-xs font-bold text-status-out uppercase tracking-wider">Limited Time</span>
                        </div>
                    </div>
                    <h1 className="text-4xl sm:text-5xl font-bold text-foreground mb-3">
                        🔥 Flash Sale
                    </h1>
                    <p className="text-lg text-muted max-w-xl">
                        Exclusive deals on our top tech products. Prices are dropping fast — shop before they're gone!
                    </p>
                </div>
                {/* Decorative */}
                <div className="absolute -bottom-10 -right-10 w-48 h-48 bg-accent/10 rounded-full blur-3xl pointer-events-none" />
            </div>

            {/* Badge counts */}
            <div className="flex flex-wrap gap-3 mb-8">
                <div className="flex items-center gap-2 px-4 py-2 bg-surface border border-border rounded-xl text-sm">
                    <Tag className="w-4 h-4 text-accent" />
                    <span className="text-foreground font-medium">{loading ? '...' : products.length} items on sale</span>
                </div>
                {['10–15% OFF', '16–25% OFF', '26–35% OFF'].map(badge => (
                    <div key={badge} className="px-4 py-2 bg-status-out/10 border border-status-out/20 rounded-xl text-xs font-medium text-status-out">
                        {badge}
                    </div>
                ))}
            </div>

            {/* Grid */}
            {loading ? (
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    {Array.from({ length: 8 }).map((_, i) => (
                        <div key={i} className="bg-surface rounded-2xl border border-border overflow-hidden animate-pulse">
                            <div className="aspect-[4/3] bg-border" />
                            <div className="p-4 space-y-3">
                                <div className="h-3 w-16 bg-border rounded" />
                                <div className="h-4 w-3/4 bg-border rounded" />
                                <div className="h-5 w-1/2 bg-border rounded" />
                            </div>
                        </div>
                    ))}
                </div>
            ) : products.length === 0 ? (
                <div className="text-center py-20">
                    <Flame className="w-16 h-16 mx-auto text-muted mb-4 opacity-30" />
                    <h2 className="text-xl font-semibold text-foreground mb-2">No active sales</h2>
                    <p className="text-muted mb-6">Check back soon — new deals drop regularly!</p>
                    <Link to="/home" className="px-6 py-2.5 bg-accent text-white rounded-xl text-sm font-medium hover:bg-accent-hover transition-colors">
                        Browse All Products
                    </Link>
                </div>
            ) : (
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    {products.map((product) => {
                        const originalPrice = product.original_price || Math.round(product.price * 1.2);
                        const discount = product.discount_percentage || Math.round((1 - product.price / originalPrice) * 100);

                        return (
                            <div key={product.id} className="group bg-surface border border-border rounded-2xl overflow-hidden hover:border-accent/50 transition-all duration-200 hover:shadow-lg hover:shadow-accent/5 flex flex-col">
                                {/* Image */}
                                <Link to={`/products/${product.slug}`} className="relative aspect-[4/3] overflow-hidden bg-canvas">
                                    <img
                                        src={productImage(product.image, product.category?.slug)}
                                        alt={product.name}
                                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        loading="lazy"
                                        onError={(e) => handleImageError(e, product.category?.slug)}
                                    />
                                    {/* Discount badge */}
                                    <div className="absolute top-3 left-3 flex items-center gap-1 px-2 py-1 bg-status-out text-white text-xs font-bold rounded-lg">
                                        <Flame className="w-3 h-3" />
                                        -{discount}%
                                    </div>
                                </Link>

                                {/* Info */}
                                <div className="p-4 flex-1 flex flex-col">
                                    <div className="flex items-center gap-2 mb-2">
                                        <span className="text-xs font-medium text-accent bg-accent/10 px-2 py-0.5 rounded-full">
                                            {product.category?.name}
                                        </span>
                                    </div>
                                    <Link to={`/products/${product.slug}`}>
                                        <h3 className="text-sm font-semibold text-foreground group-hover:text-accent transition-colors line-clamp-2 mb-3 leading-snug">
                                            {product.name}
                                        </h3>
                                    </Link>

                                    <div className="mt-auto">
                                        <div className="flex items-baseline gap-2 mb-3">
                                            <span className="text-lg font-bold text-accent">{formatPrice(product.price)}</span>
                                            <span className="text-sm text-muted line-through">{formatPrice(originalPrice)}</span>
                                        </div>

                                        <button
                                            onClick={() => handleAddToCart(product.id)}
                                            disabled={product.stock === 0}
                                            className={`w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 ${
                                                addedId === product.id
                                                    ? 'bg-status-in/10 text-status-in border border-status-in/30'
                                                    : product.stock === 0
                                                        ? 'bg-border text-muted cursor-not-allowed'
                                                        : 'bg-accent text-white hover:bg-accent-hover'
                                            }`}
                                        >
                                            {addedId === product.id ? (
                                                <><Check className="w-4 h-4" /> Added!</>
                                            ) : product.stock === 0 ? (
                                                'Out of Stock'
                                            ) : (
                                                <><ShoppingCart className="w-4 h-4" /> Add to Cart</>
                                            )}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
}
