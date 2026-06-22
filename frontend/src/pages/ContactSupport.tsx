import { useState } from 'react';
import { Mail, Phone, MapPin, Send, CheckCircle2, AlertCircle } from 'lucide-react';
import api from '../apiClient';
import { useAuth } from '../context/AuthContext';

export default function ContactSupport() {
    const { user } = useAuth();
    const [status, setStatus] = useState<'idle' | 'submitting' | 'success'>('idle');
    const [error, setError] = useState('');

    const [name, setName] = useState(user?.name || '');
    const [email, setEmail] = useState(user?.email || '');
    const [subject, setSubject] = useState('');
    const [message, setMessage] = useState('');

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setError('');
        setStatus('submitting');

        try {
            await api.post('/support/contact', { name, email, subject, message });
            setStatus('success');
        } catch (err: any) {
            setError(err.data?.message || 'Failed to send message. Please try again.');
            setStatus('idle');
        }
    };

    return (
        <div className="min-h-[80vh] flex items-center py-12 px-4 sm:px-6">
            <div className="max-w-6xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                
                {/* Information Section */}
                <div className="space-y-8">
                    <div>
                        <h1 className="text-3xl font-bold text-foreground tracking-tight sm:text-4xl">
                            Contact Support
                        </h1>
                        <p className="mt-4 text-lg text-muted">
                            Need help with your order or have a question? Our support team is here to help.
                        </p>
                    </div>

                    <div className="space-y-4">
                        <div className="flex items-start gap-4 p-4 rounded-2xl bg-surface border border-border">
                            <div className="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center shrink-0">
                                <Mail className="w-6 h-6 text-accent" />
                            </div>
                            <div>
                                <h3 className="text-sm font-semibold text-foreground">Email Support</h3>
                                <p className="text-sm text-muted mt-1">Our team typically responds within 24 hours.</p>
                                <a href="mailto:support@voltairetech.com" className="text-sm font-medium text-accent hover:text-cyan-300 mt-2 inline-block transition-colors">
                                    support@voltairetech.com
                                </a>
                            </div>
                        </div>

                        <div className="flex items-start gap-4 p-4 rounded-2xl bg-surface border border-border">
                            <div className="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center shrink-0">
                                <Phone className="w-6 h-6 text-accent" />
                            </div>
                            <div>
                                <h3 className="text-sm font-semibold text-foreground">Phone Support</h3>
                                <p className="text-sm text-muted mt-1">Available Mon–Fri, 9am – 6pm (GMT+7).</p>
                                <p className="text-sm font-medium text-foreground mt-2">1900-XXXX</p>
                            </div>
                        </div>

                        <div className="flex items-start gap-4 p-4 rounded-2xl bg-surface border border-border">
                            <div className="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center shrink-0">
                                <MapPin className="w-6 h-6 text-accent" />
                            </div>
                            <div>
                                <h3 className="text-sm font-semibold text-foreground">Office Location</h3>
                                <p className="text-sm text-muted mt-1">123 Tech Avenue, District 1<br/>Ho Chi Minh City, Vietnam</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Form Section */}
                <div className="bg-surface border border-border rounded-3xl p-8 shadow-sm relative overflow-hidden">
                    <div className="absolute top-0 right-0 w-64 h-64 bg-accent/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none" />
                    
                    {status === 'success' ? (
                        <div className="h-full flex flex-col items-center justify-center py-12 text-center relative z-10">
                            <div className="w-16 h-16 bg-status-in/10 rounded-full flex items-center justify-center mb-6">
                                <CheckCircle2 className="w-8 h-8 text-status-in" />
                            </div>
                            <h3 className="text-2xl font-bold text-foreground mb-2">Message Sent!</h3>
                            <p className="text-muted mb-8">We've received your message and will respond within 24 hours.</p>
                            <button 
                                onClick={() => { setStatus('idle'); setSubject(''); setMessage(''); }}
                                className="px-6 py-2.5 bg-canvas border border-border rounded-xl text-sm font-medium hover:border-accent transition-colors text-foreground"
                            >
                                Send Another Message
                            </button>
                        </div>
                    ) : (
                        <form onSubmit={handleSubmit} className="space-y-5 relative z-10">
                            <h2 className="text-xl font-bold text-foreground">Send us a message</h2>
                            
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div className="space-y-1.5">
                                    <label className="text-xs font-medium text-muted">Your Name</label>
                                    <input 
                                        type="text" 
                                        required
                                        value={name}
                                        onChange={e => setName(e.target.value)}
                                        className="w-full px-4 py-3 bg-canvas border border-border rounded-xl text-sm text-foreground focus:outline-none focus:border-accent transition-colors placeholder:text-muted/50" 
                                        placeholder="John Doe"
                                    />
                                </div>
                                <div className="space-y-1.5">
                                    <label className="text-xs font-medium text-muted">Email Address</label>
                                    <input 
                                        type="email" 
                                        required
                                        value={email}
                                        onChange={e => setEmail(e.target.value)}
                                        className="w-full px-4 py-3 bg-canvas border border-border rounded-xl text-sm text-foreground focus:outline-none focus:border-accent transition-colors placeholder:text-muted/50"
                                        placeholder="john@example.com"
                                    />
                                </div>
                            </div>

                            <div className="space-y-1.5">
                                <label className="text-xs font-medium text-muted">Subject</label>
                                <input 
                                    type="text" 
                                    required
                                    value={subject}
                                    onChange={e => setSubject(e.target.value)}
                                    className="w-full px-4 py-3 bg-canvas border border-border rounded-xl text-sm text-foreground focus:outline-none focus:border-accent transition-colors placeholder:text-muted/50"
                                    placeholder="Order issue / Product inquiry / Other"
                                />
                            </div>

                            <div className="space-y-1.5">
                                <label className="text-xs font-medium text-muted">Message</label>
                                <textarea 
                                    required
                                    rows={5}
                                    value={message}
                                    onChange={e => setMessage(e.target.value)}
                                    className="w-full px-4 py-3 bg-canvas border border-border rounded-xl text-sm text-foreground focus:outline-none focus:border-accent transition-colors resize-none placeholder:text-muted/50" 
                                    placeholder="Describe your issue or question in detail..."
                                />
                            </div>

                            {error && (
                                <div className="flex items-center gap-2 px-4 py-3 rounded-xl bg-status-out/10 border border-status-out/20">
                                    <AlertCircle className="w-4 h-4 text-status-out shrink-0" />
                                    <p className="text-sm text-status-out">{error}</p>
                                </div>
                            )}

                            <button 
                                type="submit"
                                disabled={status === 'submitting'}
                                className="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-accent text-white rounded-xl text-sm font-medium hover:bg-accent-hover transition-colors disabled:opacity-70"
                            >
                                {status === 'submitting' ? (
                                    <><span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />Sending...</>
                                ) : (
                                    <>Send Message <Send className="w-4 h-4" /></>
                                )}
                            </button>
                        </form>
                    )}
                </div>
            </div>
        </div>
    );
}
