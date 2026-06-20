import { useEffect, useState, useRef } from 'react';
import api from '../apiClient';
import { Package, Plus, Search, Edit2, Trash2, X, Upload } from 'lucide-react';

interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
    stock: number;
    status: string;
    image?: string;
    category_id: number;
    brand_id: number;
    category?: { id: number; name: string };
    brand?: { id: number; name: string };
}

interface Category { id: number; name: string; }
interface Brand { id: number; name: string; }

export default function AdminProducts() {
    const [products, setProducts] = useState<Product[]>([]);
    const [categories, setCategories] = useState<Category[]>([]);
    const [brands, setBrands] = useState<Brand[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editingProduct, setEditingProduct] = useState<Product | null>(null);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const formRef = useRef<HTMLFormElement>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);

    useEffect(() => {
        Promise.all([
            api.get<{ data: Product[] }>('/admin/products'),
            api.get<{ data: Category[] }>('/admin/categories'),
            api.get<{ data: Brand[] }>('/admin/brands')
        ]).then(([pRes, cRes, bRes]) => {
            setProducts(pRes.data || []);
            setCategories(cRes.data || []);
            setBrands(bRes.data || []);
        }).finally(() => setLoading(false));
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

    const handleOpenModal = (product?: Product) => {
        setEditingProduct(product || null);
        setIsModalOpen(true);
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
        setEditingProduct(null);
    };

    const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        setIsSubmitting(true);

        const formData = new FormData(e.currentTarget);
        
        try {
            if (editingProduct) {
                // Method spoofing for PUT since FormData with file works better with POST + _method=PUT in Laravel
                formData.append('_method', 'PUT');
                const res = await api.post<{ data: Product }>(`/admin/products/${editingProduct.id}`, formData);
                setProducts(products.map(p => p.id === editingProduct.id ? res.data : p));
            } else {
                const res = await api.post<{ data: Product }>('/admin/products', formData);
                setProducts([res.data, ...products]);
            }
            handleCloseModal();
        } catch (err) {
            console.error('Failed to save product', err);
            alert('Failed to save product. Please check inputs.');
        } finally {
            setIsSubmitting(false);
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
                <button 
                    onClick={() => handleOpenModal()}
                    className="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-colors"
                >
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
                                <th className="px-6 py-4 font-medium">Image</th>
                                <th className="px-6 py-4 font-medium">Name</th>
                                <th className="px-6 py-4 font-medium">Category / Brand</th>
                                <th className="px-6 py-4 font-medium">Price</th>
                                <th className="px-6 py-4 font-medium">Stock</th>
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
                                        <td className="px-6 py-4">
                                            <div className="w-12 h-12 rounded bg-gray-100 overflow-hidden">
                                                <img src={product.image || 'https://via.placeholder.com/150'} alt={product.name} className="w-full h-full object-cover" />
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 text-sm font-medium text-foreground">{product.name}</td>
                                        <td className="px-6 py-4 text-sm text-muted">
                                            {product.category?.name || 'N/A'} <br/>
                                            <span className="text-xs opacity-70">{product.brand?.name || 'N/A'}</span>
                                        </td>
                                        <td className="px-6 py-4 text-sm text-foreground">{formatPrice(product.price)}</td>
                                        <td className="px-6 py-4 text-sm text-foreground">{product.stock}</td>
                                        <td className="px-6 py-4 text-sm text-right space-x-3">
                                            <button onClick={() => handleOpenModal(product)} className="text-muted hover:text-accent transition-colors">
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

            {/* Modal */}
            {isModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                    <div className="bg-surface rounded-xl border border-border w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                        <div className="flex items-center justify-between p-6 border-b border-border">
                            <h2 className="text-xl font-semibold text-foreground">
                                {editingProduct ? 'Edit Product' : 'Add New Product'}
                            </h2>
                            <button onClick={handleCloseModal} className="text-muted hover:text-foreground transition-colors">
                                <X className="w-5 h-5" />
                            </button>
                        </div>

                        <form ref={formRef} onSubmit={handleSubmit} className="p-6 space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div className="space-y-2 md:col-span-2">
                                    <label className="text-sm font-medium text-foreground">Name</label>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        required 
                                        defaultValue={editingProduct?.name}
                                        className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                    />
                                </div>
                                <div className="space-y-2 md:col-span-2">
                                    <label className="text-sm font-medium text-foreground">Description</label>
                                    <textarea 
                                        name="description" 
                                        required 
                                        rows={3}
                                        defaultValue={editingProduct?.description}
                                        className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent resize-none"
                                    ></textarea>
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-medium text-foreground">Price (VND)</label>
                                    <input 
                                        type="number" 
                                        name="price" 
                                        min="0"
                                        required 
                                        defaultValue={editingProduct?.price}
                                        className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                    />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-medium text-foreground">Stock</label>
                                    <input 
                                        type="number" 
                                        name="stock" 
                                        min="0"
                                        required 
                                        defaultValue={editingProduct?.stock}
                                        className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                    />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-medium text-foreground">Category</label>
                                    <select 
                                        name="category_id" 
                                        required
                                        defaultValue={editingProduct?.category_id || ''}
                                        className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                    >
                                        <option value="" disabled>Select Category</option>
                                        {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                                    </select>
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-medium text-foreground">Brand</label>
                                    <select 
                                        name="brand_id" 
                                        required
                                        defaultValue={editingProduct?.brand_id || ''}
                                        className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                    >
                                        <option value="" disabled>Select Brand</option>
                                        {brands.map(b => <option key={b.id} value={b.id}>{b.name}</option>)}
                                    </select>
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-medium text-foreground">Status</label>
                                    <select 
                                        name="status" 
                                        required
                                        defaultValue={editingProduct?.status || 'active'}
                                        className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                    >
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-medium text-foreground">Product Image</label>
                                    <div className="flex items-center gap-3">
                                        <button 
                                            type="button"
                                            onClick={() => fileInputRef.current?.click()}
                                            className="px-4 py-2 border border-border rounded-lg text-sm text-muted hover:text-foreground hover:border-accent transition-colors flex items-center gap-2"
                                        >
                                            <Upload className="w-4 h-4" /> Upload Image
                                        </button>
                                        <input 
                                            ref={fileInputRef}
                                            type="file" 
                                            name="image" 
                                            accept="image/*"
                                            className="hidden"
                                            onChange={(e) => {
                                                const fileName = e.target.files?.[0]?.name;
                                                if (fileName) alert(`Selected: ${fileName}`);
                                            }}
                                        />
                                        <span className="text-xs text-muted">Max 2MB (JPG, PNG)</span>
                                    </div>
                                </div>
                            </div>
                            <div className="flex justify-end gap-3 pt-6 border-t border-border">
                                <button 
                                    type="button" 
                                    onClick={handleCloseModal}
                                    className="px-4 py-2 text-sm font-medium text-muted hover:text-foreground transition-colors"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit" 
                                    disabled={isSubmitting}
                                    className="px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-colors disabled:opacity-50"
                                >
                                    {isSubmitting ? 'Saving...' : 'Save Product'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
}
