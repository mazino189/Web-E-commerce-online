export default function WarrantyPolicy() {
    return (
        <div className="max-w-4xl mx-auto px-4 py-12">
            <h1 className="text-4xl font-bold text-foreground mb-8">Warranty Policy</h1>
            <div className="prose prose-invert prose-slate max-w-none text-muted space-y-6">
                <section>
                    <h2 className="text-2xl font-semibold text-foreground mb-4">Standard 1-Year Warranty</h2>
                    <p>All VOLTAIRE/TECH hardware products come with a 1-year limited warranty covering manufacturing defects and hardware failures under normal use.</p>
                </section>
                <section>
                    <h2 className="text-2xl font-semibold text-foreground mb-4">What is Covered</h2>
                    <ul className="list-disc pl-6 space-y-2">
                        <li>Defective internal components</li>
                        <li>Screen/display anomalies not caused by drops</li>
                        <li>Battery failures within the first 6 months</li>
                        <li>Connectivity issues (Bluetooth/Wi-Fi modules)</li>
                    </ul>
                </section>
                <section>
                    <h2 className="text-2xl font-semibold text-foreground mb-4">What is NOT Covered</h2>
                    <ul className="list-disc pl-6 space-y-2">
                        <li>Accidental damage (drops, spills, cracks)</li>
                        <li>Unauthorized modifications or repairs</li>
                        <li>Normal wear and tear</li>
                        <li>Software issues caused by third-party applications</li>
                    </ul>
                </section>
            </div>
        </div>
    );
}
