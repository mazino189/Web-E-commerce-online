import { useState, useRef, useEffect } from 'react';
import { MessageSquare, X, Send, Bot, User, Sparkles } from 'lucide-react';

interface Message {
    id: string;
    text: string;
    sender: 'user' | 'bot';
    timestamp: Date;
}

const FAQ_RESPONSES: Array<{ patterns: string[]; response: string }> = [
    {
        patterns: ['hello', 'hi', 'hey', 'xin chào', 'chào'],
        response: "Hi there! 👋 I'm Voltaire AI. How can I help you today? You can ask me about orders, products, shipping, warranty, or payment."
    },
    {
        patterns: ['shipping', 'delivery', 'giao hàng', 'ship'],
        response: "📦 We offer:\n• **Standard shipping** (3–5 working days) — Free for orders over 500,000₫\n• **Express shipping** (1–2 working days) — 50,000₫\n\nAll orders are tracked end-to-end."
    },
    {
        patterns: ['warranty', 'bảo hành', 'guarantee', 'broken', 'defect'],
        response: "🛡️ All VOLTAIRE/TECH products include:\n• **1-year warranty** on manufacturing defects\n• **6-month battery warranty**\n• Free repair or replacement for covered defects\n\nVisit our Warranty Policy page for full details."
    },
    {
        patterns: ['return', 'refund', 'đổi trả', 'money back', 'hoàn tiền'],
        response: "↩️ Our return policy:\n• 7-day return window from delivery date\n• Item must be unused and in original packaging\n• Refund processed within 3–5 business days\n\nContact our support team to initiate a return."
    },
    {
        patterns: ['payment', 'pay', 'thanh toán', 'momo', 'vnpay', 'credit card', 'cod'],
        response: "💳 We accept:\n• **COD** — Cash on Delivery\n• **MoMo** — Mobile wallet\n• **VNPay** — Bank transfer\n• **Credit/Debit Card** — Visa, Mastercard\n\nAll payments are secured and encrypted."
    },
    {
        patterns: ['order', 'đơn hàng', 'my order', 'track', 'status'],
        response: "📋 To track your order:\n1. Go to **My Orders** in your account\n2. Find your order by ID\n3. Check the status in real time\n\nIf you have an issue with a specific order, please use the Contact Support form and include your Order #."
    },
    {
        patterns: ['cancel', 'hủy đơn', 'hủy'],
        response: "❌ You can cancel an order that is still in **Pending** status. Go to **My Orders** and click 'Cancel'. Once an order is being processed, cancellation is no longer available."
    },
    {
        patterns: ['laptop', 'computer', 'pc', 'notebook'],
        response: "💻 Our laptop collection features the latest models from Apple, Dell, Lenovo, ASUS, and more. Browse the **Laptops & Computers** category for full specs and pricing."
    },
    {
        patterns: ['phone', 'smartphone', 'điện thoại', 'iphone', 'android'],
        response: "📱 We carry the latest iPhones, Samsung Galaxy, and other flagship smartphones. Check out the **Smartphones & Tablets** section for current stock and pricing."
    },
    {
        patterns: ['audio', 'headphone', 'speaker', 'earphone', 'tai nghe'],
        response: "🎵 Our audio range includes Sony, JBL, Bose, and AirPods. Browse **Audio & Speakers** for the full catalog."
    },
    {
        patterns: ['gaming', 'game', 'controller', 'gpu', 'graphics'],
        response: "🎮 Check out our **Gaming Gear** section for gaming keyboards, mice, headsets, and controllers from top brands like Razer, Logitech, and SteelSeries."
    },
    {
        patterns: ['spec', 'specification', 'feature', 'detail'],
        response: "📊 Detailed specifications are available on each product page. Navigate to any product and scroll down to see the full tech specs."
    },
    {
        patterns: ['discount', 'sale', 'promo', 'coupon', 'giảm giá', 'khuyến mãi'],
        response: "🔥 Check out our **Sale** page for current promotions and discounts. We regularly update deals on top tech products!"
    },
    {
        patterns: ['account', 'password', 'login', 'register', 'sign'],
        response: "🔐 You can manage your account, update your password, and edit your profile under **My Profile** after logging in."
    },
    {
        patterns: ['contact', 'support', 'help', 'hỗ trợ', 'liên hệ'],
        response: "📬 For detailed support, use our **Contact Support** form — your message goes directly to our team and we typically respond within 24 hours.\n\nEmail: support@voltairetech.com\nHotline: 1900-XXXX"
    },
    {
        patterns: ['thank', 'cảm ơn', 'thanks'],
        response: "😊 You're welcome! Is there anything else I can help you with?"
    },
    {
        patterns: ['price', 'cost', 'giá', 'bao nhiêu', 'how much'],
        response: "💰 Prices are shown in Vietnamese Đồng (₫) on each product page. The price you see is the final price — no hidden fees!"
    },
];

