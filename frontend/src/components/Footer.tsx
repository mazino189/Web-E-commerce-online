import { Zap } from 'lucide-react';

export default function Footer() {
    return (
        <footer className="border-t border-border bg-surface/50 mt-auto">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div className="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div className="flex items-center gap-2">
                        <Zap className="w-5 h-5 text-cyber" />
                        <span className="text-sm font-semibold text-foreground">
                            VOLTAIRE<span className="text-cyber">/</span>TECH
                        </span>
                    </div>
                    <p className="text-xs text-muted">
                        &copy; {new Date().getFullYear()} Voltaire Tech. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    );
}
