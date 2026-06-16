import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { ArrowLeft, CreditCard, Building2, Check } from 'lucide-react';
import api from '../apiClient';
import { useCart } from '../context/CartContext';

export default function Checkout() {
    const navigate = useNavigate();
    const { items, clearCart } = useCart();
    const [shippingAddress, setShippingAddress] = useState('');
    const [phoneNumber, setPhoneNumber] = useState('');
    const [paymentMethod, setPaymentMethod] = useState('cod');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState(false);

    const formatPrice = (vnd: number) =>
        new Intl.NumberFormat('vi-VN').format(vnd) + '₫';

    const subtotal = items.reduce((sum, item) => sum + (item.product?.price ?? 0) * item.quantity, 0);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setError('');
        setLoading(true);
        try {
            await api.post('/checkout', { shipping_address: shippingAddress, phone_number: phoneNumber, payment_method: paymentMethod });
            clearCart();
            setSuccess(true);
            setTimeout(() => navigate('/'), 2000);
        } catch (err: unknown) {
            const apiErr = err as { data?: { message?: string } };
            setError(apiErr.data?.message || 'Checkout failed. Please try again.');
        } finally {
            setLoading(false);
        }
    };

    if (success) {
        return (
            <div className="max-w-lg mx-auto px-4 py-20 text-center">
                <div className="w-16 h-16 mx-auto mb-4 rounded-full bg-emerald-400/10 flex items-center justify-center">
                    <Check className="w-8 h-8 text-emerald-400" />
                </div>
                <h2 className="text-xl font-bold text-foreground mb-2">Order Placed!</h2>
                <p className="text-sm text-muted">Your order has been confirmed. Redirecting...</p>
            </div>
        );
    }

    return (
        <div className="max-w-2xl mx-auto px-4 sm:px-6 py-8">
            <Link to="/cart" className="inline-flex items-center gap-1 text-sm text-muted hover:text-foreground transition-colors mb-6">
                <ArrowLeft className="w-4 h-4" /> Back to Cart
            </Link>

            <h1 className="text-xl font-bold text-foreground mb-6">Checkout</h1>

            <form onSubmit={handleSubmit} className="space-y-6">
                <div className="p-5 bg-surface rounded-xl border border-border space-y-4">
                    <h2 className="text-sm font-semibold text-foreground">Shipping Details</h2>
                    <div>
                        <label className="block text-xs text-muted mb-1.5">Shipping Address</label>
                        <textarea
                            required
                            value={shippingAddress}
                            onChange={(e) => setShippingAddress(e.target.value)}
                            rows={3}
                            className="w-full px-3 py-2 text-sm bg-canvas border border-border rounded-lg text-foreground placeholder:text-muted focus:outline-none focus:border-accent transition-colors resize-none"
                            placeholder="123 Main St, City, Country"
                        />
                    </div>
                    <div>
                        <label className="block text-xs text-muted mb-1.5">Phone Number</label>
                        <input
                            type="tel"
                            required
                            value={phoneNumber}
                            onChange={(e) => setPhoneNumber(e.target.value)}
                            className="w-full px-3 py-2 text-sm bg-canvas border border-border rounded-lg text-foreground placeholder:text-muted focus:outline-none focus:border-accent transition-colors"
                            placeholder="+84 123 456 789"
                        />
                    </div>
                </div>

                <div className="p-5 bg-surface rounded-xl border border-border space-y-3">
                    <h2 className="text-sm font-semibold text-foreground">Payment Method</h2>
                    <label className={`flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors ${paymentMethod === 'cod' ? 'border-accent bg-accent/5' : 'border-border'}`}>
                        <input type="radio" name="payment" value="cod" checked={paymentMethod === 'cod'} onChange={() => setPaymentMethod('cod')} className="accent-accent" />
                        <Building2 className="w-5 h-5 text-muted" />
                        <div>
                            <p className="text-sm text-foreground">Cash on Delivery</p>
                            <p className="text-xs text-muted">Pay when you receive</p>
                        </div>
                    </label>
                    <label className={`flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors ${paymentMethod === 'bank_transfer' ? 'border-accent bg-accent/5' : 'border-border'}`}>
                        <input type="radio" name="payment" value="bank_transfer" checked={paymentMethod === 'bank_transfer'} onChange={() => setPaymentMethod('bank_transfer')} className="accent-accent" />
                        <CreditCard className="w-5 h-5 text-muted" />
                        <div>
                            <p className="text-sm text-foreground">Bank Transfer</p>
                            <p className="text-xs text-muted">Pay via bank transaction</p>
                        </div>
                    </label>
                </div>

                <div className="p-5 bg-surface rounded-xl border border-border">
                    <div className="flex items-center justify-between mb-1">
                        <span className="text-sm text-muted">Subtotal ({items.length} items)</span>
                        <span className="text-sm text-foreground">{formatPrice(subtotal)}</span>
                    </div>
                    <div className="flex items-center justify-between pt-3 border-t border-border mt-3">
                        <span className="text-base font-semibold text-foreground">Total</span>
                        <span className="text-xl font-bold text-gradient-tech">{formatPrice(subtotal)}</span>
                    </div>
                </div>

                {error && (
                    <p className="text-sm text-status-out bg-rose-400/10 px-4 py-2 rounded-lg">{error}</p>
                )}

                <button
                    type="submit"
                    disabled={loading}
                    className="w-full px-6 py-3 bg-accent text-white rounded-xl font-medium text-sm hover:bg-accent-hover disabled:opacity-50 transition-colors"
                >
                    {loading ? 'Processing...' : `Place Order — ${formatPrice(subtotal)}`}
                </button>
            </form>
        </div>
    );
}
