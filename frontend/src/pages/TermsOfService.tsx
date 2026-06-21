import { Shield, User, CreditCard, Cpu, ArrowLeft } from 'lucide-react';
import { Link } from 'react-router-dom';

export default function TermsOfService() {
    return (
        <div className="min-h-screen bg-canvas py-16 px-4 sm:px-6 relative overflow-hidden">
            {/* Cyber effects */}
            <div className="absolute top-0 left-1/4 w-[600px] h-[600px] bg-indigo-500/5 rounded-full blur-[120px] pointer-events-none" />
            <div className="absolute bottom-0 right-1/4 w-[600px] h-[600px] bg-cyan-500/5 rounded-full blur-[120px] pointer-events-none" />

            <div className="max-w-4xl mx-auto relative z-10">
                <Link to="/" className="inline-flex items-center gap-2 text-sm text-indigo-400 hover:text-indigo-300 transition-colors mb-8">
                    <ArrowLeft className="w-4 h-4" /> Back to Home
                </Link>

                <div className="text-center mb-12">
                    <Shield className="w-16 h-16 text-indigo-500 mx-auto mb-6" />
                    <h1 className="text-4xl md:text-5xl font-bold text-foreground mb-4 tracking-tight">Terms of Service</h1>
                    <p className="text-muted text-lg">VOLTAIRE / TECH API Contract & Checkout Guidelines</p>
                </div>

                <div className="space-y-8">
                    {/* Section 1 */}
                    <div className="p-8 bg-surface border border-border rounded-2xl shadow-xl shadow-black/5">
                        <div className="flex items-center gap-4 mb-4">
                            <div className="p-3 bg-indigo-500/10 rounded-xl">
                                <Shield className="w-6 h-6 text-indigo-500" />
                            </div>
                            <h2 className="text-2xl font-semibold text-foreground">1. Acceptance of Terms</h2>
                        </div>
                        <div className="prose prose-slate prose-invert max-w-none text-muted leading-relaxed">
                            <p>By accessing the VOLTAIRE/TECH eCommerce platform and utilizing our API services or checkout endpoints, you accept and agree to be strictly bound by the terms and provisions of this agreement. Our platform operates on a robust tech stack, and any interaction constitutes legally binding acceptance of our guidelines.</p>
                        </div>
                    </div>

                    {/* Section 2 */}
                    <div className="p-8 bg-surface border border-border rounded-2xl shadow-xl shadow-black/5">
                        <div className="flex items-center gap-4 mb-4">
                            <div className="p-3 bg-indigo-500/10 rounded-xl">
                                <User className="w-6 h-6 text-indigo-500" />
                            </div>
                            <h2 className="text-2xl font-semibold text-foreground">2. User Registration Requirements</h2>
                        </div>
                        <div className="prose prose-slate prose-invert max-w-none text-muted leading-relaxed">
                            <p>To finalize an order, users must register for an account using a valid email and secure password. You are solely responsible for safeguarding your credentials and maintaining the confidentiality of your session tokens. Any unauthorized account access must be reported immediately to our support team.</p>
                        </div>
                    </div>

                    {/* Section 3 */}
                    <div className="p-8 bg-surface border border-border rounded-2xl shadow-xl shadow-black/5">
                        <div className="flex items-center gap-4 mb-4">
                            <div className="p-3 bg-indigo-500/10 rounded-xl">
                                <CreditCard className="w-6 h-6 text-indigo-500" />
                            </div>
                            <h2 className="text-2xl font-semibold text-foreground">3. Payment & Transaction Terms</h2>
                        </div>
                        <div className="prose prose-slate prose-invert max-w-none text-muted leading-relaxed">
                            <p>All financial transactions processed through our `/orders` and checkout alignment endpoints are secure and final. We accept Cash on Delivery (COD), VNPay, MoMo, and major Credit Cards. VOLTAIRE/TECH reserves the right to cancel or refund any suspicious orders flagged by our fraud-prevention algorithms.</p>
                        </div>
                    </div>

                    {/* Section 4 */}
                    <div className="p-8 bg-surface border border-border rounded-2xl shadow-xl shadow-black/5">
                        <div className="flex items-center gap-4 mb-4">
                            <div className="p-3 bg-indigo-500/10 rounded-xl">
                                <Cpu className="w-6 h-6 text-indigo-500" />
                            </div>
                            <h2 className="text-2xl font-semibold text-foreground">4. Intellectual Property</h2>
                        </div>
                        <div className="prose prose-slate prose-invert max-w-none text-muted leading-relaxed">
                            <p>All source code, platform designs, hardware branding, and software implementations associated with VOLTAIRE/TECH are the exclusive intellectual property of the company. Users may not scrape, copy, or redistribute any materials or APIs without explicit written consent.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
