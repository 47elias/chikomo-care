import React, { useState } from 'react';
import {
  Wind,
  Brain,
  CloudRain,
  Play,
  CheckCircle,
  Clock,
  ArrowLeft,
  ChevronRight
} from 'lucide-react';

const StressModules = ({ onBack }) => {
  const [selectedCategory, setSelectedCategory] = useState('all');

  const modules = [
    {
      id: 1,
      title: "Box Breathing",
      description: "A simple technique to reset your nervous system in under 2 minutes.",
      duration: "2 min",
      category: "breathing",
      icon: <Wind className="text-blue-500" />,
      color: "bg-blue-50",
      borderColor: "border-blue-100"
    },
    {
      id: 2,
      title: "Mindful Scanning",
      description: "Identify where you are holding tension and release it physically.",
      duration: "5 min",
      category: "meditation",
      icon: <Brain className="text-indigo-500" />,
      color: "bg-indigo-50",
      borderColor: "border-indigo-100"
    },
    {
      id: 3,
      title: "Grounding (5-4-3-2-1)",
      description: "Connect with your surroundings when feeling overwhelmed or anxious.",
      duration: "3 min",
      category: "anxiety",
      icon: <CloudRain className="text-rose-500" />,
      color: "bg-rose-50",
      borderColor: "border-rose-100"
    }
  ];

  const categories = [
    { id: 'all', label: 'All Modules' },
    { id: 'breathing', label: 'Breathing' },
    { id: 'meditation', label: 'Meditation' },
    { id: 'anxiety', label: 'Quick Relief' }
  ];

  const filteredModules = selectedCategory === 'all'
    ? modules
    : modules.filter(m => m.category === selectedCategory);

  return (
    <div className="max-w-4xl mx-auto p-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
      {/* Header */}
      <div className="flex items-center justify-between mb-8">
        <div>
          <button
            onClick={onBack}
            className="flex items-center gap-2 text-slate-400 hover:text-indigo-600 transition-colors text-xs font-bold uppercase tracking-widest mb-2"
          >
            <ArrowLeft size={14} /> Back to Hub
          </button>
          <h1 className="text-2xl font-black text-slate-800 tracking-tight">Stress Relief Modules</h1>
          <p className="text-slate-500 text-sm">Select a quick exercise to help manage your current state.</p>
        </div>
      </div>

      {/* Category Pills */}
      <div className="flex gap-2 mb-8 overflow-x-auto pb-2 no-scrollbar">
        {categories.map((cat) => (
          <button
            key={cat.id}
            onClick={() => setSelectedCategory(cat.id)}
            className={`px-4 py-2 rounded-full text-[11px] font-black transition-all whitespace-nowrap ${
              selectedCategory === cat.id
                ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 scale-105'
                : 'bg-white border border-slate-200 text-slate-500 hover:border-indigo-200'
            }`}
          >
            {cat.label.toUpperCase()}
          </button>
        ))}
      </div>

      {/* Module Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        {filteredModules.map((module) => (
          <button
            key={module.id}
            className={`flex flex-col p-5 rounded-3xl border-2 ${module.borderColor} ${module.color} hover:shadow-xl hover:shadow-slate-200/50 transition-all text-left group relative overflow-hidden`}
          >
            <div className="flex items-start justify-between mb-4">
              <div className="p-3 bg-white rounded-2xl shadow-sm group-hover:scale-110 transition-transform">
                {module.icon}
              </div>
              <div className="flex items-center gap-1.5 bg-white/60 backdrop-blur-sm px-2.5 py-1 rounded-full border border-white">
                <Clock size={10} className="text-slate-400" />
                <span className="text-[10px] font-bold text-slate-600">{module.duration}</span>
              </div>
            </div>

            <div className="relative z-10">
              <h3 className="text-sm font-black text-slate-800 mb-1">{module.title}</h3>
              <p className="text-[11px] text-slate-600 leading-relaxed mb-4 line-clamp-2">
                {module.description}
              </p>

              <div className="flex items-center justify-between">
                <div className="flex items-center gap-2 text-[10px] font-black text-indigo-600 uppercase tracking-tighter">
                  Start Session <Play size={10} fill="currentColor" />
                </div>
                <ChevronRight size={14} className="text-slate-300 group-hover:translate-x-1 transition-transform" />
              </div>
            </div>

            {/* Decorative background element */}
            <div className="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-opacity">
              {React.cloneElement(module.icon, { size: 80 })}
            </div>
          </button>
        ))}

        {/* Community Suggestion Card */}
        <div className="flex flex-col p-5 rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50/50 justify-center items-center text-center">
          <div className="p-3 bg-white rounded-full mb-3 text-slate-300">
            <CheckCircle size={24} />
          </div>
          <p className="text-[11px] font-bold text-slate-500 uppercase tracking-widest">More coming soon</p>
          <p className="text-[10px] text-slate-400 mt-1">New modules added weekly based on student feedback.</p>
        </div>
      </div>
    </div>
  );
};

export default StressModules;
