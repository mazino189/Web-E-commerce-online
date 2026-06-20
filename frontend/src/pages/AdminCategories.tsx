import { useEffect, useState } from 'react';
import api from '../apiClient';
import { Tags, Plus, Search, Edit2, Trash2 } from 'lucide-react';

interface Category {
    id: number;
    name: string;
    slug: string;
    description: string;
}

export default function AdminCategories() {
    const [categories, setCategories] = useState<Category[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');

    useEffect(() => {
        api.get<{ data: Category[] }>('/admin/categories')
            .then(res => setCategories(res.data || []))
            .finally(() => setLoading(false));
    }, []);

    const handleDelete = async (id: number) => {
        if (!window.confirm('Are you sure you want to delete this category?')) return;
        try {
            await api.delete(`/admin/categories/${id}`);
            setCategories(categories.filter(c => c.id !== id));
        } catch (err) {
            console.error('Failed to delete category', err);
        }
    };

    const filteredCategories = categories.filter(c => c.name.toLowerCase().includes(search.toLowerCase()));

    return (
        <div className="space-y-6">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-foreground">Categories</h1>
                    <p className="text-sm text-muted">Manage product categories.</p>
                </div>
                <button className="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-colors">
                    <Plus className="w-4 h-4" /> Add Category
                </button>
            </div>

            <div className="bg-surface border border-border rounded-xl overflow-hidden flex flex-col">
                <div className="p-4 border-b border-border flex items-center gap-4">
                    <div className="relative flex-1 max-w-md">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" />
                        <input
                            type="text"
                            placeholder="Search categories..."
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
                                <th className="px-6 py-4 font-medium">Description</th>
                                <th className="px-6 py-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {loading ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-8 text-center text-muted">Loading categories...</td>
                                </tr>
                            ) : filteredCategories.length === 0 ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-8 text-center text-muted">
                                        <Tags className="w-8 h-8 mx-auto mb-2 opacity-50" />
                                        No categories found.
                                    </td>
                                </tr>
                            ) : (
                                filteredCategories.map((category) => (
                                    <tr key={category.id} className="hover:bg-gray-50 transition-colors">
                                        <td className="px-6 py-4 text-sm font-medium text-foreground">{category.name}</td>
                                        <td className="px-6 py-4 text-sm text-muted">{category.slug}</td>
                                        <td className="px-6 py-4 text-sm text-muted truncate max-w-xs">{category.description || 'N/A'}</td>
                                        <td className="px-6 py-4 text-sm text-right space-x-3">
                                            <button className="text-muted hover:text-accent transition-colors">
                                                <Edit2 className="w-4 h-4 inline" />
                                            </button>
                                            <button onClick={() => handleDelete(category.id)} className="text-muted hover:text-rose-400 transition-colors">
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
