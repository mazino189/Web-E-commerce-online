import React from 'react';

const PrivacyPolicy: React.FC = () => {
    return (
        <div className="max-w-4xl mx-auto py-16 px-6">
            <h1 className="text-4xl font-light text-slate-100 mb-10 tracking-wide border-b border-slate-800 pb-6">Privacy Policy</h1>
            
            <div className="space-y-8 text-slate-400 font-light leading-relaxed">
                <section>
                    <h2 className="text-xl font-medium text-slate-200 mb-3">1. Information We Collect</h2>
                    <p>We collect information you provide directly to us, such as when you create or modify your account, request on-demand services, contact customer support, or otherwise communicate with us. This information may include: name, email, phone number, postal address, profile picture, payment method, and other information you choose to provide.</p>
                </section>
                
                <section>
                    <h2 className="text-xl font-medium text-slate-200 mb-3">2. How We Use Your Information</h2>
                    <p>We may use the information we collect about you to provide, maintain, and improve our services, including to facilitate payments, send receipts, provide products and services you request, develop new features, provide customer support to Users and Drivers, develop safety features, authenticate users, and send product updates and administrative messages.</p>
                </section>
                
                <section>
                    <h2 className="text-xl font-medium text-slate-200 mb-3">3. Sharing of Information</h2>
                    <p>We may share the information we collect about you as described in this Statement or as described at the time of collection or sharing, including with third party service providers who need access to such information to carry out work on our behalf.</p>
                </section>
            </div>
        </div>
    );
};

export default PrivacyPolicy;
