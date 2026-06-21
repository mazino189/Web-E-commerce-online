import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import api from '../../apiClient';

const EditProfile: React.FC = () => {
    const { user } = useAuth(); // using login to update user state if needed
    const [formData, setFormData] = useState({
        name: user?.name || '',
        email: user?.email || '',
        phone: user?.phone || '',
        address: user?.address || ''
    });
    const [message, setMessage] = useState('');
    const [error, setError] = useState('');

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setMessage('');
        setError('');
        try {
            const response = await api.put<any>('/profile', formData);
            setMessage(response.message);
            // In a real app we might update the AuthContext user here
        } catch (err: any) {
            setError(err.response?.data?.message || 'Error updating profile');
        }
    };

    return (
        <div className="max-w-2xl mx-auto mt-10 p-6 bg-slate-900/50 backdrop-blur-md rounded-2xl border border-slate-800 shadow-2xl">
            <h2 className="text-3xl font-light text-slate-100 mb-6 tracking-wide">Edit Profile</h2>
            
            {message && <div className="p-4 mb-6 text-sm text-green-400 bg-green-400/10 rounded-lg border border-green-400/20">{message}</div>}
            {error && <div className="p-4 mb-6 text-sm text-red-400 bg-red-400/10 rounded-lg border border-red-400/20">{error}</div>}

            <form onSubmit={handleSubmit} className="space-y-6">
                <div>
                    <label className="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
                    <input 
                        type="text" 
                        name="name"
                        value={formData.name} 
                        onChange={handleChange}
                        className="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    />
                </div>
                <div>
                    <label className="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                    <input 
                        type="email" 
                        name="email"
                        value={formData.email} 
                        onChange={handleChange}
                        className="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    />
                </div>
                <div>
                    <label className="block text-sm font-medium text-slate-300 mb-2">Phone Number</label>
                    <input 
                        type="text" 
                        name="phone"
                        value={formData.phone} 
                        onChange={handleChange}
                        className="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    />
                </div>
                <div>
                    <label className="block text-sm font-medium text-slate-300 mb-2">Address</label>
                    <textarea 
                        name="address"
                        value={formData.address} 
                        onChange={handleChange}
                        className="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all min-h-[100px]"
                    />
                </div>
                <button type="submit" className="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-medium py-3 rounded-lg transition-colors shadow-[0_0_15px_rgba(8,145,178,0.4)] hover:shadow-[0_0_25px_rgba(8,145,178,0.6)]">
                    Save Changes
                </button>
            </form>
        </div>
    );
};

export default EditProfile;
