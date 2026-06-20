import { Link } from 'react-router-dom';
import { Trash2, ShoppingBag, Minus, Plus, ArrowLeft } from 'lucide-react';
import { useCart } from '../context/CartContext';
import { productImage, handleImageError } from '../utils/categoryImages';

export default function Cart() {
    const { items, loading, updateItem, removeItem } = useCart();

    const formatPrice = (vnd: number) =>
        new Intl.NumberFormat('vi-VN').format(vnd) + '₫';

    const subtotal = items.reduce((sum, item) => sum + (item.product?.price ?? 0) * item.quantity, 0);

    if (loading) {
        return (
            <div className="max-w-3xl mx-auto px-4 py-12">
                <div className="animate-pulse space-y-4">
                    {[1, 2, 3].map((i) => (
                        <div key={i} className="h-24 bg-surface rounded-xl border border-border" />
                    ))}
                </div>
            </div>
        );
    }

    if (items.length === 0) {
        return (
            <div className="max-w-3xl mx-auto px-4 py-20 text-center">
                <ShoppingBag className="w-16 h-16 mx-auto text-muted mb-4" />
                <h2 className="text-xl font-semibold text-foreground mb-2">Your cart is empty</h2>
                <p className="text-muted text-sm mb-6">Looks like you haven't added anything yet.</p>
                <Link to="/" className="inline-flex items-center gap-2 text-sm font-medium text-accent hover:underline">
                    <ArrowLeft className="w-4 h-4" /> Continue Shopping
                </Link>
            </div>
        );
    }

    return (
        <div className="max-w-3xl mx-auto px-4 sm:px-6 py-8">
            <div className="flex items-center justify-between mb-6">
                <h1 className="text-xl font-bold text-foreground">Shopping Cart</h1>
                <span className="text-sm text-muted">{items.length} items</span>
            </div>

            <div className="space-y-3">
                {items.map((item) => (
                    <div key={item.id} className="flex gap-4 p-4 bg-surface rounded-xl border border-border">
                        <Link to={`/products/${item.product?.slug ?? '#'}`} className="w-20 h-20 shrink-0 rounded-lg overflow-hidden bg-gray-100">
                            <img src={productImage(item.product?.image, item.product?.category?.slug)} alt={item.product?.name ?? ''} className="w-full h-full object-cover" onError={(e) => handleImageError(e, item.product?.category?.slug)} />
                        </Link>
                        <div className="flex-1 min-w-0">
                            <Link to={`/products/${item.product?.slug ?? '#'}`} className="text-sm font-semibold text-foreground hover:text-accent transition-colors line-clamp-1">
                                {item.product?.name ?? ''}
                            </Link>
                            <p className="text-xs text-muted mt-0.5">{formatPrice(item.product?.price ?? 0)} each</p>
                            <div className="flex items-center justify-between mt-3">
                                <div className="flex items-center gap-2">
                                    <button
                                        onClick={() => item.quantity > 1 && updateItem(item.id, item.quantity - 1)}
                                        disabled={item.quantity <= 1}
                                        className="p-1 rounded text-muted hover:text-foreground hover:bg-gray-100 disabled:opacity-30 transition-colors"
                                    >
                                        <Minus className="w-3.5 h-3.5" />
                                    </button>
                                    <span className="w-8 text-center text-sm text-foreground">{item.quantity}</span>
                                    <button
                                        onClick={() => item.quantity < (item.product?.stock ?? 0) && updateItem(item.id, item.quantity + 1)}
                                        disabled={item.quantity >= (item.product?.stock ?? 0)}
                                        className="p-1 rounded text-muted hover:text-foreground hover:bg-gray-100 disabled:opacity-30 transition-colors"
                                    >
                                        <Plus className="w-3.5 h-3.5" />
                                    </button>
                                </div>
                                <div className="flex items-center gap-3">
                                    <span className="text-sm font-semibold text-foreground">
                                        {formatPrice((item.product?.price ?? 0) * item.quantity)}
                                    </span>
                                    <button
                                        onClick={() => removeItem(item.id)}
                                        className="p-1.5 rounded text-muted hover:text-rose-400 hover:bg-rose-50 transition-colors"
                                    >
                                        <Trash2 className="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            <div className="mt-6 p-4 bg-surface rounded-xl border border-border">
                <div className="flex items-center justify-between mb-4">
                    <span className="text-sm text-muted">Subtotal</span>
                    <span className="text-lg font-bold text-accent">{formatPrice(subtotal)}</span>
                </div>
                <Link
                    to="/checkout"
                    className="block w-full text-center px-6 py-3 bg-accent text-white rounded-xl font-medium text-sm hover:bg-accent-hover transition-colors"
                >
                    Proceed to Checkout
                </Link>
            </div>
        </div>
    );
}
