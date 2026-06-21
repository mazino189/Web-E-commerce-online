import React, { useState } from 'react';
import api from '../../apiClient';

const ChangePassword: React.FC = () => {
    const [formData, setFormData] = useState({
        current_password: '',
        password: '',
        password_confirmation: ''
    });
    const [message, setMessage] = useState('');
    const [error, setError] = useState('');

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setMessage('');
        setError('');
        try {
            const response = await api.put<any>('/profile/password', formData);
            setMessage(response.message);
            setFormData({ current_password: '', password: '', password_confirmation: '' });
        } catch (err: any) {
            setError(err.response?.data?.message || 'Error changing password');
        }
    };

    return (
        <div className="max-w-2xl mx-auto mt-10 p-6 bg-slate-900/50 backdrop-blur-md rounded-2xl border border-slate-800 shadow-2xl">
            <h2 className="text-3xl font-light text-slate-100 mb-6 tracking-wide">Change Password</h2>
            
            {message && <div className="p-4 mb-6 text-sm text-green-400 bg-green-400/10 rounded-lg border border-green-400/20">{message}</div>}
            {error && <div className="p-4 mb-6 text-sm text-red-400 bg-red-400/10 rounded-lg border border-red-400/20">{error}</div>}

            <form onSubmit={handleSubmit} className="space-y-6">
                <div>
                    <label className="block text-sm font-medium text-slate-300 mb-2">Current Password</label>
                    <input 
                        type="password" 
                        name="current_password"
                        value={formData.current_password} 
                        onChange={handleChange}
                        className="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    />
                </div>
                <div>
                    <label className="block text-sm font-medium text-slate-300 mb-2">New Password</label>
                    <input 
                        type="password" 
                        name="password"
                        value={formData.password} 
                        onChange={handleChange}
                        className="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    />
                </div>
                <div>
                    <label className="block text-sm font-medium text-slate-300 mb-2">Confirm New Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation"
                        value={formData.password_confirmation} 
                        onChange={handleChange}
                        className="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    />
                </div>
                <button type="submit" className="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-3 rounded-lg transition-colors shadow-[0_0_15px_rgba(79,70,229,0.4)] hover:shadow-[0_0_25px_rgba(79,70,229,0.6)]">
                    Update Password
                </button>
            </form>
        </div>
    );
};

export default ChangePassword;
