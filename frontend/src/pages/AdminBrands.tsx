import { useEffect, useState } from 'react';
import api from '../apiClient';
import { Briefcase, Plus, Search, Edit2, Trash2 } from 'lucide-react';

interface Brand {
    id: number;
    name: string;
    slug: string;
}

export default function AdminBrands() {
    const [brands, setBrands] = useState<Brand[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');

    useEffect(() => {
        api.get<{ data: Brand[] }>('/admin/brands')
            .then(res => setBrands(res.data || []))
            .finally(() => setLoading(false));
    }, []);

    const handleDelete = async (id: number) => {
        if (!window.confirm('Are you sure you want to delete this brand?')) return;
        try {
            await api.delete(`/admin/brands/${id}`);
            setBrands(brands.filter(b => b.id !== id));
        } catch (err) {
            console.error('Failed to delete brand', err);
        }
    };

    const filteredBrands = brands.filter(b => b.name.toLowerCase().includes(search.toLowerCase()));

    return (
        <div className="space-y-6">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-foreground">Brands</h1>
                    <p className="text-sm text-muted">Manage product brands.</p>
                </div>
                <button className="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-colors">
                    <Plus className="w-4 h-4" /> Add Brand
                </button>
            </div>

            <div className="bg-surface border border-border rounded-xl overflow-hidden flex flex-col">
                <div className="p-4 border-b border-border flex items-center gap-4">
                    <div className="relative flex-1 max-w-md">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" />
                        <input
                            type="text"
                            placeholder="Search brands..."
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
                                <th className="px-6 py-4 font-medium">Slug</th>
                                <th className="px-6 py-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {loading ? (
                                <tr>
                                    <td colSpan={3} className="px-6 py-8 text-center text-muted">Loading brands...</td>
                                </tr>
                            ) : filteredBrands.length === 0 ? (
                                <tr>
                                    <td colSpan={3} className="px-6 py-8 text-center text-muted">
                                        <Briefcase className="w-8 h-8 mx-auto mb-2 opacity-50" />
                                        No brands found.
                                    </td>
                                </tr>
                            ) : (
                                filteredBrands.map((brand) => (
                                    <tr key={brand.id} className="hover:bg-gray-50 transition-colors">
                                        <td className="px-6 py-4 text-sm font-medium text-foreground">{brand.name}</td>
                                        <td className="px-6 py-4 text-sm text-muted">{brand.slug}</td>
                                        <td className="px-6 py-4 text-sm text-right space-x-3">
                                            <button className="text-muted hover:text-accent transition-colors">
                                                <Edit2 className="w-4 h-4 inline" />
                                            </button>
                                            <button onClick={() => handleDelete(brand.id)} className="text-muted hover:text-rose-400 transition-colors">
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
