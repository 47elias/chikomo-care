import React from 'react';
import { MessageSquare, Library, BookOpen, Users, Compass, LogOut } from 'lucide-react';

export default function Sidebar({ currentView, onViewChange, alias, conversations, onSelectConversation }) {
  return (
    <div className="w-80 h-screen bg-slate-900 border-r border-slate-800 flex flex-col text-slate-200">
      {/* Profile/Identity Card */}
      <div className="p-6 border-b border-slate-800 bg-slate-950/40">
        <div className="flex items-center space-x-3">
          <div className="w-10 h-10 rounded-xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400 font-bold">
            {alias ? alias[0] : 'C'}
          </div>
          <div>
            <p className="text-xs text-slate-400 font-medium">Anonymous Identity</p>
            <h4 className="text-sm font-semibold text-teal-400 truncate w-48">{alias || 'Connecting...'}</h4>
          </div>
        </div>
      </div>

      {/* Main Feature View Navigation Navigation */}
      <div className="p-4 space-y-1">
        <button
          onClick={() => onViewChange('ai-chat')}
          className={`w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all ${
            currentView === 'ai-chat' ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200'
          }`}
        >
          <MessageSquare className="w-5 h-5" />
          <span>Chikomo AI Guidance</span>
        </button>

        <button
          onClick={() => onViewChange('counselor-chat')}
          className={`w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all ${
            currentView === 'counselor-chat' ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200'
          }`}
        >
          <Users className="w-5 h-5" />
          <span>Live Counselor Desk</span>
        </button>

        <button
          onClick={() => onViewChange('stress-modules')}
          className={`w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all ${
            currentView === 'stress-modules' ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200'
          }`}
        >
          <Library className="w-5 h-5" />
          <span>Stress Modules</span>
        </button>

        <button
          onClick={() => onViewChange('peer-stories')}
          className={`w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all ${
            currentView === 'peer-stories' ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200'
          }`}
        >
          <BookOpen className="w-5 h-5" />
          <span>Peer Stories Hub</span>
        </button>
      </div>

      <div className="border-t border-slate-800 my-2 mx-4"></div>

      {/* Dynamic Context History List Block (Visible during AI Chat context view modes) */}
      <div className="flex-1 overflow-y-auto px-4 py-2 space-y-2">
        {currentView === 'ai-chat' && (
          <>
            <p className="text-xs font-semibold text-slate-500 uppercase tracking-wider px-2 mb-2">AI Conversations</p>
            {conversations && conversations.length > 0 ? (
              conversations.map((chat) => (
                <button
                  key={chat.id}
                  onClick={() => onSelectConversation(chat)}
                  className="w-full text-left px-3 py-2.5 rounded-lg text-xs bg-slate-800/40 hover:bg-slate-800 border border-slate-800/60 text-slate-300 truncate block transition-all"
                >
                  {chat.alias}
                </button>
              ))
            ) : (
              <p className="text-xs text-slate-600 px-2 italic">No past conversations</p>
            )}
          </>
        )}
      </div>

      {/* Bottom Actions footer */}
      <div className="p-4 border-t border-slate-800 bg-slate-950/20">
        <div className="flex items-center justify-between text-xs text-slate-500 px-2">
          <span className="flex items-center"><Compass className="w-3.5 h-3.5 mr-1 text-teal-500" /> Bindura University</span>
          <span>v2.0</span>
        </div>
      </div>
    </div>
  );
}
