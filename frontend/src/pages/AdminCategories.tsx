import { useEffect, useState } from 'react';
import api from '../apiClient';
import { Tags, Plus, Search, Edit2, Trash2, X, Upload } from 'lucide-react';

interface Category {
    id: number;
    name: string;
    slug: string;
    description: string;
    image?: string;
    status: number;
}

export default function AdminCategories() {
    const [categories, setCategories] = useState<Category[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    
    // Form state
    const [name, setName] = useState('');
    const [slug, setSlug] = useState('');
    const [description, setDescription] = useState('');
    const [imageFile, setImageFile] = useState<File | null>(null);
    const [status, setStatus] = useState(1);
    const [submitting, setSubmitting] = useState(false);

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

    const handleDelete = async (id: number) => {
        if (!window.confirm('Are you sure you want to delete this category?')) return;
        try {
            await api.delete(`/admin/categories/${id}`);
            setCategories(categories.filter(c => c.id !== id));
        } catch (err) {
            console.error('Failed to delete category', err);
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

        const formData = new FormData();
        formData.append('name', name);
        formData.append('slug', slug);
        formData.append('description', description);
        formData.append('status', status.toString());
        if (imageFile) {
            formData.append('image', imageFile);
        }

        try {
            await api.post('/admin/categories', formData);
            fetchCategories();
            setIsModalOpen(false);
            // Reset form
            setName('');
            setSlug('');
            setDescription('');
            setImageFile(null);
            setStatus(1);
        } catch (err) {
            console.error('Failed to add category', err);
            alert('Failed to add category. Check console.');
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
                    onClick={() => setIsModalOpen(true)}
                    className="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-colors shadow-lg"
                >
                    <Plus className="w-4 h-4" /> Add Category
                </button>
            </div>

            {/* Modal */}
            {isModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div className="bg-white dark:bg-slate-900 border border-border rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
                        <div className="flex items-center justify-between p-6 border-b border-border">
                            <h2 className="text-xl font-bold text-foreground">Add New Category</h2>
                            <button onClick={() => setIsModalOpen(false)} className="text-muted hover:text-foreground">
                                <X className="w-5 h-5" />
                            </button>
                        </div>
                        <div className="p-6 overflow-y-auto">
                            <form id="categoryForm" onSubmit={handleSubmit} className="space-y-5">
                                <div>
                                    <label className="block text-sm font-medium text-foreground mb-1">Name</label>
                                    <input type="text" required value={name} onChange={e => {
                                        setName(e.target.value);
                                        setSlug(e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, ''));
                                    }} className="w-full px-4 py-2 bg-canvas border border-border rounded-lg text-sm focus:outline-none focus:border-accent" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-foreground mb-1">Slug</label>
                                    <input type="text" required value={slug} onChange={e => setSlug(e.target.value)} className="w-full px-4 py-2 bg-canvas border border-border rounded-lg text-sm focus:outline-none focus:border-accent" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-foreground mb-1">Description</label>
                                    <textarea value={description} onChange={e => setDescription(e.target.value)} rows={3} className="w-full px-4 py-2 bg-canvas border border-border rounded-lg text-sm focus:outline-none focus:border-accent resize-none"></textarea>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-foreground mb-1">Image</label>
                                    <div className="flex items-center gap-4">
                                        <label className="cursor-pointer flex items-center gap-2 px-4 py-2 bg-canvas border border-border rounded-lg text-sm font-medium hover:border-accent transition-colors">
                                            <Upload className="w-4 h-4 text-muted" /> Choose File
                                            <input type="file" accept="image/*" onChange={handleFileChange} className="hidden" />
                                        </label>
                                        <span className="text-sm text-muted">{imageFile ? imageFile.name : 'No file chosen'}</span>
                                    </div>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-foreground mb-1">Status</label>
                                    <select value={status} onChange={e => setStatus(Number(e.target.value))} className="w-full px-4 py-2 bg-canvas border border-border rounded-lg text-sm focus:outline-none focus:border-accent">
                                        <option value={1}>Active</option>
                                        <option value={0}>Inactive</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div className="p-6 border-t border-border flex justify-end gap-3 bg-gray-50/50 dark:bg-slate-800/50">
                            <button onClick={() => setIsModalOpen(false)} className="px-4 py-2 border border-border rounded-lg text-sm font-medium hover:bg-canvas transition-colors">Cancel</button>
                            <button form="categoryForm" type="submit" disabled={submitting} className="px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-colors disabled:opacity-70">
                                {submitting ? 'Saving...' : 'Save Category'}
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
                            <tr className="border-b border-border bg-gray-50 text-xs text-muted uppercase tracking-wider">
                                <th className="px-6 py-4 font-medium">Image</th>
                                <th className="px-6 py-4 font-medium">Name</th>
                                <th className="px-6 py-4 font-medium">Slug</th>
                                <th className="px-6 py-4 font-medium">Status</th>
                                <th className="px-6 py-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {loading ? (
                                <tr>
                                    <td colSpan={5} className="px-6 py-8 text-center text-muted">Loading categories...</td>
                                </tr>
                            ) : filteredCategories.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="px-6 py-8 text-center text-muted">
                                        <Tags className="w-8 h-8 mx-auto mb-2 opacity-50" />
                                        No categories found.
                                    </td>
                                </tr>
                            ) : (
                                filteredCategories.map((category) => (
                                    <tr key={category.id} className="hover:bg-gray-50 transition-colors">
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
                                        <td className="px-6 py-4 text-sm text-muted">{category.slug}</td>
                                        <td className="px-6 py-4 text-sm">
                                            <span className={`px-2.5 py-1 rounded-full text-xs font-medium ${category.status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'}`}>
                                                {category.status ? 'Active' : 'Inactive'}
                                            </span>
                                        </td>
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
