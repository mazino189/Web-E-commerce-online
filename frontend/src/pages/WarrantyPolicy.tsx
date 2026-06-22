import { Shield, CheckCircle, XCircle, Clock, Phone, FileText, AlertTriangle, Wrench } from 'lucide-react';
import { Link } from 'react-router-dom';

const covered = [
    'Defective internal components (CPU, RAM, storage failures)',
    'Screen/display anomalies not caused by physical impact',
    'Battery degradation failures within the first 6 months',
    'Connectivity issues (Bluetooth, Wi-Fi, USB modules)',
    'Factory defects in build quality or finish',
    'Software failures caused by pre-installed firmware',
];

const notCovered = [
    'Accidental damage — drops, spills, cracks, or scratches',
    'Unauthorized modifications, repairs, or part replacements',
    'Normal wear and tear over time',
    'Software issues from third-party applications',
    'Water damage beyond IP rating thresholds',
    'Lost or stolen devices',
];

const claimSteps = [
    {
        step: '01',
        icon: Phone,
        title: 'Contact Support',
        desc: 'Reach out via our Contact Support page or call 1900-XXXX. Have your Order ID ready.',
    },
    {
        step: '02',
        icon: FileText,
        title: 'Submit Claim',
        desc: 'Fill out the warranty claim form with your order number, a description, and photos of the defect.',
    },
    {
        step: '03',
        icon: Wrench,
        title: 'Inspection',
        desc: 'Ship the product to our service center. We cover return shipping for valid warranty claims.',
    },
    {
        step: '04',
        icon: Shield,
        title: 'Resolution',
        desc: 'Within 7–14 business days, you receive a repaired or replaced unit at no cost.',
    },
];

export default function WarrantyPolicy() {
    return (
        <div className="min-h-screen bg-canvas py-12 px-4 sm:px-6 relative overflow-hidden">
            {/* BG effects */}
            <div className="absolute top-1/4 right-0 w-[500px] h-[500px] bg-status-in/5 rounded-full blur-[120px] pointer-events-none" />
            <div className="absolute bottom-0 left-0 w-[500px] h-[500px] bg-accent/5 rounded-full blur-[120px] pointer-events-none" />

            <div className="max-w-4xl mx-auto relative z-10">
                {/* Hero */}
                <div className="text-center mb-14">
                    <div className="w-20 h-20 bg-status-in/10 border border-status-in/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <Shield className="w-10 h-10 text-status-in" />
                    </div>
                    <h1 className="text-4xl md:text-5xl font-bold text-foreground mb-4 tracking-tight">Warranty Policy</h1>
                    <p className="text-muted text-lg">VOLTAIRE/TECH stands behind every product we sell.</p>
                    <div className="mt-6 inline-flex items-center gap-3 px-5 py-2.5 bg-status-in/10 border border-status-in/20 rounded-full">
                        <Clock className="w-4 h-4 text-status-in" />
                        <span className="text-sm font-semibold text-status-in">1-Year Standard Limited Warranty</span>
                    </div>
                </div>

                {/* Warranty tiers */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
                    {[
                        { label: '1 Year', desc: 'Full Hardware Coverage', color: 'border-status-in/30 bg-status-in/5', text: 'text-status-in' },
                        { label: '6 Months', desc: 'Battery Warranty', color: 'border-accent/30 bg-accent/5', text: 'text-accent' },
                        { label: '7 Days', desc: 'Free Return Window', color: 'border-amber-400/30 bg-amber-400/5', text: 'text-amber-400' },
                    ].map(item => (
                        <div key={item.label} className={`p-5 bg-surface border ${item.color} rounded-2xl text-center`}>
                            <div className={`text-3xl font-bold ${item.text} mb-1`}>{item.label}</div>
                            <div className="text-sm text-muted">{item.desc}</div>
                        </div>
                    ))}
                </div>

                {/* What is covered / not */}
                <div className="grid md:grid-cols-2 gap-6 mb-10">
                    <div className="p-7 bg-surface border border-border rounded-2xl">
                        <div className="flex items-center gap-3 mb-5">
                            <div className="p-2.5 bg-status-in/10 rounded-xl">
                                <CheckCircle className="w-5 h-5 text-status-in" />
                            </div>
                            <h2 className="text-lg font-semibold text-foreground">What Is Covered</h2>
                        </div>
                        <ul className="space-y-3">
                            {covered.map((item, i) => (
                                <li key={i} className="flex items-start gap-3 text-sm text-muted">
                                    <CheckCircle className="w-4 h-4 text-status-in shrink-0 mt-0.5" />
                                    {item}
                                </li>
                            ))}
                        </ul>
                    </div>

                    <div className="p-7 bg-surface border border-border rounded-2xl">
                        <div className="flex items-center gap-3 mb-5">
                            <div className="p-2.5 bg-status-out/10 rounded-xl">
                                <XCircle className="w-5 h-5 text-status-out" />
                            </div>
                            <h2 className="text-lg font-semibold text-foreground">What Is NOT Covered</h2>
                        </div>
                        <ul className="space-y-3">
                            {notCovered.map((item, i) => (
                                <li key={i} className="flex items-start gap-3 text-sm text-muted">
                                    <XCircle className="w-4 h-4 text-status-out shrink-0 mt-0.5" />
                                    {item}
                                </li>
                            ))}
                        </ul>
                    </div>
                </div>

                {/* Claim Process */}
                <div className="p-7 bg-surface border border-border rounded-2xl mb-8">
                    <h2 className="text-xl font-semibold text-foreground mb-6">How to Make a Warranty Claim</h2>
                    <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        {claimSteps.map((step, i) => {
                            const Icon = step.icon;
                            return (
                                <div key={i} className="relative">
                                    <div className="flex items-center gap-3 mb-3">
                                        <div className="text-2xl font-bold text-border">{step.step}</div>
                                        <div className="w-9 h-9 bg-accent/10 rounded-xl flex items-center justify-center">
                                            <Icon className="w-4 h-4 text-accent" />
                                        </div>
                                    </div>
                                    <h3 className="text-sm font-semibold text-foreground mb-2">{step.title}</h3>
                                    <p className="text-xs text-muted leading-relaxed">{step.desc}</p>
                                    {i < claimSteps.length - 1 && (
                                        <div className="hidden lg:block absolute top-5 left-full w-full border-t border-dashed border-border -translate-x-3" />
                                    )}
                                </div>
                            );
                        })}
                    </div>
                </div>

                {/* Warning note */}
                <div className="flex items-start gap-4 p-5 bg-amber-400/5 border border-amber-400/20 rounded-2xl mb-8">
                    <AlertTriangle className="w-5 h-5 text-amber-400 shrink-0 mt-0.5" />
                    <p className="text-sm text-muted">
                        <span className="font-semibold text-amber-400">Important:</span> Attempting to repair a device yourself or through an unauthorized service center will void the warranty. Always contact VOLTAIRE/TECH support first.
                    </p>
                </div>

                {/* CTA */}
                <div className="text-center">
                    <Link
                        to="/contact-support"
                        className="inline-flex items-center gap-2 px-6 py-3 bg-accent text-white rounded-xl font-medium text-sm hover:bg-accent-hover transition-colors"
                    >
                        <Phone className="w-4 h-4" /> Contact Support for a Claim
                    </Link>
                    <p className="text-xs text-muted mt-4">
                        Or email us at <a href="mailto:warranty@voltairetech.com" className="text-accent hover:underline">warranty@voltairetech.com</a>
                    </p>
                </div>
            </div>
        </div>
    );
}
