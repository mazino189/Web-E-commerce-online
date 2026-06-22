import { useEffect, useState } from 'react';
import api from '../apiClient';
import { Tags, Plus, Search, Edit2, Trash2, X, Upload, AlertCircle } from 'lucide-react';

interface Category {
    id: number;
    name: string;
    slug: string;
    description: string;
    image?: string;
    status: number;
}

type ModalMode = 'create' | 'edit';

export default function AdminCategories() {
    const [categories, setCategories] = useState<Category[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [modalMode, setModalMode] = useState<ModalMode>('create');
    const [editingCategory, setEditingCategory] = useState<Category | null>(null);

    // Form state
    const [name, setName] = useState('');
    const [slug, setSlug] = useState('');
    const [description, setDescription] = useState('');
    const [imageFile, setImageFile] = useState<File | null>(null);
    const [status, setStatus] = useState(1);
    const [submitting, setSubmitting] = useState(false);
    const [formError, setFormError] = useState('');

    useEffect(() => {
        fetchCategories();
    }, []);

    const fetchCategories = async () => {
        try {
            const res = await api.get<{ data: Category[] }>('/admin/categories');
            setCategories(res.data || []);
        } catch (error) {
            console.error('Failed to fetch categories', error);
        } finally {
            setLoading(false);
        }
    };

    const resetForm = () => {
        setName('');
        setSlug('');
        setDescription('');
        setImageFile(null);
        setStatus(1);
        setFormError('');
    };

    const openCreateModal = () => {
        setModalMode('create');
        setEditingCategory(null);
        resetForm();
        setIsModalOpen(true);
    };

    const openEditModal = (cat: Category) => {
        setModalMode('edit');
        setEditingCategory(cat);
        setName(cat.name);
        setSlug(cat.slug);
        setDescription(cat.description || '');
        setStatus(cat.status);
        setImageFile(null);
        setFormError('');
        setIsModalOpen(true);
    };

    const closeModal = () => {
        setIsModalOpen(false);
        setFormError('');
    };

    const handleDelete = async (id: number) => {
        if (!window.confirm('Are you sure you want to delete this category?')) return;
        try {
            await api.delete(`/admin/categories/${id}`);
            setCategories(categories.filter(c => c.id !== id));
        } catch (err: any) {
            alert(err.data?.message || 'Failed to delete category.');
        }
    };

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setImageFile(e.target.files[0]);
        }
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setSubmitting(true);
        setFormError('');

        const formData = new FormData();
        formData.append('name', name);
        formData.append('slug', slug);
        formData.append('description', description);
        formData.append('status', status.toString());
        if (imageFile) formData.append('image', imageFile);

        try {
            if (modalMode === 'create') {
                await api.post('/admin/categories', formData);
            } else if (editingCategory) {
                formData.append('_method', 'PUT');
                await api.post(`/admin/categories/${editingCategory.id}`, formData);
            }
            fetchCategories();
            closeModal();
            resetForm();
        } catch (err: any) {
            const msg = err.data?.errors
                ? Object.values(err.data.errors).flat().join(', ')
                : err.data?.message || 'Failed to save category. Check your inputs.';
            setFormError(msg);
        } finally {
            setSubmitting(false);
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
                <button 
                    onClick={openCreateModal}
                    className="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-colors shadow-lg"
                >
                    <Plus className="w-4 h-4" /> Add Category
                </button>
            </div>

            {/* Modal */}
            {isModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                    <div className="bg-surface border border-border rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
                        <div className="flex items-center justify-between p-6 border-b border-border">
                            <h2 className="text-xl font-bold text-foreground">
                                {modalMode === 'create' ? 'Add New Category' : `Edit — ${editingCategory?.name}`}
                            </h2>
                            <button onClick={closeModal} className="text-muted hover:text-foreground transition-colors">
                                <X className="w-5 h-5" />
                            </button>
                        </div>
                        <div className="p-6 overflow-y-auto">
                            {formError && (
                                <div className="flex items-center gap-2 px-4 py-3 rounded-xl bg-status-out/10 border border-status-out/20 mb-5">
                                    <AlertCircle className="w-4 h-4 text-status-out shrink-0" />
                                    <p className="text-sm text-status-out">{formError}</p>
                                </div>
                            )}
                            <form id="categoryForm" onSubmit={handleSubmit} className="space-y-5">
                                <div>
                                    <label className="block text-sm font-medium text-foreground mb-1">Name *</label>
                                    <input type="text" required value={name} onChange={e => {
                                        setName(e.target.value);
                                        setSlug(e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, ''));
                                    }} className="w-full px-4 py-2.5 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent transition-colors" placeholder="Category name" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-foreground mb-1">Slug *</label>
                                    <input type="text" required value={slug} onChange={e => setSlug(e.target.value)} className="w-full px-4 py-2.5 bg-canvas border border-border rounded-lg text-sm font-mono text-foreground focus:outline-none focus:border-accent transition-colors" placeholder="category-slug" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-foreground mb-1">Description</label>
                                    <textarea value={description} onChange={e => setDescription(e.target.value)} rows={3} className="w-full px-4 py-2.5 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent transition-colors resize-none" placeholder="Optional description" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-foreground mb-1">
                                        Image {modalMode === 'edit' && '(leave empty to keep existing)'}
                                    </label>
                                    <div className="flex items-center gap-4">
                                        <label className="cursor-pointer flex items-center gap-2 px-4 py-2 bg-canvas border border-border rounded-lg text-sm font-medium hover:border-accent transition-colors text-muted">
                                            <Upload className="w-4 h-4" /> Choose File
                                            <input type="file" accept="image/*" onChange={handleFileChange} className="hidden" />
                                        </label>
                                        <span className="text-sm text-muted truncate">{imageFile ? imageFile.name : 'No file chosen'}</span>
                                    </div>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-foreground mb-1">Status</label>
                                    <select value={status} onChange={e => setStatus(Number(e.target.value))} className="w-full px-4 py-2.5 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent transition-colors">
                                        <option value={1}>Active</option>
                                        <option value={0}>Inactive</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div className="p-6 border-t border-border flex justify-end gap-3">
                            <button onClick={closeModal} className="px-4 py-2 border border-border rounded-lg text-sm font-medium hover:bg-canvas text-muted transition-colors">Cancel</button>
                            <button form="categoryForm" type="submit" disabled={submitting} className="px-5 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-colors disabled:opacity-60">
                                {submitting ? 'Saving...' : (modalMode === 'create' ? 'Save Category' : 'Save Changes')}
                            </button>
                        </div>
                    </div>
                </div>
            )}

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
                            <tr className="border-b border-border bg-surface text-xs text-muted uppercase tracking-wider">
                                <th className="px-6 py-4 font-medium">Image</th>
                                <th className="px-6 py-4 font-medium">Name</th>
                                <th className="px-6 py-4 font-medium">Slug</th>
                                <th className="px-6 py-4 font-medium">Status</th>
                                <th className="px-6 py-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {loading ? (
                                <tr><td colSpan={5} className="px-6 py-8 text-center text-muted">Loading categories...</td></tr>
                            ) : filteredCategories.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="px-6 py-8 text-center text-muted">
                                        <Tags className="w-8 h-8 mx-auto mb-2 opacity-50" />
                                        No categories found.
                                    </td>
                                </tr>
                            ) : (
                                filteredCategories.map((category) => (
                                    <tr key={category.id} className="hover:bg-canvas transition-colors">
                                        <td className="px-6 py-4">
                                            {category.image ? (
                                                <img src={category.image} alt={category.name} className="w-10 h-10 rounded-lg object-cover bg-canvas border border-border" />
                                            ) : (
                                                <div className="w-10 h-10 rounded-lg bg-canvas border border-border flex items-center justify-center">
                                                    <Tags className="w-4 h-4 text-muted" />
                                                </div>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 text-sm font-medium text-foreground">{category.name}</td>
                                        <td className="px-6 py-4 text-sm text-muted font-mono">{category.slug}</td>
                                        <td className="px-6 py-4 text-sm">
                                            <span className={`px-2.5 py-1 rounded-full text-xs font-medium ${category.status ? 'bg-status-in/10 text-status-in' : 'bg-muted/10 text-muted'}`}>
                                                {category.status ? 'Active' : 'Inactive'}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-sm text-right space-x-3">
                                            <button onClick={() => openEditModal(category)} className="text-muted hover:text-accent transition-colors">
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
