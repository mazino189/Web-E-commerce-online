import { useState, useEffect } from 'react';
import { Search, SlidersHorizontal, X } from 'lucide-react';
import api from '../apiClient';
import ProductCard from '../components/ProductCard';

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

interface Category { id: number; name: string; slug: string }
interface Brand { id: number; name: string; slug: string }

interface ListResponse<T> {
    data: T[];
}

export default function Home() {
    const [products, setProducts] = useState<Product[]>([]);
    const [categories, setCategories] = useState<Category[]>([]);
    const [brands, setBrands] = useState<Brand[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [categoryId, setCategoryId] = useState('');
    const [brandId, setBrandId] = useState('');
    const [showFilters, setShowFilters] = useState(false);

    useEffect(() => {
        api.get<ListResponse<Category>>('/categories').then((res) => setCategories(res.data || [])).catch(() => {});
        api.get<ListResponse<Brand>>('/brands').then((res) => setBrands(res.data || [])).catch(() => {});
    }, []);

    useEffect(() => {
        setLoading(true);
        const params = new URLSearchParams();
        if (search) params.set('search', search);
        if (categoryId) params.set('category_id', categoryId);
        if (brandId) params.set('brand_id', brandId);
        api.get<ListResponse<Product>>(`/products?${params.toString()}`)
            .then((res) => setProducts(res.data || []))
            .catch(() => setProducts([]))
            .finally(() => setLoading(false));
    }, [search, categoryId, brandId]);

    const clearFilters = () => {
        setSearch('');
        setCategoryId('');
        setBrandId('');
    };

    const hasFilters = search || categoryId || brandId;

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 className="text-2xl font-bold text-foreground">Premium Electronics</h1>
                    <p className="text-sm text-muted mt-1">
                        {loading ? 'Loading...' : `${products.length} products`}
                    </p>
                </div>
                <div className="flex items-center gap-3 w-full sm:w-auto">
                    <div className="relative flex-1 sm:flex-initial">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" />
                        <input
                            type="text"
                            placeholder="Search products..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className="w-full sm:w-64 pl-9 pr-3 py-2 text-sm bg-surface border border-border rounded-lg text-foreground placeholder:text-muted focus:outline-none focus:border-accent transition-colors"
                        />
                    </div>
                    <button
                        onClick={() => setShowFilters(!showFilters)}
                        className={`p-2 rounded-lg border transition-colors ${showFilters ? 'bg-accent/10 border-accent text-accent' : 'border-border text-muted hover:text-foreground'}`}
                    >
                        <SlidersHorizontal className="w-4 h-4" />
                    </button>
                </div>
            </div>

            {showFilters && (
                <div className="flex flex-wrap items-center gap-3 mb-6 p-4 bg-surface rounded-xl border border-border">
                    <select
                        value={categoryId}
                        onChange={(e) => setCategoryId(e.target.value)}
                        className="px-3 py-1.5 text-sm bg-canvas border border-border rounded-lg text-foreground focus:outline-none focus:border-accent"
                    >
                        <option value="">All Categories</option>
                        {categories.map((c) => (
                            <option key={c.id} value={c.id}>{c.name}</option>
                        ))}
                    </select>
                    <select
                        value={brandId}
                        onChange={(e) => setBrandId(e.target.value)}
                        className="px-3 py-1.5 text-sm bg-canvas border border-border rounded-lg text-foreground focus:outline-none focus:border-accent"
                    >
                        <option value="">All Brands</option>
                        {brands.map((b) => (
                            <option key={b.id} value={b.id}>{b.name}</option>
                        ))}
                    </select>
                    {hasFilters && (
                        <button
                            onClick={clearFilters}
                            className="flex items-center gap-1 px-3 py-1.5 text-sm text-muted hover:text-rose-400 transition-colors"
                        >
                            <X className="w-3 h-3" /> Clear
                        </button>
                    )}
                </div>
            )}

            {loading ? (
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    {Array.from({ length: 8 }).map((_, i) => (
                        <div key={i} className="bg-surface rounded-xl border border-border overflow-hidden animate-pulse">
                            <div className="aspect-[4/3] bg-slate-800" />
                            <div className="p-4 space-y-3">
                                <div className="h-3 w-16 bg-slate-800 rounded" />
                                <div className="h-4 w-3/4 bg-slate-800 rounded" />
                                <div className="h-3 w-1/2 bg-slate-800 rounded" />
                                <div className="h-5 w-1/3 bg-slate-800 rounded" />
                            </div>
                        </div>
                    ))}
                </div>
            ) : products.length === 0 ? (
                <div className="text-center py-20">
                    <p className="text-muted">No products found</p>
                    {hasFilters && (
                        <button onClick={clearFilters} className="mt-2 text-sm text-accent hover:underline">
                            Clear filters
                        </button>
                    )}
                </div>
            ) : (
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    {products.map((product) => (
                        <ProductCard key={product.id} product={product} />
                    ))}
                </div>
            )}
        </div>
    );
}
