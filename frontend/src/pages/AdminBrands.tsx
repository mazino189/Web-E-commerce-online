import { useEffect, useState, useRef } from 'react';
import api from '../apiClient';
import { Briefcase, Plus, Search, Edit2, Trash2, X } from 'lucide-react';

interface Brand {
    id: number;
    name: string;
    slug: string;
    logo?: string;
}

export default function AdminBrands() {
    const [brands, setBrands] = useState<Brand[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [formData, setFormData] = useState({ name: '', slug: '', description: '' });
    const [logoFile, setLogoFile] = useState<File | null>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);

    useEffect(() => {
        fetchBrands();
    }, []);

    const fetchBrands = () => {
        api.get<{ data: Brand[] }>('/admin/brands')
            .then(res => setBrands(res.data || []))
            .finally(() => setLoading(false));
    };

    const handleDelete = async (id: number) => {
        if (!window.confirm('Are you sure you want to delete this brand?')) return;
        try {
            await api.delete(`/admin/brands/${id}`);
            setBrands(brands.filter(b => b.id !== id));
        } catch (err) {
            console.error('Failed to delete brand', err);
        }
    };

    const handleCreateBrand = async (e: React.FormEvent) => {
        e.preventDefault();
        try {
            const data = new FormData();
            data.append('name', formData.name);
            data.append('slug', formData.slug);
            data.append('description', formData.description);
            if (logoFile) {
                data.append('logo', logoFile);
            }
            data.append('status', '1');

            await api.post('/admin/brands', data);
            setIsModalOpen(false);
            setFormData({ name: '', slug: '', description: '' });
            setLogoFile(null);
            fetchBrands();
        } catch (err) {
            console.error('Failed to create brand', err);
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
                <button 
                    onClick={() => setIsModalOpen(true)}
                    className="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-colors"
                >
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
                                <th className="px-6 py-4 font-medium">Logo</th>
                                <th className="px-6 py-4 font-medium">Name</th>
                                <th className="px-6 py-4 font-medium">Slug</th>
                                <th className="px-6 py-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {loading ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-8 text-center text-muted">Loading brands...</td>
                                </tr>
                            ) : filteredBrands.length === 0 ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-8 text-center text-muted">
                                        <Briefcase className="w-8 h-8 mx-auto mb-2 opacity-50" />
                                        No brands found.
                                    </td>
                                </tr>
                            ) : (
                                filteredBrands.map((brand) => (
                                    <tr key={brand.id} className="hover:bg-gray-50 transition-colors">
                                        <td className="px-6 py-4">
                                            {brand.logo ? (
                                                <img src={brand.logo} alt={brand.name} className="w-10 h-10 object-contain" />
                                            ) : (
                                                <div className="w-10 h-10 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">N/A</div>
                                            )}
                                        </td>
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

            {isModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
                    <div className="bg-surface w-full max-w-md rounded-2xl border border-border shadow-2xl flex flex-col max-h-[90vh]">
                        <div className="p-6 border-b border-border flex items-center justify-between">
                            <h2 className="text-xl font-bold text-foreground">Add New Brand</h2>
                            <button onClick={() => setIsModalOpen(false)} className="text-muted hover:text-foreground">
                                <X className="w-5 h-5" />
                            </button>
                        </div>
                        <form onSubmit={handleCreateBrand} className="p-6 space-y-4 overflow-y-auto">
                            <div>
                                <label className="block text-sm font-medium text-foreground mb-1">Name</label>
                                <input
                                    type="text"
                                    required
                                    value={formData.name}
                                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                    className="w-full px-4 py-2 bg-canvas border border-border rounded-xl text-foreground focus:outline-none focus:border-accent"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-foreground mb-1">Slug</label>
                                <input
                                    type="text"
                                    required
                                    value={formData.slug}
                                    onChange={(e) => setFormData({ ...formData, slug: e.target.value })}
                                    className="w-full px-4 py-2 bg-canvas border border-border rounded-xl text-foreground focus:outline-none focus:border-accent"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-foreground mb-1">Description</label>
                                <textarea
                                    value={formData.description}
                                    onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                                    className="w-full px-4 py-2 bg-canvas border border-border rounded-xl text-foreground focus:outline-none focus:border-accent"
                                    rows={3}
                                ></textarea>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-foreground mb-1">Logo</label>
                                <input
                                    type="file"
                                    ref={fileInputRef}
                                    onChange={(e) => setLogoFile(e.target.files?.[0] || null)}
                                    className="w-full text-sm text-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-accent/10 file:text-accent hover:file:bg-accent/20"
                                />
                            </div>
                            <div className="pt-4 border-t border-border flex justify-end gap-3">
                                <button type="button" onClick={() => setIsModalOpen(false)} className="px-4 py-2 text-sm font-medium text-muted hover:text-foreground">
                                    Cancel
                                </button>
                                <button type="submit" className="px-4 py-2 bg-accent text-white rounded-xl text-sm font-medium hover:bg-accent-hover transition-colors">
                                    Create Brand
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
}
