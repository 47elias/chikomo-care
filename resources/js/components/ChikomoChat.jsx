import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import {
  Menu,
  ShieldCheck,
  ArrowUp,
  MoreHorizontal,
  User,
  Sparkles
} from 'lucide-react';
import Sidebar from './Sidebar';

const ChikomoChat = () => {
    const [messages, setMessages] = useState([]);
    const [input, setInput] = useState('');
    const [alias, setAlias] = useState('');
    const [token, setToken] = useState(localStorage.getItem('chikomo_token'));
    const [isLoading, setIsLoading] = useState(false);
    const [isSidebarOpen, setIsSidebarOpen] = useState(true);
    const scrollRef = useRef(null);

    // Initial Setup: Fetch Session and History
    useEffect(() => {
        const initSession = async () => {
            try {
                // 1. Initialize session/alias
                const sessionRes = await axios.post('/api/session/init', {}, {
                    headers: {
                        'X-Chikomo-Token': token || '',
                        'Accept': 'application/json'
                    }
                });

                const newToken = sessionRes.data.token;
                const userAlias = sessionRes.data.alias;

                setToken(newToken);
                setAlias(userAlias);
                localStorage.setItem('chikomo_token', newToken);

                // 2. Fetch History for this specific token
                const historyRes = await axios.get('/api/chat/history', {
                    params: { token: newToken }
                });

                setMessages(historyRes.data.messages || []);
            } catch (error) {
                console.error("Failed to initialize Chikomo session:", error);
                // If the token is invalid/expired, clear it and try once more
                if (error.response?.status === 404 || error.response?.status === 401) {
                    localStorage.removeItem('chikomo_token');
                    setToken(null);
                }
            }
        };
        initSession();
    }, []);

    // Function to switch between conversations from the Sidebar
    const handleSelectConversation = async (selectedToken) => {
        if (selectedToken === token) return;

        setIsLoading(true);
        try {
            const historyRes = await axios.get('/api/chat/history', {
                params: { token: selectedToken }
            });

            setToken(selectedToken);
            setAlias(historyRes.data.alias);
            setMessages(historyRes.data.messages || []);
            localStorage.setItem('chikomo_token', selectedToken);

            // Close sidebar on mobile after selection
            if (window.innerWidth < 1024) {
                setIsSidebarOpen(false);
            }
        } catch (err) {
            console.error("Failed to load conversation history:", err);
        } finally {
            setIsLoading(false);
        }
    };

    // Auto-scroll logic
    useEffect(() => {
        if (scrollRef.current) {
            scrollRef.current.scrollIntoView({ behavior: "smooth" });
        }
    }, [messages, isLoading]);

    const handleSend = async (e, customMsg = null) => {
        if (e) e.preventDefault();
        const messageToSend = customMsg || input;

        if (!messageToSend.trim() || isLoading) return;

        setInput('');
        setMessages(prev => [...prev, { content: messageToSend, sender_type: 'user' }]);
        setIsLoading(true);

        try {
            const res = await axios.post('/api/chat/send', {
                token: token,
                message: messageToSend
            });

            setMessages(prev => [...prev, {
                content: res.data.content,
                sender_type: 'ai',
                timestamp: res.data.timestamp
            }]);
        } catch (err) {
            console.error("Chat Error:", err);
            const errorMsg = err.response?.data?.message ||
                             "I'm having trouble connecting. Please try again in a moment.";

            setMessages(prev => [...prev, {
                content: errorMsg,
                sender_type: 'ai'
            }]);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className="flex h-screen bg-white font-sans text-slate-900 overflow-hidden">
            <Sidebar
                isOpen={isSidebarOpen}
                toggleSidebar={() => setIsSidebarOpen(!isSidebarOpen)}
                alias={alias}
                token={token}
                onSelectConversation={handleSelectConversation}
            />

            <div className="flex-1 flex flex-col min-w-0 bg-white relative">
                <header className="h-16 flex items-center justify-between px-6 bg-indigo-600 shadow-lg shrink-0 z-20">
                    <div className="flex items-center gap-4">
                        {!isSidebarOpen && (
                            <button onClick={() => setIsSidebarOpen(true)} className="text-white p-2 hover:bg-indigo-700 rounded-lg transition-colors">
                                <Menu size={20} />
                            </button>
                        )}
                        <div className="flex flex-col">
                            <h1 className="text-sm font-black tracking-[0.2em] text-white uppercase leading-none">CHIKOMO CARE</h1>
                            <span className="text-[9px] text-indigo-200 font-bold uppercase tracking-widest mt-1">Youth Support Platform</span>
                        </div>
                    </div>

                    <div className="flex items-center gap-3 bg-indigo-700/50 px-3 py-1.5 rounded-xl border border-indigo-500/30">
                        <div className="text-right">
                            <p className="text-[10px] font-black text-white uppercase tracking-tighter">{alias || 'Anonymous'}</p>
                        </div>
                        <ShieldCheck size={18} className="text-emerald-400" />
                    </div>
                </header>

                <main className="flex-1 overflow-y-auto custom-scrollbar bg-slate-50/30">
                    <div className="max-w-3xl mx-auto px-6 py-12">
                        {messages.length === 0 ? (
                            <div className="py-12 flex flex-col items-center text-center animate-fade-in">
                                <div className="w-16 h-16 bg-indigo-50 rounded-3xl flex items-center justify-center text-indigo-600 mb-6 shadow-sm">
                                    <Sparkles size={32} />
                                </div>
                                <h2 className="text-3xl font-bold text-slate-800 mb-3 italic">"How can I help you, {alias || 'friend'}?"</h2>
                                <p className="text-slate-400 text-sm max-w-sm mb-10">Choose a topic below or type your own to start a private conversation.</p>

                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full max-w-xl">
                                    {[
                                        "I'm feeling very stressed lately",
                                        "Help me understand peer pressure",
                                        "Ways to improve my mental health",
                                        "I just need someone to talk to"
                                    ].map((prompt, i) => (
                                        <button
                                            key={i}
                                            onClick={() => handleSend(null, prompt)}
                                            className="p-4 text-left bg-white border border-slate-200 rounded-2xl text-xs font-semibold text-slate-600 hover:border-indigo-400 hover:bg-indigo-50 transition-all shadow-sm group"
                                        >
                                            {prompt}
                                            <ArrowUp size={14} className="float-right text-slate-300 group-hover:text-indigo-500" />
                                        </button>
                                    ))}
                                </div>
                            </div>
                        ) : (
                            <div className="space-y-8">
                                {messages.map((msg, i) => (
                                    <div key={i} className={`flex gap-4 ${msg.sender_type === 'user' ? 'justify-end' : 'justify-start'} animate-pop-in`}>
                                        {msg.sender_type !== 'user' && (
                                            <div className="shrink-0 w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-[10px] font-black shadow-md">
                                                CC
                                            </div>
                                        )}
                                        <div className={`max-w-[85%] text-[14px] leading-relaxed px-5 py-3.5 shadow-sm ${
                                            msg.sender_type === 'user'
                                            ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-none font-medium'
                                            : 'bg-white border border-slate-100 text-slate-700 rounded-2xl rounded-tl-none'
                                        }`}>
                                            {msg.content}
                                        </div>
                                        {msg.sender_type === 'user' && (
                                            <div className="shrink-0 w-9 h-9 rounded-xl bg-slate-200 flex items-center justify-center text-slate-500 border border-slate-300 shadow-sm">
                                                <User size={18} />
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        )}

                        {isLoading && (
                            <div className="flex items-center gap-3 mt-8 animate-pulse">
                                <div className="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center">
                                    <MoreHorizontal className="text-indigo-600" />
                                </div>
                                <span className="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Chikomo is reflecting...</span>
                            </div>
                        )}
                        <div ref={scrollRef} className="h-4" />
                    </div>
                </main>

                <footer className="p-6 shrink-0 bg-white border-t border-slate-100">
                    <div className="max-w-3xl mx-auto">
                        <form onSubmit={handleSend} className="relative group">
                            <div className="flex items-center bg-slate-100 border border-transparent rounded-[2rem] p-1.5 pl-6 focus-within:bg-white focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-50 transition-all shadow-inner">
                                <input
                                    value={input}
                                    onChange={(e) => setInput(e.target.value)}
                                    placeholder="Message Chikomo Care..."
                                    className="flex-1 bg-transparent border-none focus:ring-0 py-3 text-sm outline-none text-slate-700 font-medium"
                                />
                                <button
                                    type="submit"
                                    disabled={!input.trim() || isLoading}
                                    className={`p-3 rounded-full transition-all ${input.trim() ? 'bg-indigo-600 text-white shadow-lg scale-100' : 'bg-slate-200 text-slate-400 scale-90'}`}
                                >
                                    <ArrowUp size={20} strokeWidth={3} />
                                </button>
                            </div>
                            <p className="text-[9px] text-center mt-4 text-slate-400 font-bold uppercase tracking-wider">
                                Secure Anonymous Session • No data is shared with third parties
                            </p>
                        </form>
                    </div>
                </footer>
            </div>
        </div>
    );
};

export default ChikomoChat;
