import { useState, useEffect } from 'react';
import { useSearchParams } from 'react-router-dom';
import { SlidersHorizontal, X } from 'lucide-react';
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
    const [searchParams] = useSearchParams();
    const search = searchParams.get('search') || '';

    const [products, setProducts] = useState<Product[]>([]);
    const [categories, setCategories] = useState<Category[]>([]);
    const [brands, setBrands] = useState<Brand[]>([]);
    const [loading, setLoading] = useState(true);
    
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
        setCategoryId('');
        setBrandId('');
    };

    const hasFilters = search || categoryId || brandId;

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 className="text-3xl font-bold text-foreground">
                        {search ? `Search results for "${search}"` : 'Explore Collection'}
                    </h1>
                    <p className="text-sm text-muted mt-2">
                        {loading ? 'Loading...' : `${products.length} products available`}
                    </p>
                </div>
                <div className="flex items-center gap-3 w-full sm:w-auto">
                    <button
                        onClick={() => setShowFilters(!showFilters)}
                        className={`flex items-center gap-2 px-4 py-2 rounded-xl border text-sm font-medium transition-colors ${showFilters ? 'bg-accent/10 border-accent text-accent' : 'border-border bg-surface text-foreground hover:border-accent'}`}
                    >
                        <SlidersHorizontal className="w-4 h-4" />
                        Filters
                    </button>
                </div>
            </div>

            {showFilters && (
                <div className="flex flex-wrap items-center gap-4 mb-8 p-6 bg-surface rounded-2xl border border-border">
                    <select
                        value={categoryId}
                        onChange={(e) => setCategoryId(e.target.value)}
                        className="px-4 py-2.5 text-sm bg-canvas border border-border rounded-xl text-foreground focus:outline-none focus:border-accent transition-colors"
                    >
                        <option value="">All Categories</option>
                        {categories.map((c) => (
                            <option key={c.id} value={c.id}>{c.name}</option>
                        ))}
                    </select>
                    <select
                        value={brandId}
                        onChange={(e) => setBrandId(e.target.value)}
                        className="px-4 py-2.5 text-sm bg-canvas border border-border rounded-xl text-foreground focus:outline-none focus:border-accent transition-colors"
                    >
                        <option value="">All Brands</option>
                        {brands.map((b) => (
                            <option key={b.id} value={b.id}>{b.name}</option>
                        ))}
                    </select>
                    {(categoryId || brandId) && (
                        <button
                            onClick={clearFilters}
                            className="flex items-center gap-1 px-3 py-1.5 text-sm text-muted hover:text-rose-400 transition-colors"
                        >
                            <X className="w-4 h-4" /> Clear Filters
                        </button>
                    )}
                </div>
            )}

            {loading ? (
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    {Array.from({ length: 8 }).map((_, i) => (
                        <div key={i} className="bg-surface rounded-2xl border border-border overflow-hidden animate-pulse">
                            <div className="aspect-[4/3] bg-gray-200" />
                            <div className="p-5 space-y-3">
                                <div className="h-3 w-16 bg-gray-200 rounded" />
                                <div className="h-4 w-3/4 bg-gray-200 rounded" />
                                <div className="h-3 w-1/2 bg-gray-200 rounded" />
                                <div className="h-5 w-1/3 bg-gray-200 rounded" />
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
