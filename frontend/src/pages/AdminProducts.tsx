import { useEffect, useState } from 'react';
import api from '../apiClient';
import { Package, Plus, Search, Edit2, Trash2 } from 'lucide-react';

interface Product {
    id: number;
    name: string;
    price: number;
    stock: number;
    status: string;
    category?: { name: string };
    brand?: { name: string };
}

export default function AdminProducts() {
    const [products, setProducts] = useState<Product[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');

    useEffect(() => {
        api.get<{ data: Product[] }>('/admin/products')
            .then(res => setProducts(res.data || []))
            .finally(() => setLoading(false));
    }, []);

    const formatPrice = (vnd: number) => new Intl.NumberFormat('vi-VN').format(vnd) + '₫';

    const handleDelete = async (id: number) => {
        if (!window.confirm('Are you sure you want to delete this product?')) return;
        try {
            await api.delete(`/admin/products/${id}`);
            setProducts(products.filter(p => p.id !== id));
        } catch (err) {
            console.error('Failed to delete product', err);
        }
    };

    const filteredProducts = products.filter(p => p.name.toLowerCase().includes(search.toLowerCase()));

    return (
        <div className="space-y-6">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-foreground">Products</h1>
                    <p className="text-sm text-muted">Manage your store's inventory.</p>
                </div>
                <button className="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-colors">
                    <Plus className="w-4 h-4" /> Add Product
                </button>
            </div>

            <div className="bg-surface border border-border rounded-xl overflow-hidden flex flex-col">
                <div className="p-4 border-b border-border flex items-center gap-4">
                    <div className="relative flex-1 max-w-md">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" />
                        <input
                            type="text"
                            placeholder="Search products..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm bg-canvas border border-border rounded-lg text-foreground placeholder:text-muted focus:outline-none focus:border-accent transition-colors"
                        />
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="border-b border-border bg-gray-50 text-xs text-muted uppercase tracking-wider">
                                <th className="px-6 py-4 font-medium">Name</th>
                                <th className="px-6 py-4 font-medium">Category / Brand</th>
                                <th className="px-6 py-4 font-medium">Price</th>
                                <th className="px-6 py-4 font-medium">Stock</th>
                                <th className="px-6 py-4 font-medium">Status</th>
                                <th className="px-6 py-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {loading ? (
                                <tr>
                                    <td colSpan={6} className="px-6 py-8 text-center text-muted">Loading products...</td>
                                </tr>
                            ) : filteredProducts.length === 0 ? (
                                <tr>
                                    <td colSpan={6} className="px-6 py-8 text-center text-muted">
                                        <Package className="w-8 h-8 mx-auto mb-2 opacity-50" />
                                        No products found.
                                    </td>
                                </tr>
                            ) : (
                                filteredProducts.map((product) => (
                                    <tr key={product.id} className="hover:bg-gray-50 transition-colors">
                                        <td className="px-6 py-4 text-sm font-medium text-foreground">{product.name}</td>
                                        <td className="px-6 py-4 text-sm text-muted">
                                            {product.category?.name || 'N/A'} <br/>
                                            <span className="text-xs opacity-70">{product.brand?.name || 'N/A'}</span>
                                        </td>
                                        <td className="px-6 py-4 text-sm text-foreground">{formatPrice(product.price)}</td>
                                        <td className="px-6 py-4 text-sm text-foreground">{product.stock}</td>
                                        <td className="px-6 py-4 text-sm">
                                            <span className={`px-2 py-1 rounded-full text-xs font-medium ${
                                                product.status === 'active' ? 'bg-emerald-400/10 text-emerald-400' : 'bg-rose-400/10 text-rose-400'
                                            }`}>
                                                {product.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-sm text-right space-x-3">
                                            <button className="text-muted hover:text-accent transition-colors">
                                                <Edit2 className="w-4 h-4 inline" />
                                            </button>
                                            <button onClick={() => handleDelete(product.id)} className="text-muted hover:text-rose-400 transition-colors">
                                                <Trash2 className="w-4 h-4 inline" />
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
