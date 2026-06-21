import { Link } from 'react-router-dom';
import { Zap, Mail } from 'lucide-react';

export default function Footer() {
    return (
        <footer className="bg-[#020617] border-t border-slate-800 text-slate-400 py-16">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                    {/* Brand Story */}
                    <div className="space-y-6">
                        <Link to="/" className="flex items-center gap-2 group">
                            <Zap className="w-6 h-6 text-cyan-400" />
                            <span className="text-xl font-bold text-slate-100 tracking-tight font-mono">
                                VOLTAIRE<span className="text-cyan-400">/</span>TECH
                            </span>
                        </Link>
                        <p className="text-sm leading-relaxed font-light">
                            Precision-built electronics and minimalist aesthetics. We bridge the gap between human intuition and machine capability with sustainable, premium hardware.
                        </p>
                        <div className="flex gap-4">
                            <a href="#" className="hover:text-cyan-400 transition-colors">Twitter</a>
                            <a href="#" className="hover:text-cyan-400 transition-colors">GitHub</a>
                            <a href="#" className="hover:text-cyan-400 transition-colors">LinkedIn</a>
                        </div>
                    </div>

                    {/* Category Links */}
                    <div>
                        <h3 className="text-slate-100 font-medium mb-6">Hardware</h3>
                        <ul className="space-y-4 text-sm font-light">
                            <li><Link to="/home?category_slug=laptops-computers" className="hover:text-cyan-400 transition-colors">Laptops</Link></li>
                            <li><Link to="/home?category_slug=smartphones-tablets" className="hover:text-cyan-400 transition-colors">Smartphones & Tablets</Link></li>
                            <li><Link to="/home?category_slug=audio-speakers" className="hover:text-cyan-400 transition-colors">Audio & Speakers</Link></li>
                            <li><Link to="/home?category_slug=accessories" className="hover:text-cyan-400 transition-colors">Accessories & Peripherals</Link></li>
                            <li><Link to="/home?category_slug=wearables-smartwatches" className="hover:text-cyan-400 transition-colors">Wearables & Smartwatches</Link></li>
                        </ul>
                    </div>

                    {/* Legal Links */}
                    <div>
                        <h3 className="text-slate-100 font-medium mb-6">Company</h3>
                        <ul className="space-y-4 text-sm font-light">
                            <li><Link to="/about-us" className="hover:text-cyan-400 transition-colors">About Us</Link></li>
                            <li><Link to="/privacy-policy" className="hover:text-cyan-400 transition-colors">Privacy Policy</Link></li>
                            <li><Link to="/support" className="hover:text-cyan-400 transition-colors">Contact Support</Link></li>
                            <li><a href="#" className="hover:text-cyan-400 transition-colors">Terms of Service</a></li>
                        </ul>
                    </div>

                    {/* Newsletter */}
                    <div>
                        <h3 className="text-slate-100 font-medium mb-6">Stay Updated</h3>
                        <p className="text-sm font-light mb-4">Subscribe to our newsletter for early access to new releases.</p>
                        <form className="flex">
                            <input
                                type="email"
                                placeholder="Enter your email"
                                className="flex-1 bg-slate-900 border border-slate-800 rounded-l-xl px-4 py-2 text-sm text-slate-200 focus:outline-none focus:border-cyan-500 transition-colors"
                            />
                            <button
                                type="submit"
                                className="bg-cyan-500 hover:bg-cyan-400 text-slate-900 px-4 py-2 rounded-r-xl transition-colors flex items-center justify-center"
                            >
                                <Mail className="w-4 h-4" />
                            </button>
                        </form>
                    </div>
                </div>

                <div className="mt-16 pt-8 border-t border-slate-800/50 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-light">
                    <p>&copy; 2026 VOLTAIRE/TECH. All rights reserved.</p>
                    <p className="flex items-center gap-1">Designed with <Zap className="w-3 h-3 text-cyan-400" /> in Neo-Tokyo</p>
                </div>
            </div>
        </footer>
    );
}
