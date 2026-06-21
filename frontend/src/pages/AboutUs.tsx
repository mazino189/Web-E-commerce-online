import React from 'react';

const AboutUs: React.FC = () => {
    return (
        <div className="max-w-5xl mx-auto py-16 px-6">
            <div className="text-center mb-16">
                <h1 className="text-5xl font-light text-slate-100 mb-6 tracking-wide">About VOLTAIRE/TECH</h1>
                <div className="w-24 h-1 bg-cyan-600 mx-auto rounded"></div>
            </div>

            <div className="space-y-12 text-slate-300 leading-relaxed text-lg font-light">
                <section>
                    <h2 className="text-2xl font-medium text-slate-100 mb-4">Our Origin</h2>
                    <p>
                        Established in 2026, VOLTAIRE/TECH was born from a singular vision: to bridge the gap between precision engineering and minimalist aesthetics. We recognized that the technology we use daily shouldn't just be functional—it should be an extension of our design philosophy.
                    </p>
                </section>

                <section>
                    <h2 className="text-2xl font-medium text-slate-100 mb-4">Precision-Built Electronics</h2>
                    <p>
                        Every product in our catalog undergoes rigorous curation. We partner with top-tier manufacturers who share our obsession with detail. From the tactile feedback of a mechanical switch to the thermal efficiency of a laptop chassis, we believe that true quality is felt, not just seen.
                    </p>
                </section>

                <section>
                    <h2 className="text-2xl font-medium text-slate-100 mb-4">Sustainable Engineering</h2>
                    <p>
                        The future of technology must be sustainable. VOLTAIRE/TECH is committed to eco-friendly packaging, carbon-neutral shipping, and promoting devices with high repairability scores. Our glassmorphism digital aesthetics reflect our commitment to transparency—both in design and in our business practices.
                    </p>
                </section>
            </div>
        </div>
    );
};

export default AboutUs;
