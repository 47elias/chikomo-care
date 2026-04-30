import React, { useState, useEffect } from 'react';
import axios from 'axios';
import {
  Menu,
  Plus,
  MessageSquare,
  HelpCircle,
  LogOut,
  Clock,
  Loader2
} from 'lucide-react';

const Sidebar = ({ isOpen, toggleSidebar, alias, token, onSelectConversation }) => {
  const [conversations, setConversations] = useState([]);
  const [loading, setLoading] = useState(false);

  // Fetch history only when sidebar is open AND we have a valid token
  useEffect(() => {
    const fetchHistory = async () => {
      if (!token) return;

      setLoading(true);
      try {
        const res = await axios.get('/api/conversations', {
          headers: {
            'X-Chikomo-Token': token,
            'Accept': 'application/json'
          }
        });
        setConversations(res.data);
      } catch (err) {
        console.error("Error fetching conversation list:", err);
      } finally {
        setLoading(false);
      }
    };

    if (isOpen && token) {
      fetchHistory();
    }
  }, [isOpen, token]); // Re-run if token becomes available

  return (
    <div className={`${isOpen ? 'w-72' : 'w-0'} bg-slate-50 border-r border-slate-200 flex flex-col transition-all duration-300 ease-in-out relative shrink-0 z-50 h-full`}>

      <div className={`${!isOpen && 'hidden'} flex-1 flex flex-col p-4 overflow-hidden`}>

        {/* Header Actions */}
        <div className="flex items-center gap-4 mb-8">
          <button onClick={toggleSidebar} className="p-2 hover:bg-slate-200 rounded-lg text-slate-500 transition-colors">
            <Menu size={20} />
          </button>
          <button
            onClick={() => {
              localStorage.removeItem('chikomo_token');
              window.location.reload();
            }}
            className="flex items-center gap-2 bg-white border border-slate-200 py-2 px-4 rounded-full text-[11px] font-bold text-slate-600 shadow-sm hover:shadow-md transition-all active:scale-95"
          >
            <Plus size={14} strokeWidth={3} className="text-indigo-600" />
            NEW CHAT
          </button>
        </div>

        {/* Recent History Section */}
        <div className="flex-1 overflow-y-auto custom-scrollbar">
          <div className="flex items-center gap-2 px-3 mb-4">
            <Clock size={12} className="text-slate-400" />
            <h3 className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Your History</h3>
          </div>

          <div className="space-y-1">
            {loading && (
              <div className="flex items-center justify-center py-4">
                <Loader2 size={20} className="animate-spin text-indigo-400" />
              </div>
            )}

            {!loading && conversations.length === 0 && (
              <p className="px-3 py-2 text-[11px] text-slate-400 italic">
                {token ? 'No past conversations found.' : 'Initializing session...'}
              </p>
            )}

            {conversations.map((conv) => (
              <button
                key={conv.token}
                onClick={() => onSelectConversation(conv.token)}
                className={`w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-semibold text-left transition-all group ${
                  token === conv.token
                  ? 'bg-indigo-100 text-indigo-700 border border-indigo-200'
                  : 'hover:bg-indigo-50 border border-transparent text-slate-600'
                }`}
              >
                <MessageSquare size={14} className={`shrink-0 ${token === conv.token ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500'}`} />
                <div className="flex flex-col truncate">
                  <span className="truncate">{conv.alias || 'Anonymous Session'}</span>
                  <span className="text-[9px] text-slate-400 font-normal uppercase">
                    {new Date(conv.updated_at).toLocaleDateString()}
                  </span>
                </div>
              </button>
            ))}
          </div>
        </div>

        {/* Footer Navigation */}
        <div className="pt-4 border-t border-slate-200 space-y-1">
          <div className="px-3 py-3 mb-2 bg-white border border-slate-200 rounded-xl shadow-sm">
            <p className="text-[9px] font-black text-slate-400 uppercase tracking-tighter leading-none mb-1">Active Persona</p>
            <p className="text-xs font-bold text-indigo-600 truncate">{alias || 'Guest User'}</p>
          </div>

          <button className="w-full flex items-center gap-3 px-3 py-2.5 hover:bg-slate-200 rounded-xl text-xs font-bold text-slate-500 transition-colors">
            <HelpCircle size={16} />
            HELP & SAFETY
          </button>

          <button
            onClick={() => window.location.href = 'https://google.com'}
            className="w-full flex items-center gap-3 px-3 py-4 text-red-500 hover:bg-red-50 rounded-xl text-xs font-black tracking-widest transition-colors mt-2"
          >
            <LogOut size={16} />
            QUICK EXIT
          </button>
        </div>
      </div>
    </div>
  );
};

export default Sidebar;
