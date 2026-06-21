import { useEffect, useState } from 'react';
import api from '../apiClient';
import { MessageSquare, CheckCircle, Search } from 'lucide-react';

interface ContactMessage {
    id: number;
    user_id: number | null;
    name: string;
    email: string;
    subject: string;
    message: string;
    status: 'pending' | 'resolved';
    created_at: string;
}

export default function AdminSupport() {
    const [messages, setMessages] = useState<ContactMessage[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [filterStatus, setFilterStatus] = useState<string>('');

    useEffect(() => {
        fetchMessages();
    }, [filterStatus]);

    const fetchMessages = () => {
        setLoading(true);
        const url = filterStatus ? `/admin/support?status=${filterStatus}` : '/admin/support';
        api.get<{ data: ContactMessage[] }>(url)
            .then(res => setMessages(res.data || []))
            .finally(() => setLoading(false));
    };

    const handleResolve = async (id: number) => {
        try {
            await api.put(`/admin/support/${id}/resolve`);
            setMessages(messages.map(m => m.id === id ? { ...m, status: 'resolved' } : m));
        } catch (err) {
            console.error('Failed to resolve message', err);
        }
    };

    const filteredMessages = messages.filter(m => 
        m.name.toLowerCase().includes(search.toLowerCase()) || 
        m.email.toLowerCase().includes(search.toLowerCase()) ||
        m.subject.toLowerCase().includes(search.toLowerCase())
    );

    return (
        <div className="space-y-6">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-foreground">Support Messages</h1>
                    <p className="text-sm text-muted">Manage customer queries and complaints.</p>
                </div>
            </div>

            <div className="bg-surface border border-border rounded-xl overflow-hidden flex flex-col">
                <div className="p-4 border-b border-border flex flex-col sm:flex-row items-center gap-4">
                    <div className="relative flex-1 w-full max-w-md">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" />
                        <input
                            type="text"
                            placeholder="Search by name, email or subject..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm bg-canvas border border-border rounded-lg text-foreground placeholder:text-muted focus:outline-none focus:border-accent transition-colors"
                        />
                    </div>
                    <select
                        value={filterStatus}
                        onChange={(e) => setFilterStatus(e.target.value)}
                        className="w-full sm:w-auto px-4 py-2 text-sm bg-canvas border border-border rounded-lg text-foreground focus:outline-none focus:border-accent transition-colors"
                    >
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="border-b border-border bg-gray-50 text-xs text-muted uppercase tracking-wider">
                                <th className="px-6 py-4 font-medium">Customer</th>
                                <th className="px-6 py-4 font-medium">Subject & Message</th>
                                <th className="px-6 py-4 font-medium">Status</th>
                                <th className="px-6 py-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {loading ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-8 text-center text-muted">Loading messages...</td>
                                </tr>
                            ) : filteredMessages.length === 0 ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-8 text-center text-muted">
                                        <MessageSquare className="w-8 h-8 mx-auto mb-2 opacity-50" />
                                        No messages found.
                                    </td>
                                </tr>
                            ) : (
                                filteredMessages.map((msg) => (
                                    <tr key={msg.id} className="hover:bg-gray-50 transition-colors">
                                        <td className="px-6 py-4">
                                            <div className="text-sm font-medium text-foreground">{msg.name}</div>
                                            <div className="text-xs text-muted">{msg.email}</div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="text-sm font-medium text-foreground">{msg.subject}</div>
                                            <div className="text-sm text-muted mt-1 max-w-md truncate">{msg.message}</div>
                                            <div className="text-xs text-muted mt-2">{new Date(msg.created_at).toLocaleString()}</div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className={`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                                                msg.status === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'
                                            }`}>
                                                {msg.status.toUpperCase()}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-right space-x-3">
                                            {msg.status === 'pending' && (
                                                <button 
                                                    onClick={() => handleResolve(msg.id)}
                                                    className="inline-flex items-center gap-1 text-xs font-medium text-green-600 hover:text-green-700 transition-colors"
                                                >
                                                    <CheckCircle className="w-4 h-4" /> Resolve
                                                </button>
                                            )}
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
