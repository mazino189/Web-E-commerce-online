import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { ArrowRight } from 'lucide-react';
import { useAuth } from '../context/AuthContext';
import logoImg from '../assets/logo.png';

export default function Intro() {
    const navigate = useNavigate();
    const { user, loading } = useAuth();
    const [text, setText] = useState('');
    const fullText = "Engineered for a quieter future.";
    const [isComplete, setIsComplete] = useState(false);

    // If user is already logged in, skip the intro and go straight to home
    useEffect(() => {
        if (!loading && user) {
            navigate('/home', { replace: true });
        }
    }, [user, loading, navigate]);

    useEffect(() => {
        // Don't start the animation until auth check is done and user is NOT logged in
        if (loading || user) return;
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
    }, [loading, user]);

    // Show nothing while checking auth to avoid flicker
    if (loading) return null;
    if (user) return null;

    return (
        <div className="min-h-screen bg-canvas flex flex-col items-center justify-center p-4 relative overflow-hidden">
            {/* Cyber background effects */}
            <div className="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(0,255,255,0.05)_0%,transparent_50%)]" />
            <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-[100px]" />
            <div className="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-[100px]" />

            <div className="relative z-10 flex flex-col items-center max-w-2xl text-center">
                {/* Logo */}
                <div className="mb-8">
                    <img
                        src={logoImg}
                        alt="VOLTAIRE/TECH"
                        className="h-20 md:h-28 w-auto object-contain drop-shadow-[0_0_30px_rgba(0,229,255,0.4)] animate-pulse"
                    />
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
