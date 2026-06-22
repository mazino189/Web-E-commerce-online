import { useEffect, useState } from 'react';
import api from '../apiClient';
import { MessageSquare, CheckCircle, Search, X, Mail, User, Clock, Tag, Reply } from 'lucide-react';

interface ContactMessage {
    id: number;
    user_id: number | null;
    name: string;
    email: string;
    subject: string;
    message: string;
    status: 'pending' | 'resolved';
    admin_reply?: string;
    created_at: string;
    updated_at: string;
}

interface Paginated<T> {
    data: T[];
    current_page: number;
    last_page: number;
    total: number;
}

export default function AdminSupport() {
    const [messages, setMessages] = useState<ContactMessage[]>([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [filterStatus, setFilterStatus] = useState<string>('');
    const [selectedMsg, setSelectedMsg] = useState<ContactMessage | null>(null);
    const [replyText, setReplyText] = useState('');
    const [resolving, setResolving] = useState(false);
    const [stats, setStats] = useState({ total: 0, pending: 0, resolved: 0 });

    useEffect(() => {
        fetchMessages();
    }, [filterStatus]);

    const fetchMessages = () => {
        setLoading(true);
        const url = filterStatus ? `/admin/support?status=${filterStatus}` : '/admin/support';
        api.get<Paginated<ContactMessage>>(url)
            .then(res => {
                const data = res.data || [];
                setMessages(data);
                setStats({
                    total: data.length,
                    pending: data.filter(m => m.status === 'pending').length,
                    resolved: data.filter(m => m.status === 'resolved').length,
                });
            })
            .finally(() => setLoading(false));
    };

    const handleResolve = async (id: number, reply?: string) => {
        setResolving(true);
        try {
            await api.put(`/admin/support/${id}/resolve`, { admin_reply: reply || '' });
            setMessages(msgs => msgs.map(m => m.id === id ? { ...m, status: 'resolved', admin_reply: reply } : m));
            if (selectedMsg?.id === id) {
                setSelectedMsg(prev => prev ? { ...prev, status: 'resolved', admin_reply: reply } : null);
            }
        } catch (err) {
            console.error('Failed to resolve message', err);
        } finally {
            setResolving(false);
        }
    };

    const filteredMessages = messages.filter(m =>
        m.name.toLowerCase().includes(search.toLowerCase()) ||
        m.email.toLowerCase().includes(search.toLowerCase()) ||
        m.subject.toLowerCase().includes(search.toLowerCase())
    );

    const openDetail = (msg: ContactMessage) => {
        setSelectedMsg(msg);
        setReplyText(msg.admin_reply || '');
    };

    return (
        <div className="space-y-6">
            {/* Header */}
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-foreground">Support Messages</h1>
                    <p className="text-sm text-muted">Manage customer queries and complaints.</p>
                </div>
            </div>

            {/* Stats Row */}
            <div className="grid grid-cols-3 gap-4">
                {[
                    { label: 'Total', value: stats.total, color: 'text-foreground' },
                    { label: 'Pending', value: stats.pending, color: 'text-amber-400' },
                    { label: 'Resolved', value: stats.resolved, color: 'text-status-in' },
                ].map(s => (
                    <div key={s.label} className="bg-surface border border-border rounded-xl p-4 text-center">
                        <div className={`text-2xl font-bold ${s.color}`}>{s.value}</div>
                        <div className="text-xs text-muted mt-1">{s.label}</div>
                    </div>
                ))}
            </div>

            {/* Table */}
            <div className="bg-surface border border-border rounded-xl overflow-hidden">
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
                            <tr className="border-b border-border bg-surface text-xs text-muted uppercase tracking-wider">
                                <th className="px-6 py-4 font-medium">Customer</th>
                                <th className="px-6 py-4 font-medium">Subject</th>
                                <th className="px-6 py-4 font-medium">Status</th>
                                <th className="px-6 py-4 font-medium">Date</th>
                                <th className="px-6 py-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {loading ? (
                                <tr><td colSpan={5} className="px-6 py-8 text-center text-muted">Loading messages...</td></tr>
                            ) : filteredMessages.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="px-6 py-8 text-center text-muted">
                                        <MessageSquare className="w-8 h-8 mx-auto mb-2 opacity-50" />
                                        No messages found.
                                    </td>
                                </tr>
                            ) : (
                                filteredMessages.map((msg) => (
                                    <tr key={msg.id} className="hover:bg-canvas transition-colors cursor-pointer" onClick={() => openDetail(msg)}>
                                        <td className="px-6 py-4">
                                            <div className="text-sm font-medium text-foreground">{msg.name}</div>
                                            <div className="text-xs text-muted">{msg.email}</div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="text-sm font-medium text-foreground">{msg.subject}</div>
                                            <div className="text-xs text-muted max-w-xs truncate">{msg.message}</div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className={`inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${
                                                msg.status === 'resolved'
                                                    ? 'bg-status-in/10 text-status-in'
                                                    : 'bg-amber-400/10 text-amber-400'
                                            }`}>
                                                {msg.status === 'resolved' ? '✓ Resolved' : '⏳ Pending'}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-xs text-muted whitespace-nowrap">
                                            {new Date(msg.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                        </td>
                                        <td className="px-6 py-4 text-right" onClick={e => e.stopPropagation()}>
                                            {msg.status === 'pending' && (
                                                <button
                                                    onClick={() => handleResolve(msg.id)}
                                                    className="inline-flex items-center gap-1 text-xs font-medium text-status-in hover:text-status-in/80 transition-colors"
                                                >
                                                    <CheckCircle className="w-4 h-4" /> Quick Resolve
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

            {/* Detail Modal */}
            {selectedMsg && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                    <div className="bg-surface border border-border rounded-2xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[90vh]">
                        {/* Modal Header */}
                        <div className="p-6 border-b border-border flex items-start justify-between gap-4">
                            <div className="flex-1 min-w-0">
                                <div className="flex items-center gap-3 flex-wrap">
                                    <h2 className="text-lg font-bold text-foreground">{selectedMsg.subject}</h2>
                                    <span className={`px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                        selectedMsg.status === 'resolved' ? 'bg-status-in/10 text-status-in' : 'bg-amber-400/10 text-amber-400'
                                    }`}>
                                        {selectedMsg.status === 'resolved' ? '✓ Resolved' : '⏳ Pending'}
                                    </span>
                                </div>
                            </div>
                            <button onClick={() => setSelectedMsg(null)} className="text-muted hover:text-foreground transition-colors shrink-0">
                                <X className="w-5 h-5" />
                            </button>
                        </div>

                        <div className="p-6 overflow-y-auto space-y-6 flex-1">
                            {/* Contact Info */}
                            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div className="flex items-center gap-3 p-3 bg-canvas rounded-xl border border-border">
                                    <User className="w-4 h-4 text-accent shrink-0" />
                                    <div className="min-w-0">
                                        <div className="text-xs text-muted">Customer</div>
                                        <div className="text-sm font-medium text-foreground truncate">{selectedMsg.name}</div>
                                    </div>
                                </div>
                                <div className="flex items-center gap-3 p-3 bg-canvas rounded-xl border border-border">
                                    <Mail className="w-4 h-4 text-accent shrink-0" />
                                    <div className="min-w-0">
                                        <div className="text-xs text-muted">Email</div>
                                        <a href={`mailto:${selectedMsg.email}`} className="text-sm font-medium text-accent hover:underline truncate block">{selectedMsg.email}</a>
                                    </div>
                                </div>
                                <div className="flex items-center gap-3 p-3 bg-canvas rounded-xl border border-border">
                                    <Clock className="w-4 h-4 text-accent shrink-0" />
                                    <div className="min-w-0">
                                        <div className="text-xs text-muted">Received</div>
                                        <div className="text-sm font-medium text-foreground">{new Date(selectedMsg.created_at).toLocaleString()}</div>
                                    </div>
                                </div>
                            </div>

                            {/* Message */}
                            <div>
                                <div className="flex items-center gap-2 mb-3">
                                    <Tag className="w-4 h-4 text-muted" />
                                    <span className="text-sm font-semibold text-foreground">Customer Message</span>
                                </div>
                                <div className="p-4 bg-canvas border border-border rounded-xl text-sm text-foreground leading-relaxed whitespace-pre-wrap">
                                    {selectedMsg.message}
                                </div>
                            </div>

                            {/* Existing reply */}
                            {selectedMsg.admin_reply && (
                                <div>
                                    <div className="flex items-center gap-2 mb-3">
                                        <Reply className="w-4 h-4 text-status-in" />
                                        <span className="text-sm font-semibold text-status-in">Admin Reply</span>
                                    </div>
                                    <div className="p-4 bg-status-in/5 border border-status-in/20 rounded-xl text-sm text-foreground leading-relaxed whitespace-pre-wrap">
                                        {selectedMsg.admin_reply}
                                    </div>
                                </div>
                            )}

                            {/* Reply & Resolve */}
                            {selectedMsg.status === 'pending' && (
                                <div>
                                    <div className="flex items-center gap-2 mb-3">
                                        <Reply className="w-4 h-4 text-muted" />
                                        <span className="text-sm font-semibold text-foreground">Reply & Resolve</span>
                                    </div>
                                    <textarea
                                        value={replyText}
                                        onChange={e => setReplyText(e.target.value)}
                                        rows={4}
                                        placeholder="Write an admin note or reply for this message (optional)..."
                                        className="w-full px-4 py-3 bg-canvas border border-border rounded-xl text-sm text-foreground focus:outline-none focus:border-accent transition-colors resize-none placeholder:text-muted/50"
                                    />
                                </div>
                            )}
                        </div>

                        {/* Footer Actions */}
                        <div className="p-6 border-t border-border flex justify-end gap-3">
                            <button onClick={() => setSelectedMsg(null)} className="px-4 py-2 text-sm font-medium text-muted hover:text-foreground transition-colors">
                                Close
                            </button>
                            {selectedMsg.status === 'pending' && (
                                <button
                                    onClick={() => handleResolve(selectedMsg.id, replyText)}
                                    disabled={resolving}
                                    className="flex items-center gap-2 px-5 py-2 bg-status-in text-white rounded-xl text-sm font-medium hover:bg-status-in/80 transition-colors disabled:opacity-60"
                                >
                                    <CheckCircle className="w-4 h-4" />
                                    {resolving ? 'Resolving...' : 'Mark as Resolved'}
                                </button>
                            )}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
