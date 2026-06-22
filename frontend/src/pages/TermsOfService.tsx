import { Shield, User, CreditCard, Cpu, ArrowLeft, Package, RefreshCw, Lock } from 'lucide-react';
import { Link } from 'react-router-dom';

const sections = [
    {
        icon: Shield,
        color: 'text-accent',
        bg: 'bg-accent/10',
        title: '1. Acceptance of Terms',
        content: `By accessing the VOLTAIRE/TECH eCommerce platform, browsing our products, or placing any order, you confirm that you have read, understood, and agree to be bound by these Terms of Service. If you do not agree to any part of these terms, you must not use the platform.

By registering an account, you explicitly accept these terms and consent to our data processing practices as described in our Privacy Policy.`,
    },
    {
        icon: User,
        color: 'text-purple-400',
        bg: 'bg-purple-500/10',
        title: '2. Account Registration',
        content: `To place an order or access personalized features, you must create an account with a valid email address and a secure password.

You are solely responsible for:
• Maintaining the confidentiality of your login credentials
• All activities that occur under your account
• Immediately notifying us of any unauthorized account access

VOLTAIRE/TECH reserves the right to terminate accounts that violate these terms or engage in fraudulent activity.`,
    },
    {
        icon: CreditCard,
        color: 'text-status-in',
        bg: 'bg-status-in/10',
        title: '3. Payment & Transactions',
        content: `All transactions on VOLTAIRE/TECH are processed securely. We accept:
• Cash on Delivery (COD)
• MoMo mobile wallet
• VNPay bank transfer
• Credit/Debit Cards (Visa, Mastercard)

All prices are displayed in Vietnamese Đồng (₫). By completing a purchase, you confirm that you are authorized to use the selected payment method. VOLTAIRE/TECH reserves the right to cancel orders flagged by our fraud-prevention systems.`,
    },
    {
        icon: Package,
        color: 'text-amber-400',
        bg: 'bg-amber-400/10',
        title: '4. Orders & Shipping',
        content: `Orders are processed within 1–2 business days. Shipping timelines:
• Standard delivery: 3–5 working days
• Express delivery: 1–2 working days

Once an order is placed, it may only be cancelled while its status is "Pending". We are not liable for delays caused by courier partners or force majeure events.`,
    },
    {
        icon: RefreshCw,
        color: 'text-sky-400',
        bg: 'bg-sky-400/10',
        title: '5. Returns & Refunds',
        content: `We offer a 7-day return window from the date of delivery. To be eligible for a return:
• The item must be in its original, unused condition
• Original packaging and all accessories must be included
• Proof of purchase is required

Refunds are processed within 3–5 business days after we receive and inspect the returned item. Items marked "Final Sale" are non-returnable.`,
    },
    {
        icon: Shield,
        color: 'text-status-in',
        bg: 'bg-status-in/10',
        title: '6. Warranty',
        content: `All VOLTAIRE/TECH products include a 1-year limited warranty covering manufacturing defects. The warranty does not cover:
• Accidental damage (drops, spills, cracks)
• Unauthorized modifications or repairs
• Normal wear and tear
• Damage caused by third-party software

To make a warranty claim, contact our support team with your order number and a description of the defect.`,
    },
    {
        icon: Cpu,
        color: 'text-accent',
        bg: 'bg-accent/10',
        title: '7. Intellectual Property',
        content: `All content on this platform — including but not limited to source code, product designs, branding, images, and descriptions — is the exclusive intellectual property of VOLTAIRE/TECH. You may not reproduce, copy, distribute, or reverse-engineer any materials without explicit written consent.`,
    },
    {
        icon: Lock,
        color: 'text-purple-400',
        bg: 'bg-purple-500/10',
        title: '8. Privacy & Data',
        content: `We collect and process your personal data in accordance with our Privacy Policy. By using our platform, you consent to:
• Collection of name, email, address, and payment metadata
• Use of this data to fulfill orders and improve our services
• Storage of data on secure servers

We do not sell your personal information to third parties.`,
    },
];

export default function TermsOfService() {
    return (
        <div className="min-h-screen bg-canvas py-12 px-4 sm:px-6 relative overflow-hidden">
            {/* Background effects */}
            <div className="absolute top-0 left-1/4 w-[600px] h-[600px] bg-accent/5 rounded-full blur-[120px] pointer-events-none" />
            <div className="absolute bottom-0 right-1/4 w-[600px] h-[600px] bg-purple-500/5 rounded-full blur-[120px] pointer-events-none" />

            <div className="max-w-4xl mx-auto relative z-10">
                <Link to="/home" className="inline-flex items-center gap-2 text-sm text-accent hover:text-cyan-300 transition-colors mb-8">
                    <ArrowLeft className="w-4 h-4" /> Back to Home
                </Link>

                <div className="text-center mb-12">
                    <div className="w-16 h-16 bg-accent/10 border border-accent/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <Shield className="w-8 h-8 text-accent" />
                    </div>
                    <h1 className="text-4xl md:text-5xl font-bold text-foreground mb-4 tracking-tight">Terms of Service</h1>
                    <p className="text-muted text-lg">VOLTAIRE / TECH — Platform Usage Agreement</p>
                    <p className="text-sm text-muted/60 mt-2">Last updated: June 2025</p>
                </div>

                <div className="space-y-5">
                    {sections.map((section, i) => {
                        const Icon = section.icon;
                        return (
                            <div key={i} className="p-7 bg-surface border border-border rounded-2xl shadow-xl shadow-black/10 hover:border-accent/30 transition-colors">
                                <div className="flex items-center gap-4 mb-4">
                                    <div className={`p-3 ${section.bg} rounded-xl`}>
                                        <Icon className={`w-6 h-6 ${section.color}`} />
                                    </div>
                                    <h2 className="text-xl font-semibold text-foreground">{section.title}</h2>
                                </div>
                                <div className="text-muted leading-relaxed text-sm whitespace-pre-line">
                                    {section.content}
                                </div>
                            </div>
                        );
                    })}
                </div>

                <div className="mt-10 p-6 bg-accent/5 border border-accent/20 rounded-2xl text-center">
                    <p className="text-sm text-muted">
                        By using VOLTAIRE/TECH you acknowledge that you have read and agree to these Terms of Service.
                        Questions? Contact us at <a href="mailto:legal@voltairetech.com" className="text-accent hover:underline">legal@voltairetech.com</a>
                    </p>
                </div>
            </div>
        </div>
    );
}
