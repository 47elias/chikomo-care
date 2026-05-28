import React, { useState, useEffect } from 'react';
import axios from 'axios';
import {
  Menu,
  Plus,
  MessageSquare,
  HelpCircle,
  LogOut,
  Clock,
  Loader2,
  BookOpen,
  Users,
  Sparkles,
  PlayCircle,
  MapPin,
  Calendar,
  ChevronRight,
  Heart
} from 'lucide-react';

const Sidebar = ({ isOpen, toggleSidebar, alias, token, onSelectConversation }) => {
  const [conversations, setConversations] = useState([]);
  const [loading, setLoading] = useState(false);

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
  }, [isOpen, token]);

  return (
    <div className={`${isOpen ? 'w-72' : 'w-0'} bg-slate-50 border-r border-slate-200 flex flex-col transition-all duration-300 ease-in-out relative shrink-0 z-50 h-full`}>

      <div className={`${!isOpen && 'hidden'} flex-1 flex flex-col p-4 overflow-hidden`}>

        {/* Header: Branding & Action */}
        <div className="flex items-center gap-3 mb-6">
          <button onClick={toggleSidebar} className="p-2 hover:bg-slate-200 rounded-lg text-slate-500 transition-colors">
            <Menu size={20} />
          </button>
          <button
            onClick={() => {
              localStorage.removeItem('chikomo_token');
              window.location.reload();
            }}
            className="flex-1 flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-[11px] font-black shadow-lg shadow-indigo-100 transition-all active:scale-95"
          >
            <Plus size={14} strokeWidth={3} />
            NEW SESSION
          </button>
        </div>

        <div className="flex-1 overflow-y-auto space-y-7 pr-1 custom-scrollbar">

          {/* 1. PHYSICAL COUNSELOR BOOKING (NEW) */}
          <section>
            <div className="flex items-center gap-2 px-2 mb-3">
              <MapPin size={12} className="text-rose-500" />
              <h3 className="text-[10px] font-black text-slate-400 uppercase tracking-widest">In-Person Care</h3>
            </div>

            <button className="w-full flex flex-col gap-3 p-4 bg-gradient-to-br from-rose-50 to-white border border-rose-100 rounded-2xl hover:border-rose-300 hover:shadow-md transition-all text-left group">
              <div className="flex items-center justify-between">
                <div className="p-2 bg-rose-500 text-white rounded-lg shadow-sm">
                  <Calendar size={16} />
                </div>
                <span className="text-[9px] font-bold text-rose-600 bg-rose-100 px-2 py-0.5 rounded-full uppercase">On-Campus</span>
              </div>
              <div>
                <p className="text-[11px] font-black text-slate-800">Book Office Visit</p>
                <p className="text-[9px] text-slate-500 font-medium">Face-to-face professional session</p>
              </div>
              <div className="flex items-center gap-1 text-[10px] font-bold text-rose-600 group-hover:gap-2 transition-all">
                Find available slots <ChevronRight size={12} />
              </div>
            </button>
          </section>

          {/* 2. INTERACTIVE LEARNING HUB */}
          <section>
            <div className="flex items-center gap-2 px-2 mb-3">
              <BookOpen size={12} className="text-indigo-500" />
              <h3 className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Learning Hub</h3>
            </div>

            <div className="grid grid-cols-1 gap-2">
              <button className="flex items-center justify-between p-3 bg-white border border-slate-200 rounded-xl hover:border-indigo-400 hover:shadow-md group transition-all">
                <div className="flex items-center gap-3">
                  <div className="p-2 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <PlayCircle size={16} />
                  </div>
                  <span className="text-[11px] font-bold text-slate-700">Daily Mindfulness</span>
                </div>
                <ChevronRight size={14} className="text-slate-300 group-hover:text-indigo-600" />
              </button>

              <button className="flex items-center justify-between p-3 bg-white border border-slate-200 rounded-xl hover:border-amber-400 hover:shadow-md group transition-all">
                <div className="flex items-center gap-3">
                  <div className="p-2 bg-amber-50 text-amber-600 rounded-lg group-hover:bg-amber-500 group-hover:text-white transition-colors">
                    <Sparkles size={16} />
                  </div>
                  <span className="text-[11px] font-bold text-slate-700">Stress Modules</span>
                </div>
                <ChevronRight size={14} className="text-slate-300 group-hover:text-amber-600" />
              </button>
            </div>
          </section>

          {/* 3. PEER SUPPORT */}
          <section>
            <div className="flex items-center gap-2 px-2 mb-3">
              <Users size={12} className="text-emerald-500" />
              <h3 className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Community</h3>
            </div>

            <div className="grid grid-cols-1 gap-2">
              <button className="flex items-center gap-3 p-3 bg-emerald-50/50 border border-emerald-100 rounded-xl hover:bg-emerald-100 transition-all text-left">
                <div className="w-8 h-8 rounded-full bg-emerald-200 border-2 border-white flex items-center justify-center text-[10px] font-bold text-emerald-700 shrink-0">
                  <Heart size={14} />
                </div>
                <div>
                  <p className="text-[11px] font-bold text-emerald-900">Read Peer Stories</p>
                  <p className="text-[9px] text-emerald-600 font-medium">12 New Stories Today</p>
                </div>
              </button>
            </div>
          </section>

          {/* 4. CONVERSATION HISTORY */}
          <section>
            <div className="flex items-center gap-2 px-2 mb-3">
              <Clock size={12} className="text-slate-400" />
              <h3 className="text-[10px] font-black text-slate-400 uppercase tracking-widest">History</h3>
            </div>

            <div className="space-y-1">
              {loading ? (
                <div className="flex items-center justify-center py-4">
                  <Loader2 size={20} className="animate-spin text-indigo-400" />
                </div>
              ) : conversations.length === 0 ? (
                <p className="px-3 py-2 text-[11px] text-slate-400 italic">No past chats.</p>
              ) : (
                conversations.map((conv) => (
                  <button
                    key={conv.token}
                    onClick={() => onSelectConversation(conv.token)}
                    className={`w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-semibold text-left transition-all group ${
                      token === conv.token
                      ? 'bg-white shadow-sm border border-slate-200 text-indigo-700 ring-2 ring-indigo-50'
                      : 'hover:bg-slate-200/50 border border-transparent text-slate-600'
                    }`}
                  >
                    <MessageSquare size={14} className={token === conv.token ? 'text-indigo-600' : 'text-slate-400'} />
                    <span className="truncate flex-1">{conv.alias || 'Anonymous'}</span>
                  </button>
                ))
              )}
            </div>
          </section>
        </div>

        {/* Footer */}
        <div className="mt-auto pt-4 border-t border-slate-200 space-y-2">
          <div className="flex items-center gap-3 px-3 py-3 bg-white border border-slate-200 rounded-xl">
            <div className="w-2 h-2 rounded-full bg-green-500 animate-pulse" />
            <div className="overflow-hidden">
              <p className="text-[9px] font-black text-slate-400 uppercase">Persona</p>
              <p className="text-xs font-bold text-slate-700 truncate">{alias || 'Guest'}</p>
            </div>
          </div>

          <button className="w-full flex items-center justify-center gap-2 py-3 bg-red-50 text-red-600 rounded-xl text-[10px] font-black tracking-widest hover:bg-red-100 transition-colors">
            <LogOut size={14} />
            QUICK EXIT
          </button>
        </div>
      </div>
    </div>
  );
};

export default Sidebar;
