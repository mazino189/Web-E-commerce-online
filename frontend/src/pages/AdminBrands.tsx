import { useEffect, useState, useRef } from 'react';
import api from '../apiClient';
import { Briefcase, Plus, Search, Edit2, Trash2, X, AlertCircle } from 'lucide-react';

interface Brand {
    id: number;
    name: string;
    slug: string;
    logo?: string;
}

type ModalMode = 'create' | 'edit';

export default function AdminBrands() {
    const [brands, setBrands] = useState<Brand[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [modalMode, setModalMode] = useState<ModalMode>('create');
    const [editingBrand, setEditingBrand] = useState<Brand | null>(null);
    const [formData, setFormData] = useState({ name: '', slug: '', description: '' });
    const [logoFile, setLogoFile] = useState<File | null>(null);
    const [submitting, setSubmitting] = useState(false);
    const [formError, setFormError] = useState('');
    const fileInputRef = useRef<HTMLInputElement>(null);

    useEffect(() => {
        fetchBrands();
    }, []);

    const fetchBrands = () => {
        setLoading(true);
        api.get<{ data: Brand[] }>('/admin/brands')
            .then(res => setBrands(res.data || []))
            .catch(() => setBrands([]))
            .finally(() => setLoading(false));
    };

    const openCreateModal = () => {
        setModalMode('create');
        setEditingBrand(null);
        setFormData({ name: '', slug: '', description: '' });
        setLogoFile(null);
        setFormError('');
        setIsModalOpen(true);
    };

    const openEditModal = (brand: Brand) => {
        setModalMode('edit');
        setEditingBrand(brand);
        setFormData({ name: brand.name, slug: brand.slug, description: '' });
        setLogoFile(null);
        setFormError('');
        setIsModalOpen(true);
    };

    const closeModal = () => {
        setIsModalOpen(false);
        setFormError('');
    };

    const handleNameChange = (val: string) => {
        const slug = val.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
        setFormData(f => ({ ...f, name: val, slug }));
    };

    const handleDelete = async (id: number) => {
        if (!window.confirm('Are you sure you want to delete this brand?')) return;
        try {
            await api.delete(`/admin/brands/${id}`);
            setBrands(brands.filter(b => b.id !== id));
        } catch (err: any) {
            alert(err.data?.message || 'Failed to delete brand.');
        }
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setFormError('');
        setSubmitting(true);
        try {
            const data = new FormData();
            data.append('name', formData.name);
            data.append('slug', formData.slug);
            data.append('description', formData.description);
            data.append('status', '1');
            if (logoFile) data.append('logo', logoFile);

            if (modalMode === 'create') {
                await api.post('/admin/brands', data);
            } else if (editingBrand) {
                data.append('_method', 'PUT');
                await api.post(`/admin/brands/${editingBrand.id}`, data);
            }
            closeModal();
            fetchBrands();
        } catch (err: any) {
            const msg = err.data?.errors
                ? Object.values(err.data.errors).flat().join(', ')
                : err.data?.message || 'Failed to save brand.';
            setFormError(msg);
        } finally {
            setSubmitting(false);
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
                    onClick={openCreateModal}
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
                            <tr className="border-b border-border bg-surface text-xs text-muted uppercase tracking-wider">
                                <th className="px-6 py-4 font-medium">Logo</th>
                                <th className="px-6 py-4 font-medium">Name</th>
                                <th className="px-6 py-4 font-medium">Slug</th>
                                <th className="px-6 py-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {loading ? (
                                <tr><td colSpan={4} className="px-6 py-8 text-center text-muted">Loading brands...</td></tr>
                            ) : filteredBrands.length === 0 ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-8 text-center text-muted">
                                        <Briefcase className="w-8 h-8 mx-auto mb-2 opacity-50" />
                                        No brands found.
                                    </td>
                                </tr>
                            ) : (
                                filteredBrands.map((brand) => (
                                    <tr key={brand.id} className="hover:bg-canvas transition-colors">
                                        <td className="px-6 py-4">
                                            {brand.logo ? (
                                                <img src={brand.logo} alt={brand.name} className="w-10 h-10 object-contain rounded" />
                                            ) : (
                                                <div className="w-10 h-10 bg-canvas border border-border rounded flex items-center justify-center text-xs text-muted">N/A</div>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 text-sm font-medium text-foreground">{brand.name}</td>
                                        <td className="px-6 py-4 text-sm text-muted font-mono">{brand.slug}</td>
                                        <td className="px-6 py-4 text-sm text-right space-x-3">
                                            <button onClick={() => openEditModal(brand)} className="text-muted hover:text-accent transition-colors">
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

            {/* Create / Edit Modal */}
            {isModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                    <div className="bg-surface w-full max-w-md rounded-2xl border border-border shadow-2xl flex flex-col max-h-[90vh]">
                        <div className="p-6 border-b border-border flex items-center justify-between">
                            <h2 className="text-xl font-bold text-foreground">
                                {modalMode === 'create' ? 'Add New Brand' : `Edit Brand — ${editingBrand?.name}`}
                            </h2>
                            <button onClick={closeModal} className="text-muted hover:text-foreground transition-colors">
                                <X className="w-5 h-5" />
                            </button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-6 space-y-4 overflow-y-auto">
                            {formError && (
                                <div className="flex items-center gap-2 px-4 py-3 rounded-xl bg-status-out/10 border border-status-out/20">
                                    <AlertCircle className="w-4 h-4 text-status-out shrink-0" />
                                    <p className="text-sm text-status-out">{formError}</p>
                                </div>
                            )}
                            <div>
                                <label className="block text-sm font-medium text-foreground mb-1">Name *</label>
                                <input
                                    type="text"
                                    required
                                    value={formData.name}
                                    onChange={(e) => handleNameChange(e.target.value)}
                                    className="w-full px-4 py-2.5 bg-canvas border border-border rounded-xl text-foreground focus:outline-none focus:border-accent transition-colors text-sm"
                                    placeholder="Brand name"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-foreground mb-1">Slug *</label>
                                <input
                                    type="text"
                                    required
                                    value={formData.slug}
                                    onChange={(e) => setFormData(f => ({ ...f, slug: e.target.value }))}
                                    className="w-full px-4 py-2.5 bg-canvas border border-border rounded-xl text-foreground font-mono focus:outline-none focus:border-accent transition-colors text-sm"
                                    placeholder="brand-slug"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-foreground mb-1">Description</label>
                                <textarea
                                    value={formData.description}
                                    onChange={(e) => setFormData(f => ({ ...f, description: e.target.value }))}
                                    className="w-full px-4 py-2.5 bg-canvas border border-border rounded-xl text-foreground focus:outline-none focus:border-accent transition-colors text-sm resize-none"
                                    rows={2}
                                    placeholder="Optional brand description"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-foreground mb-1">
                                    Logo {modalMode === 'edit' && '(leave empty to keep existing)'}
                                </label>
                                <input
                                    type="file"
                                    accept="image/*"
                                    ref={fileInputRef}
                                    onChange={(e) => setLogoFile(e.target.files?.[0] || null)}
                                    className="w-full text-sm text-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-accent/10 file:text-accent hover:file:bg-accent/20 cursor-pointer"
                                />
                            </div>
                            <div className="pt-4 border-t border-border flex justify-end gap-3">
                                <button type="button" onClick={closeModal} className="px-4 py-2 text-sm font-medium text-muted hover:text-foreground transition-colors">
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={submitting}
                                    className="px-5 py-2 bg-accent text-white rounded-xl text-sm font-medium hover:bg-accent-hover transition-colors disabled:opacity-60"
                                >
                                    {submitting ? 'Saving...' : (modalMode === 'create' ? 'Create Brand' : 'Save Changes')}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
}
