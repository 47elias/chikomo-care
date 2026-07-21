import React from 'react';
import {
  MessageSquare,
  Library,
  BookOpen,
  Users,
  Compass,
  Bot,
  HeartHandshake,
  ShieldCheck,
  ChevronRight,
} from 'lucide-react';

export default function Sidebar({ currentView, onViewChange, alias, conversations, onSelectConversation }) {
  const navItems = [
    { id: 'ai-chat', label: 'Chikomo AI Guidance', icon: Bot, accent: 'from-teal-500 to-emerald-600' },
    { id: 'counselor-chat', label: 'Live Counselor Desk', icon: HeartHandshake, accent: 'from-indigo-500 to-violet-600' },
    { id: 'stress-modules', label: 'Stress Modules', icon: Library, accent: 'from-amber-500 to-orange-600' },
    { id: 'peer-stories', label: 'Peer Stories Hub', icon: BookOpen, accent: 'from-rose-500 to-pink-600' },
  ];

  return (
    <div className="w-80 h-screen bg-slate-900 border-r border-slate-800 flex flex-col text-slate-200">
      {/* Profile/Identity Card */}
      <div className="p-6 border-b border-slate-800/80 bg-gradient-to-b from-slate-950/60 to-slate-900 flex-shrink-0">
        <div className="flex items-center space-x-3">
          <div className="relative flex-shrink-0">
            <div className="w-11 h-11 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center text-white font-bold shadow-md shadow-teal-900/40">
              {alias ? alias[0] : 'C'}
            </div>
            <span className="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-slate-900" />
          </div>
          <div className="min-w-0">
            <p className="text-[11px] text-slate-500 font-medium flex items-center gap-1">
              <ShieldCheck className="w-3 h-3 text-emerald-500" />
              Anonymous Identity
            </p>
            <h4 className="text-sm font-semibold text-teal-400 truncate w-48">{alias || 'Connecting...'}</h4>
          </div>
        </div>
      </div>

      {/* Main Feature View Navigation */}
      <div className="p-4 space-y-1.5 flex-shrink-0">
        {navItems.map(({ id, label, icon: Icon, accent }) => {
          const isActive = currentView === id;
          return (
            <button
              key={id}
              onClick={() => onViewChange(id)}
              className={`group w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all ${
                isActive
                  ? `bg-gradient-to-r ${accent} text-white shadow-lg shadow-black/20`
                  : 'text-slate-400 hover:bg-slate-800/70 hover:text-slate-100'
              }`}
            >
              <Icon className={`w-5 h-5 flex-shrink-0 ${isActive ? 'text-white' : 'text-slate-500 group-hover:text-teal-400'} transition-colors`} />
              <span className="flex-1 text-left truncate">{label}</span>
              {isActive && <ChevronRight className="w-4 h-4 text-white/80 flex-shrink-0" />}
            </button>
          );
        })}
      </div>

      <div className="border-t border-slate-800/80 mx-4" />

      {/* Dynamic Context History List Block (Visible during AI Chat context view modes) */}
      <div className="flex-1 overflow-y-auto px-4 py-3 space-y-2">
        {currentView === 'ai-chat' && (
          <>
            <p className="text-[11px] font-semibold text-slate-500 uppercase tracking-wider px-2 mb-2 flex items-center gap-1.5">
              <MessageSquare className="w-3 h-3" />
              AI Conversations
            </p>
            {conversations && conversations.length > 0 ? (
              conversations.map((chat) => (
                <button
                  key={chat.id}
                  onClick={() => onSelectConversation(chat)}
                  className="w-full text-left px-3.5 py-2.5 rounded-xl text-xs bg-slate-800/40 hover:bg-slate-800 border border-slate-800/60 hover:border-teal-700/40 text-slate-300 truncate flex items-center gap-2 transition-all"
                >
                  <span className="w-1.5 h-1.5 rounded-full bg-teal-500 flex-shrink-0" />
                  <span className="truncate">{chat.alias}</span>
                </button>
              ))
            ) : (
              <p className="text-xs text-slate-600 px-2 italic">No past conversations</p>
            )}
          </>
        )}
      </div>

      {/* Bottom Actions footer */}
      <div className="p-4 border-t border-slate-800/80 bg-slate-950/30 flex-shrink-0">
        <div className="flex items-center justify-between text-xs text-slate-500 px-2">
          <span className="flex items-center gap-1.5">
            <Compass className="w-3.5 h-3.5 text-teal-500" />
            Bindura University
          </span>
          <span className="text-[10px] bg-slate-800/80 px-2 py-0.5 rounded-full border border-slate-700/60">v2.0</span>
        </div>
      </div>
    </div>
  );
}
