import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { ArrowRight, Zap } from 'lucide-react';

export default function Intro() {
    const navigate = useNavigate();
    const [text, setText] = useState('');
    const fullText = "Engineered for a quieter future.";
    const [isComplete, setIsComplete] = useState(false);

    useEffect(() => {
        let i = 0;
        const timer = setInterval(() => {
            setText(fullText.substring(0, i));
            i++;
            if (i > fullText.length) {
                clearInterval(timer);
                setTimeout(() => setIsComplete(true), 500);
            }
        }, 100);
        return () => clearInterval(timer);
    }, []);

    return (
        <div className="min-h-screen bg-canvas flex flex-col items-center justify-center p-4 relative overflow-hidden">
            {/* Cyber background effects */}
            <div className="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(0,255,255,0.05)_0%,transparent_50%)]" />
            <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-[100px]" />
            <div className="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-[100px]" />

            <div className="relative z-10 flex flex-col items-center max-w-2xl text-center">
                <div className="flex items-center gap-3 mb-8">
                    <Zap className="w-12 h-12 text-accent animate-pulse" />
                    <h1 className="text-5xl md:text-7xl font-bold text-foreground tracking-tighter">
                        VOLTAIRE<span className="text-accent">/</span>TECH
                    </h1>
                </div>
                
                <div className="h-12 mb-12">
                    <p className="text-lg md:text-2xl text-muted font-light tracking-wide font-mono">
                        {text}<span className="animate-ping">_</span>
                    </p>
                </div>

                <div className={`transition-all duration-1000 transform ${isComplete ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'}`}>
                    <button
                        onClick={() => navigate('/home')}
                        className="group relative px-8 py-4 bg-accent text-white font-medium text-lg rounded-xl overflow-hidden hover:scale-105 transition-all duration-300 shadow-[0_0_20px_rgba(0,255,255,0.3)]"
                    >
                        <div className="absolute inset-0 w-full h-full bg-white/20 -translate-x-full group-hover:translate-x-full transition-transform duration-500" />
                        <span className="flex items-center gap-2">
                            Enter Store <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                        </span>
                    </button>
                </div>
            </div>
        </div>
    );
}