const QUICK_REPLIES = [
    'How does shipping work?',
    'What is the warranty?',
    'Payment methods?',
    'Track my order',
    'Return policy',
];

function getBotResponse(userInput: string): string {
    const lower = userInput.toLowerCase();
    for (const faq of FAQ_RESPONSES) {
        if (faq.patterns.some(p => lower.includes(p))) {
            return faq.response;
        }
    }
    return "I'm not sure about that. For detailed help, please visit our **Contact Support** page or reach us at support@voltairetech.com — our team responds within 24 hours! 💬";
}

export default function SupportChat() {
    const [isOpen, setIsOpen] = useState(false);
    const [messages, setMessages] = useState<Message[]>([
        { id: '1', text: "Hi! 👋 I'm **Voltaire AI**. How can I help you today?", sender: 'bot', timestamp: new Date() }
    ]);
    const [input, setInput] = useState('');
    const [isTyping, setIsTyping] = useState(false);
    const messagesEndRef = useRef<HTMLDivElement>(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    };

    useEffect(() => {
        scrollToBottom();
    }, [messages, isTyping]);

    const sendMessage = (text: string) => {
        if (!text.trim()) return;

        const userMsg: Message = { id: Date.now().toString(), text, sender: 'user', timestamp: new Date() };
        setMessages(prev => [...prev, userMsg]);
        setInput('');
        setIsTyping(true);

        const delay = 800 + Math.random() * 800;
        setTimeout(() => {
            const botText = getBotResponse(text);
            setIsTyping(false);
            setMessages(prev => [...prev, {
                id: (Date.now() + 1).toString(),
                text: botText,
                sender: 'bot',
                timestamp: new Date()
            }]);
        }, delay);
    };

    const handleSend = (e: React.FormEvent) => {
        e.preventDefault();
        sendMessage(input);
    };

    const renderText = (text: string) => {
        // Simple markdown-like bold support
        return text.split('\n').map((line, i) => (
            <span key={i}>
                {line.split(/\*\*(.*?)\*\*/g).map((part, j) =>
                    j % 2 === 1 ? <strong key={j} className="font-semibold">{part}</strong> : part
                )}
                {i < text.split('\n').length - 1 && <br />}
            </span>
        ));
    };

    return (
        <>
            {/* Toggle Button */}
            <button
                onClick={() => setIsOpen(true)}
                className={`fixed bottom-6 right-6 p-4 bg-accent text-white rounded-full shadow-lg hover:scale-110 transition-all duration-200 z-50 ${isOpen ? 'hidden' : 'flex'} items-center justify-center`}
                aria-label="Open chat support"
            >
                <MessageSquare className="w-6 h-6" />
                {messages.filter(m => m.sender === 'bot').length > 1 && (
                    <span className="absolute -top-1 -right-1 w-4 h-4 bg-status-out rounded-full text-[10px] text-white flex items-center justify-center">!</span>
                )}
            </button>

            {/* Chat Window */}
            {isOpen && (
                <div className="fixed bottom-6 right-6 w-80 sm:w-96 bg-surface border border-border rounded-2xl shadow-2xl flex flex-col z-50 overflow-hidden h-[530px] max-h-[85vh] animate-slide-up">
                    {/* Header */}
                    <div className="p-4 bg-canvas border-b border-border flex items-center justify-between">
                        <div className="flex items-center gap-3">
                            <div className="relative">
                                <div className="w-9 h-9 rounded-full bg-accent/20 flex items-center justify-center">
                                    <Bot className="w-5 h-5 text-accent" />
                                </div>
                                <div className="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-status-in rounded-full border-2 border-canvas" />
                            </div>
                            <div>
                                <div className="flex items-center gap-1.5">
                                    <span className="text-sm font-semibold text-foreground">Voltaire AI</span>
                                    <Sparkles className="w-3.5 h-3.5 text-accent" />
                                </div>
                                <span className="text-xs text-status-in">Online now</span>
                            </div>
                        </div>
                        <button onClick={() => setIsOpen(false)} className="text-muted hover:text-foreground transition-colors p-1 rounded-lg hover:bg-border">
                            <X className="w-4 h-4" />
                        </button>
                    </div>

                    {/* Messages */}
                    <div className="flex-1 overflow-y-auto p-4 space-y-4 bg-canvas/50">
                        {messages.map(msg => (
                            <div key={msg.id} className={`flex ${msg.sender === 'user' ? 'justify-end' : 'justify-start'}`}>
                                <div className={`flex items-end gap-2 max-w-[85%] ${msg.sender === 'user' ? 'flex-row-reverse' : 'flex-row'}`}>
                                    <div className={`w-7 h-7 rounded-full flex items-center justify-center shrink-0 mb-0.5 ${msg.sender === 'user' ? 'bg-accent' : 'bg-accent/15 border border-accent/30'}`}>
                                        {msg.sender === 'user'
                                            ? <User className="w-3.5 h-3.5 text-white" />
                                            : <Bot className="w-3.5 h-3.5 text-accent" />
                                        }
                                    </div>
                                    <div className={`px-3.5 py-2.5 rounded-2xl text-sm leading-relaxed ${
                                        msg.sender === 'user'
                                            ? 'bg-accent text-white rounded-br-sm'
                                            : 'bg-surface border border-border text-foreground rounded-bl-sm'
                                    }`}>
                                        {renderText(msg.text)}
                                    </div>
                                </div>
                            </div>
                        ))}

                        {/* Typing indicator */}
                        {isTyping && (
                            <div className="flex items-end gap-2">
                                <div className="w-7 h-7 rounded-full bg-accent/15 border border-accent/30 flex items-center justify-center shrink-0">
                                    <Bot className="w-3.5 h-3.5 text-accent" />
                                </div>
                                <div className="px-4 py-3 bg-surface border border-border rounded-2xl rounded-bl-sm flex items-center gap-1.5">
                                    {[0, 150, 300].map(delay => (
                                        <span
                                            key={delay}
                                            className="w-1.5 h-1.5 bg-muted rounded-full animate-bounce"
                                            style={{ animationDelay: `${delay}ms` }}
                                        />
                                    ))}
                                </div>
                            </div>
                        )}
                        <div ref={messagesEndRef} />
                    </div>

                    {/* Quick Replies */}
                    {messages.length <= 2 && (
                        <div className="px-4 py-2 bg-canvas/50 border-t border-border flex gap-2 overflow-x-auto scrollbar-hide">
                            {QUICK_REPLIES.map(qr => (
                                <button
                                    key={qr}
                                    onClick={() => sendMessage(qr)}
                                    className="shrink-0 px-3 py-1.5 text-xs font-medium border border-border rounded-full text-muted hover:border-accent hover:text-accent transition-colors bg-surface whitespace-nowrap"
                                >
                                    {qr}
                                </button>
                            ))}
                        </div>
                    )}

                    {/* Input */}
                    <form onSubmit={handleSend} className="p-3 bg-surface border-t border-border">
                        <div className="relative">
                            <input
                                type="text"
                                value={input}
                                onChange={(e) => setInput(e.target.value)}
                                placeholder="Ask me anything..."
                                className="w-full pl-4 pr-11 py-2.5 bg-canvas border border-border rounded-xl text-sm text-foreground focus:outline-none focus:border-accent transition-colors placeholder:text-muted/60"
                            />
                            <button
                                type="submit"
                                disabled={!input.trim() || isTyping}
                                className="absolute right-1.5 top-1/2 -translate-y-1/2 p-2 text-accent disabled:text-muted hover:bg-accent/10 rounded-lg transition-colors"
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
