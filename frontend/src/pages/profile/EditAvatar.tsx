import React, { useState, useRef } from 'react';
import { useAuth } from '../../context/AuthContext';
import api from '../../apiClient';

const EditAvatar: React.FC = () => {
    const { user } = useAuth(); // If login can refresh user context
    const [file, setFile] = useState<File | null>(null);
    const [preview, setPreview] = useState<string>(user?.avatar || '');
    const [message, setMessage] = useState('');
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);
    const fileInputRef = useRef<HTMLInputElement>(null);

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            const selectedFile = e.target.files[0];
            setFile(selectedFile);
            setPreview(URL.createObjectURL(selectedFile));
        }
    };

    const handleUpload = async () => {
        if (!file) return;
        setLoading(true);
        setMessage('');
        setError('');

        const formData = new FormData();
        formData.append('avatar', file);

        try {
            const response = await api.post<any>('/profile/avatar', formData);
            setMessage(response.message);
            // Ideally update AuthContext here
        } catch (err: any) {
            setError(err.response?.data?.message || 'Error uploading avatar');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="max-w-xl mx-auto mt-10 p-6 bg-slate-900/50 backdrop-blur-md rounded-2xl border border-slate-800 shadow-2xl text-center">
            <h2 className="text-3xl font-light text-slate-100 mb-6 tracking-wide">Change Avatar</h2>
            
            {message && <div className="p-4 mb-6 text-sm text-green-400 bg-green-400/10 rounded-lg border border-green-400/20">{message}</div>}
            {error && <div className="p-4 mb-6 text-sm text-red-400 bg-red-400/10 rounded-lg border border-red-400/20">{error}</div>}

            <div className="flex flex-col items-center space-y-6">
                <div 
                    className="w-40 h-40 rounded-full border-4 border-slate-700 overflow-hidden bg-slate-800 flex items-center justify-center cursor-pointer group relative shadow-[0_0_20px_rgba(0,0,0,0.5)]"
                    onClick={() => fileInputRef.current?.click()}
                >
                    {preview ? (
                        <img src={preview} alt="Avatar Preview" className="w-full h-full object-cover" />
                    ) : (
                        <span className="text-slate-500 text-sm group-hover:text-slate-300 transition-colors">Click to upload</span>
                    )}
                    <div className="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center transition-all">
                        <span className="text-white text-sm font-medium">Choose Image</span>
                    </div>
                </div>

                <input 
                    type="file" 
                    ref={fileInputRef} 
                    onChange={handleFileChange} 
                    className="hidden" 
                    accept="image/*"
                />

                <button 
                    onClick={handleUpload}
                    disabled={!file || loading}
                    className="w-full max-w-xs bg-fuchsia-600 hover:bg-fuchsia-500 disabled:bg-slate-700 disabled:text-slate-500 text-white font-medium py-3 rounded-lg transition-colors shadow-[0_0_15px_rgba(192,38,211,0.4)] hover:shadow-[0_0_25px_rgba(192,38,211,0.6)] disabled:shadow-none"
                >
                    {loading ? 'Uploading...' : 'Upload to Cloudinary'}
                </button>
            </div>
        </div>
    );
};

export default EditAvatar;
