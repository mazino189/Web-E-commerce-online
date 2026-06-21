import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { Zap } from 'lucide-react';
import { useAuth } from '../context/AuthContext';

export default function Register() {
    const navigate = useNavigate();
    const { register } = useAuth();
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);
    const [agreedToTerms, setAgreedToTerms] = useState(false);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setError('');
        if (password !== passwordConfirmation) {
            setError('Passwords do not match');
            return;
        }
        setLoading(true);
        try {
            await register(name, email, password, passwordConfirmation);
            navigate('/');
        } catch (err: any) {
            const msg = err.data?.errors
                ? Object.values(err.data.errors).flat().join(', ')
                : err.data?.message || 'Registration failed';
            setError(msg);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-[70vh] flex items-center justify-center px-4">
            <div className="w-full max-w-sm">
                <div className="text-center mb-8">
                    <Zap className="w-8 h-8 text-cyber mx-auto mb-3" />
                    <h1 className="text-xl font-bold text-foreground">Create Account</h1>
                    <p className="text-sm text-muted mt-1">Join VOLTAIRE / TECH</p>
                </div>

                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label className="block text-xs text-muted mb-1.5">Name</label>
                        <input
                            type="text"
                            required
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                            className="w-full px-3 py-2.5 text-sm bg-surface border border-border rounded-lg text-foreground placeholder:text-muted focus:outline-none focus:border-accent transition-colors"
                            placeholder="John Doe"
                        />
                    </div>
                    <div>
                        <label className="block text-xs text-muted mb-1.5">Email</label>
                        <input
                            type="email"
                            required
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            className="w-full px-3 py-2.5 text-sm bg-surface border border-border rounded-lg text-foreground placeholder:text-muted focus:outline-none focus:border-accent transition-colors"
                            placeholder="you@example.com"
                        />
                    </div>
                    <div>
                        <label className="block text-xs text-muted mb-1.5">Password</label>
                        <input
                            type="password"
                            required
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            className="w-full px-3 py-2.5 text-sm bg-surface border border-border rounded-lg text-foreground placeholder:text-muted focus:outline-none focus:border-accent transition-colors"
                            placeholder="••••••••"
                        />
                    </div>
                    <div>
                        <label className="block text-xs text-muted mb-1.5">Confirm Password</label>
                        <input
                            type="password"
                            required
                            value={passwordConfirmation}
                            onChange={(e) => setPasswordConfirmation(e.target.value)}
                            className="w-full px-3 py-2.5 text-sm bg-surface border border-border rounded-lg text-foreground placeholder:text-muted focus:outline-none focus:border-accent transition-colors"
                            placeholder="••••••••"
                        />
                    </div>

                    {error && (
                        <p className="text-sm text-status-out bg-rose-400/10 px-3 py-2 rounded-lg">{error}</p>
                    )}

                    <div className="flex items-center gap-2">
                        <input
                            type="checkbox"
                            id="terms"
                            required
                            checked={agreedToTerms}
                            onChange={(e) => setAgreedToTerms(e.target.checked)}
                            className="w-4 h-4 rounded border-border bg-surface text-accent focus:ring-accent"
                        />
                        <label htmlFor="terms" className="text-xs text-muted">
                            I agree to the <Link to="/terms" className="text-accent hover:underline">Terms of Service</Link> and <Link to="/privacy-policy" className="text-accent hover:underline">Privacy Policy</Link>
                        </label>
                    </div>

                    <button
                        type="submit"
                        disabled={loading || !agreedToTerms}
                        className="w-full px-4 py-2.5 bg-accent text-white rounded-lg font-medium text-sm hover:bg-accent-hover disabled:opacity-50 transition-colors"
                    >
                        {loading ? 'Creating account...' : 'Create Account'}
                    </button>
                </form>

                <p className="text-center text-sm text-muted mt-6">
                    Already have an account?{' '}
                    <Link to="/login" className="text-accent hover:underline">Sign in</Link>
                </p>
            </div>
        </div>
    );
}
