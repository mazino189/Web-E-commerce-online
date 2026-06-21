import { useEffect, useState } from 'react';
import api from '../apiClient';
import { Users, Search, Edit2, Trash2, X, Plus } from 'lucide-react';

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
    created_at: string;
}

export default function AdminCustomers() {
    const [customers, setCustomers] = useState<User[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editingCustomer, setEditingCustomer] = useState<User | null>(null);
    const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);

    useEffect(() => {
        fetchCustomers();
    }, []);

    const fetchCustomers = () => {
        setLoading(true);
        api.get<{ data: any }>('/admin/users')
            .then(res => {
                const data = res.data?.data || res.data || [];
                setCustomers(Array.isArray(data) ? data : []);
            })
            .finally(() => setLoading(false));
    };

    const handleDelete = async (id: number) => {
        if (!window.confirm('Are you sure you want to delete this customer? This action cannot be undone.')) return;
        try {
            await api.delete(`/admin/users/${id}`);
            setCustomers(customers.filter(c => c.id !== id));
        } catch (err: any) {
            console.error('Failed to delete customer', err);
            alert(err?.response?.data?.message || 'Failed to delete customer.');
        }
    };

    const handleOpenModal = (customer: User) => {
        setEditingCustomer(customer);
        setIsModalOpen(true);
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
        setEditingCustomer(null);
    };

    const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (!editingCustomer) return;
        
        setIsSubmitting(true);
        const formData = new FormData(e.currentTarget);
        const data = Object.fromEntries(formData.entries());

        try {
            const res = await api.put<{ data: User }>(`/admin/users/${editingCustomer.id}`, data);
            setCustomers(customers.map(c => c.id === editingCustomer.id ? res.data : c));
            handleCloseModal();
        } catch (err: any) {
            console.error('Failed to update customer', err);
            alert(err?.response?.data?.message || 'Failed to update customer.');
        } finally {
            setIsSubmitting(false);
        }
    };

    const handleCreateSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        setIsSubmitting(true);
        const formData = new FormData(e.currentTarget);
        const data = Object.fromEntries(formData.entries());

        try {
            await api.post('/admin/users', data);
            fetchCustomers();
            setIsCreateModalOpen(false);
        } catch (err: any) {
            console.error('Failed to create user', err);
            alert(err?.response?.data?.message || 'Failed to create user.');
        } finally {
            setIsSubmitting(false);
        }
    };

    const filteredCustomers = customers.filter(c => 
        c.name.toLowerCase().includes(search.toLowerCase()) || 
        c.email.toLowerCase().includes(search.toLowerCase())
    );

    return (
        <div className="space-y-6">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-foreground">Users & Admins</h1>
                    <p className="text-sm text-muted">Manage your registered users and staff.</p>
                </div>
                <button 
                    onClick={() => setIsCreateModalOpen(true)}
                    className="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-colors shadow-lg"
                >
                    <Plus className="w-4 h-4" /> Add User
                </button>
            </div>

            <div className="bg-surface border border-border rounded-xl overflow-hidden flex flex-col">
                <div className="p-4 border-b border-border flex items-center gap-4">
                    <div className="relative flex-1 max-w-md">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" />
                        <input
                            type="text"
                            placeholder="Search customers by name or email..."
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
                                <th className="px-6 py-4 font-medium">Customer</th>
                                <th className="px-6 py-4 font-medium">Role</th>
                                <th className="px-6 py-4 font-medium">Registered Date</th>
                                <th className="px-6 py-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {loading ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-8 text-center text-muted">Loading customers...</td>
                                </tr>
                            ) : filteredCustomers.length === 0 ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-8 text-center text-muted">
                                        <Users className="w-8 h-8 mx-auto mb-2 opacity-50" />
                                        No customers found.
                                    </td>
                                </tr>
                            ) : (
                                filteredCustomers.map((customer) => (
                                    <tr key={customer.id} className="hover:bg-gray-50 transition-colors">
                                        <td className="px-6 py-4 text-sm">
                                            <p className="font-medium text-foreground">{customer.name}</p>
                                            <p className="text-muted">{customer.email}</p>
                                        </td>
                                        <td className="px-6 py-4 text-sm">
                                            <span className={`inline-flex items-center px-2 py-1 rounded-md text-xs font-medium capitalize ${
                                                customer.role === 'admin' ? 'bg-purple-500/10 text-purple-500' : 'bg-blue-500/10 text-blue-500'
                                            }`}>
                                                {customer.role}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-sm text-muted">
                                            {new Date(customer.created_at).toLocaleDateString()}
                                        </td>
                                        <td className="px-6 py-4 text-sm text-right space-x-3">
                                            <button onClick={() => handleOpenModal(customer)} className="text-muted hover:text-accent transition-colors">
                                                <Edit2 className="w-4 h-4 inline" />
                                            </button>
                                            <button onClick={() => handleDelete(customer.id)} className="text-muted hover:text-rose-400 transition-colors">
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

            {/* Edit Modal */}
            {isModalOpen && editingCustomer && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                    <div className="bg-surface rounded-xl border border-border w-full max-w-md">
                        <div className="flex items-center justify-between p-6 border-b border-border">
                            <h2 className="text-xl font-semibold text-foreground">Edit Customer</h2>
                            <button onClick={handleCloseModal} className="text-muted hover:text-foreground transition-colors">
                                <X className="w-5 h-5" />
                            </button>
                        </div>

                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-foreground">Name</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    required 
                                    defaultValue={editingCustomer.name}
                                    className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                />
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-foreground">Email</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    required 
                                    defaultValue={editingCustomer.email}
                                    className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                />
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-foreground">Role</label>
                                <select 
                                    name="role" 
                                    required 
                                    defaultValue={editingCustomer.role}
                                    className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                >
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            
                            <div className="flex justify-end gap-3 pt-4 border-t border-border mt-6">
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
                                    {isSubmitting ? 'Saving...' : 'Save Changes'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
            {/* Create Modal */}
            {isCreateModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                    <div className="bg-surface rounded-xl border border-border w-full max-w-md">
                        <div className="flex items-center justify-between p-6 border-b border-border">
                            <h2 className="text-xl font-semibold text-foreground">Add New User</h2>
                            <button onClick={() => setIsCreateModalOpen(false)} className="text-muted hover:text-foreground transition-colors">
                                <X className="w-5 h-5" />
                            </button>
                        </div>

                        <form onSubmit={handleCreateSubmit} className="p-6 space-y-4">
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-foreground">Name</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    required 
                                    className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                />
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-foreground">Email</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    required 
                                    className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                />
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-foreground">Password</label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    required
                                    minLength={8}
                                    className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                />
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-foreground">Role</label>
                                <select 
                                    name="role" 
                                    required 
                                    defaultValue="user"
                                    className="w-full px-3 py-2 bg-canvas border border-border rounded-lg text-sm text-foreground focus:outline-none focus:border-accent"
                                >
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            
                            <div className="flex justify-end gap-3 pt-4 border-t border-border mt-6">
                                <button 
                                    type="button" 
                                    onClick={() => setIsCreateModalOpen(false)}
                                    className="px-4 py-2 text-sm font-medium text-muted hover:text-foreground transition-colors"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit" 
                                    disabled={isSubmitting}
                                    className="px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium hover:bg-accent-hover transition-colors disabled:opacity-50"
                                >
                                    {isSubmitting ? 'Creating...' : 'Create User'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
}
