import { useState, useRef, useEffect } from 'react';
import { MessageSquare, X, Send, Bot, User } from 'lucide-react';

interface Message {
    id: string;
    text: string;
    sender: 'user' | 'bot';
}

export default function SupportChat() {
    const [isOpen, setIsOpen] = useState(false);
    const [messages, setMessages] = useState<Message[]>([
        { id: '1', text: 'Hello! I am Voltaire AI. How can I assist you today?', sender: 'bot' }
    ]);
    const [input, setInput] = useState('');
    const messagesEndRef = useRef<HTMLDivElement>(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    };

    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    const handleSend = (e: React.FormEvent) => {
        e.preventDefault();
        if (!input.trim()) return;

        const userMsg: Message = { id: Date.now().toString(), text: input, sender: 'user' };
        setMessages(prev => [...prev, userMsg]);
        setInput('');

        // Simulated AI response
        setTimeout(() => {
            let botText = "I'm sorry, I didn't quite catch that. Could you please provide more details?";
            const lowerInput = userMsg.text.toLowerCase();

            if (lowerInput.includes('shipping') || lowerInput.includes('delivery')) {
                botText = "We offer standard 3-5 day shipping and express 1-2 day shipping nationwide.";
            } else if (lowerInput.includes('warranty') || lowerInput.includes('guarantee')) {
                botText = "All our hardware products come with a standard 1-year limited warranty covering manufacturing defects.";
            } else if (lowerInput.includes('specs') || lowerInput.includes('specification')) {
                botText = "You can find detailed specifications on the product page under the 'Specs' tab.";
            } else if (lowerInput.includes('hello') || lowerInput.includes('hi')) {
                botText = "Hi there! How can I help you with our tech products today?";
            }

            setMessages(prev => [...prev, { id: Date.now().toString(), text: botText, sender: 'bot' }]);
        }, 1000);
    };

    return (
        <>
            {/* Toggle Button */}
            <button
                onClick={() => setIsOpen(true)}
                className={`fixed bottom-6 right-6 p-4 bg-accent text-white rounded-full shadow-lg hover:scale-110 transition-transform z-50 ${isOpen ? 'hidden' : 'flex'}`}
            >
                <MessageSquare className="w-6 h-6" />
            </button>

            {/* Chat Window */}
            {isOpen && (
                <div className="fixed bottom-6 right-6 w-80 sm:w-96 bg-surface border border-border rounded-2xl shadow-2xl flex flex-col z-50 overflow-hidden h-[500px] max-h-[80vh]">
                    {/* Header */}
                    <div className="p-4 bg-cyber text-foreground flex items-center justify-between border-b border-border">
                        <div className="flex items-center gap-2">
                            <Bot className="w-5 h-5 text-accent" />
                            <span className="font-semibold">Voltaire AI Support</span>
                        </div>
                        <button onClick={() => setIsOpen(false)} className="text-muted hover:text-white transition-colors">
                            <X className="w-5 h-5" />
                        </button>
                    </div>

                    {/* Messages */}
                    <div className="flex-1 overflow-y-auto p-4 space-y-4 bg-canvas">
                        {messages.map(msg => (
                            <div key={msg.id} className={`flex ${msg.sender === 'user' ? 'justify-end' : 'justify-start'}`}>
                                <div className={`flex items-start gap-2 max-w-[80%] ${msg.sender === 'user' ? 'flex-row-reverse' : 'flex-row'}`}>
                                    <div className={`w-8 h-8 rounded-full flex items-center justify-center shrink-0 ${msg.sender === 'user' ? 'bg-accent text-white' : 'bg-cyber text-accent'}`}>
                                        {msg.sender === 'user' ? <User className="w-4 h-4" /> : <Bot className="w-4 h-4" />}
                                    </div>
                                    <div className={`p-3 rounded-2xl text-sm ${msg.sender === 'user' ? 'bg-accent text-white rounded-tr-sm' : 'bg-surface border border-border text-foreground rounded-tl-sm'}`}>
                                        {msg.text}
                                    </div>
                                </div>
                            </div>
                        ))}
                        <div ref={messagesEndRef} />
                    </div>

                    {/* Input Area */}
                    <form onSubmit={handleSend} className="p-4 bg-surface border-t border-border">
                        <div className="relative">
                            <input
                                type="text"
                                value={input}
                                onChange={(e) => setInput(e.target.value)}
                                placeholder="Type a message..."
                                className="w-full pl-4 pr-12 py-3 bg-canvas border border-border rounded-xl text-sm text-foreground focus:outline-none focus:border-accent transition-colors"
                            />
                            <button
                                type="submit"
                                disabled={!input.trim()}
                                className="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-accent disabled:text-muted hover:bg-accent/10 rounded-lg transition-colors"
                            >
                                <Send className="w-4 h-4" />
                            </button>
                        </div>
                    </form>
                </div>
            )}
        </>
    );
}
