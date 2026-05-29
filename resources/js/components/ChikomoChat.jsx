import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import Sidebar from './Sidebar';
import { Send, Sparkles, SendHorizontal, Users, RefreshCw, Feather, Menu, X, MessageSquare, Download } from 'lucide-react';

export default function ChikomoChat() {
  // Core Identity States
  const [alias, setAlias] = useState('');
  const [token, setToken] = useState(localStorage.getItem('chikomo_token') || '');
  const [currentView, setCurrentView] = useState('ai-chat'); // ai-chat, counselor-chat, stress-modules, peer-stories

  // Responsive Drawer Control Toggle
  const [isDrawerOpen, setIsDrawerOpen] = useState(false);

  // Component Datasets
  const [conversations, setConversations] = useState([]);
  const [messages, setMessages] = useState([]);
  const [inputMessage, setInputMessage] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  // Feature Component Sub-States
  const [modules, setModules] = useState([]);
  const [activeModuleComments, setActiveModuleComments] = useState({}); // Tracks expanding/viewing comment lists per module ID
  const [moduleCommentInputs, setModuleCommentInputs] = useState({}); // Stores text input per module ID
  const [stories, setStories] = useState([]);
  const [storyTitle, setStoryTitle] = useState('');
  const [storyContent, setStoryContent] = useState('');

  // Human Counselor State Trackers
  const [counselorStatus, setCounselorStatus] = useState(localStorage.getItem('counselor_room_status') || 'none'); // none, searching, active, completed
  const [counselorMessages, setCounselorMessages] = useState(JSON.parse(localStorage.getItem('counselor_local_messages')) || []);

  const chatBottomRef = useRef(null);

  // Initialize System Session
  useEffect(() => {
    const initSession = async () => {
      try {
        const response = await axios.post('/api/session/init', {}, {
          headers: { 'X-Chikomo-Token': token }
        });
        const data = response.data;
        setToken(data.token);
        setAlias(data.alias);
        localStorage.setItem('chikomo_token', data.token);

        if (data.status === 'searching' || data.status === 'pending' || data.counselor_id) {
          const matchedStatus = data.counselor_id ? 'active' : 'searching';
          setCounselorStatus(matchedStatus);
          localStorage.setItem('counselor_room_status', matchedStatus);
          if (matchedStatus === 'active') {
            setCurrentView('counselor-chat');
          }
        }

        // Load initial chat history and conversation listings
        fetchConversations(data.token);
        fetchChatHistory(data.token);
      } catch (err) {
        console.error("Session matching initialization mismatch", err);
      }
    };
    initSession();
  }, []);

  // Sync Counselor Local Storage Lists
  useEffect(() => {
    localStorage.setItem('counselor_local_messages', JSON.stringify(counselorMessages));
    localStorage.setItem('counselor_room_status', counselorStatus);
  }, [counselorMessages, counselorStatus]);

  // Handle Poll Tracking Loops for Counselor Assignments
  useEffect(() => {
    let interval = null;
    if (counselorStatus === 'searching' || counselorStatus === 'active') {
      const pollStatus = async () => {
        try {
          const response = await axios.get('/api/counselor/status', {
            headers: { 'X-Chikomo-Token': token }
          });

          const currentStatus = response.data.status;

          if (currentStatus === 'completed') {
            setCounselorStatus('completed');
          } else if (response.data.counselor_id || currentStatus === 'active' || currentStatus === 'accepted') {
            if (counselorStatus !== 'active') {
              setCounselorStatus('active');
              setCurrentView('counselor-chat'); // Instantly shift workspace view frame to counselor window
            }
            fetchCounselorHistory();
          }
        } catch (e) {
          console.error("Polling error", e);
        }
      };

      // Poll instantly on mount/status switch, then drop into loop interval
      pollStatus();
      interval = setInterval(pollStatus, 2500);
    }
    return () => {
      if (interval) clearInterval(interval);
    };
  }, [counselorStatus, token]);

  useEffect(() => {
    chatBottomRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages, counselorMessages, currentView]);

  // Load Content Modules on Tab Click Changes
  useEffect(() => {
    if (currentView === 'stress-modules') fetchStressModules();
    if (currentView === 'peer-stories') fetchPeerStories();
    if (currentView === 'counselor-chat') fetchCounselorHistory();
  }, [currentView]);

  const fetchConversations = async (sessionToken) => {
    try {
      const res = await axios.get('/api/conversations', { headers: { 'X-Chikomo-Token': sessionToken } });
      setConversations(res.data);
    } catch (e) { console.error(e); }
  };

  const fetchChatHistory = async (sessionToken) => {
    try {
      const res = await axios.get(`/api/chat/history?token=${sessionToken}`);
      if (res.data && res.data.messages) {
        setMessages(res.data.messages);
      }
    } catch (e) { console.error(e); }
  };

  const fetchStressModules = async () => {
    try {
      const res = await axios.get('/api/stress-modules');
      setModules(res.data);
    } catch (e) { console.error(e); }
  };

  // Trigger download count increment via API endpoint
  const handleDownloadTrack = async (moduleId) => {
    try {
      await axios.post(`/api/stress-modules/${moduleId}/download`, {}, {
        headers: { 'X-Chikomo-Token': token }
      });
      // Optimistically update count locally to prevent re-fetching jitter
      setModules(prev => prev.map(m => m.id === moduleId ? { ...m, download_count: m.download_count + 1 } : m));
    } catch (e) {
      console.error("Failed to track download action", e);
    }
  };

  // Expand or close a module's comments container
  const toggleCommentsView = (moduleId) => {
    setActiveModuleComments(prev => ({
      ...prev,
      [moduleId]: !prev[moduleId]
    }));
  };

  // Handle local state typing per individual module input line
  const handleCommentInputChange = (moduleId, text) => {
    setModuleCommentInputs(prev => ({
      ...prev,
      [moduleId]: text
    }));
  };

  // Post anonymous module validation feedback or inquiry
  const handlePostModuleComment = async (e, moduleId) => {
    e.preventDefault();
    const commentText = moduleCommentInputs[moduleId];
    if (!commentText || !commentText.trim()) return;

    try {
      const response = await axios.post(`/api/stress-modules/${moduleId}/comments`, {
        comment: commentText
      }, {
        headers: { 'X-Chikomo-Token': token }
      });

      // Clear layout text field input box
      setModuleCommentInputs(prev => ({ ...prev, [moduleId]: '' }));

      // Append new comment onto module structure in context dynamically
      setModules(prev => prev.map(m => {
        if (m.id === moduleId) {
          const currentComments = m.comments || [];
          return {
            ...m,
            comments: [...currentComments, response.data]
          };
        }
        return m;
      }));
    } catch (e) {
      console.error("Failed to submit comment", e);
    }
  };

  const fetchPeerStories = async () => {
    try {
      const res = await axios.get('/api/peer-stories');
      setStories(res.data);
    } catch (e) { console.error(e); }
  };

  const fetchCounselorHistory = async () => {
    try {
      const res = await axios.get('/api/counselor/history', { headers: { 'X-Chikomo-Token': token } });
      if (res.data && res.data.length > 0) {
        setCounselorMessages(res.data);
      }
    } catch (e) { console.error(e); }
  };

  // Submit AI message pipeline stream
  const handleSendAIMessage = async (e) => {
    e.preventDefault();
    if (!inputMessage.trim() || isLoading) return;

    const userMsg = { id: Date.now(), content: inputMessage, sender_type: 'user' };
    setMessages(prev => [...prev, userMsg]);
    const textToSend = inputMessage;
    setInputMessage('');
    setIsLoading(true);

    try {
      const response = await axios.post('/api/chat/send', {
        message: textToSend,
        token: token
      });

      const aiMsg = {
        id: Date.now() + 1,
        content: response.data.content,
        sender_type: response.data.sender_type,
        timestamp: response.data.timestamp
      };

      setMessages(prev => [...prev, aiMsg]);
      fetchConversations(token);
    } catch (err) {
      console.error(err);
    } finally {
      setIsLoading(false);
    }
  };

  // Explicitly trigger human chat queue pipeline instantiations via custom API matching route
  const handleRequestCounselor = async (selectedRisk = 'low') => {
    setIsLoading(true);
    try {
      const response = await axios.post('/api/conversations/create', {
        risk_level: selectedRisk
      }, {
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Chikomo-Token': token
        }
      });

      if (response.data.success) {
        setCounselorStatus('searching');
        setAlias(response.data.conversation.alias);
        localStorage.setItem('counselor_room_status', 'searching');
      } else {
        alert("Failed to initialize connection queue.");
      }
    } catch (e) {
      console.error("Error creating chat request pipeline:", e);
    } finally {
      setIsLoading(false);
    }
  };

  // Submit Live Human Message stream
  const handleSendCounselorMessage = async (e) => {
    e.preventDefault();
    if (!inputMessage.trim()) return;

    const localMsg = { id: Date.now(), content: inputMessage, sender_type: 'user', created_at: 'Just now' };
    setCounselorMessages(prev => [...prev, localMsg]);
    const msgText = inputMessage;
    setInputMessage('');

    try {
      await axios.post('/api/chat/send', {
        message: msgText,
        token: token
      });
      fetchCounselorHistory();
    } catch (err) {
      console.error(err);
    }
  };

  // Submit Peer Story Form
  const handlePostStory = async (e) => {
    e.preventDefault();
    if (!storyTitle.trim() || !storyContent.trim()) return;

    try {
      await axios.post('/api/peer-stories/post', { title: storyTitle, content: storyContent }, {
        headers: { 'X-Chikomo-Token': token }
      });
      setStoryTitle('');
      setStoryContent('');
      fetchPeerStories();
    } catch (e) { console.error(e); }
  };

  // Intercept Navigation Changes to Close Drawer Automatically
  const handleViewChange = (view) => {
    setCurrentView(view);
    setIsDrawerOpen(false);
  };

  return (
    <div className="flex w-screen h-screen bg-slate-950 font-sans overflow-hidden relative">

      {/* 1. DESKTOP VIEW SIDEBAR */}
      <div className="hidden lg:block">
        <Sidebar
          currentView={currentView}
          onViewChange={handleViewChange}
          alias={alias}
          conversations={conversations}
          onSelectConversation={(chat) => {
            setCurrentView('ai-chat');
            fetchChatHistory(chat.token);
          }}
        />
      </div>

      {/* 2. RESPONSIVE MOBILE DRAWER SLIDE-OVER OVERLAY FRAMEWORK */}
      {isDrawerOpen && (
        <div className="fixed inset-0 z-50 flex lg:hidden">
          <div
            className="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity duration-300"
            onClick={() => setIsDrawerOpen(false)}
          />

          <div className="relative flex flex-col w-80 max-w-sm bg-slate-900 h-full shadow-2xl border-r border-slate-800 z-10">
            <div className="absolute top-4 right-4 z-20">
              <button
                onClick={() => setIsDrawerOpen(false)}
                className="p-2 rounded-xl bg-slate-950/40 text-slate-400 hover:text-slate-200 border border-slate-800/80 focus:outline-none"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            <div className="flex-1 h-full overflow-y-auto">
              <Sidebar
                currentView={currentView}
                onViewChange={handleViewChange}
                alias={alias}
                conversations={conversations}
                onSelectConversation={(chat) => {
                  setCurrentView('ai-chat');
                  setIsDrawerOpen(false);
                  fetchChatHistory(chat.token);
                }}
              />
            </div>
          </div>
        </div>
      )}

      {/* Main Context Dynamic Console Layout Space */}
      <div className="flex-1 flex flex-col h-full bg-slate-900/50 min-w-0">

        {/* VIEWPORTS A: CHIKOMO AI VIEW PANEL */}
        {currentView === 'ai-chat' && (
          <div className="flex-1 flex flex-col h-full relative">
            <header className="p-4 border-b border-slate-800 bg-slate-900 flex items-center justify-between">
              <div className="flex items-center space-x-3 min-w-0">
                <button
                  onClick={() => setIsDrawerOpen(true)}
                  className="lg:hidden p-2 rounded-xl bg-slate-950/40 text-slate-400 hover:text-slate-200 border border-slate-800/80 focus:outline-none transition-all"
                >
                  <Menu className="w-5 h-5" />
                </button>
                <div className="flex items-center space-x-2 truncate">
                  <Sparkles className="w-5 h-5 text-teal-400 flex-shrink-0 animate-pulse" />
                  <h2 className="font-semibold text-slate-100 truncate">Chikomo Conversational AI</h2>
                </div>
              </div>
              <span className="text-xs bg-slate-800 text-slate-400 px-2.5 py-1 rounded-full border border-slate-700 flex-shrink-0">Encrypted</span>
            </header>

            <div className="flex-1 overflow-y-auto p-6 space-y-4">
              {messages.map((msg, index) => (
                <div key={msg.id || index} className={`flex ${msg.sender_type === 'user' ? 'justify-end' : 'justify-start'}`}>
                  <div className={`max-w-xl rounded-2xl px-4 py-3 text-sm leading-relaxed shadow-md ${
                    msg.sender_type === 'user' ? 'bg-teal-600 text-white rounded-br-none' : 'bg-slate-800 text-slate-200 rounded-bl-none border border-slate-700/50'
                  }`}>
                    {msg.content}
                  </div>
                </div>
              ))}
              {isLoading && (
                <div className="flex justify-start">
                  <div className="bg-slate-800 text-slate-400 rounded-2xl rounded-bl-none border border-slate-700/50 px-4 py-3 text-sm flex items-center space-x-2">
                    <RefreshCw className="w-4 h-4 animate-spin text-teal-400" />
                    <span>Processing reflections...</span>
                  </div>
                </div>
              )}
              <div ref={chatBottomRef} />
            </div>

            <form onSubmit={handleSendAIMessage} className="p-4 bg-slate-900 border-t border-slate-800 flex space-x-3">
              <input
                type="text"
                value={inputMessage}
                onChange={(e) => setInputMessage(e.target.value)}
                placeholder="Type any thoughts anonymously..."
                className="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-teal-500 transition-all"
              />
              <button type="submit" className="bg-teal-600 hover:bg-teal-500 text-white rounded-xl px-5 flex items-center justify-center shadow-lg shadow-teal-600/15 transition-all flex-shrink-0">
                <SendHorizontal className="w-5 h-5" />
              </button>
            </form>
          </div>
        )}

        {/* VIEWPORTS B: LIVE HUMAN COUNSELOR INTERVENTION PANEL */}
        {currentView === 'counselor-chat' && (
          <div className="flex-1 flex flex-col h-full">
            <header className="p-4 border-b border-slate-800 bg-slate-900 flex items-center space-x-3">
              <button
                onClick={() => setIsDrawerOpen(true)}
                className="lg:hidden p-2 rounded-xl bg-slate-950/40 text-slate-400 hover:text-slate-200 border border-slate-800/80 focus:outline-none transition-all"
              >
                <Menu className="w-5 h-5" />
              </button>
              <div className="flex items-center space-x-2 min-w-0">
                <Users className="w-5 h-5 text-teal-400 flex-shrink-0" />
                <h2 className="font-semibold text-slate-100 truncate">Campus Counselor Hotline</h2>
              </div>
            </header>

            {counselorStatus === 'none' && (
              <div className="flex-1 flex flex-col items-center justify-center p-8 text-center space-y-4">
                <div className="w-16 h-16 rounded-full bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400">
                  <Users className="w-8 h-8" />
                </div>
                <div className="max-w-sm">
                  <h3 className="text-lg font-bold text-slate-200">Connect with a Real Person</h3>
                  <p className="text-sm text-slate-400 mt-1">If you need targeted human guidance or formal assistance, request an allocation handshake with an active on-campus professional.</p>
                </div>
                <button
                  onClick={() => handleRequestCounselor('low')}
                  className="bg-teal-600 hover:bg-teal-500 text-white px-6 py-2.5 rounded-xl font-medium text-sm transition-all shadow-lg shadow-teal-600/20"
                >
                  Establish Connection Request
                </button>
              </div>
            )}

            {counselorStatus === 'searching' && (
              <div className="flex-1 flex flex-col items-center justify-center p-8 text-center space-y-4">
                <RefreshCw className="w-10 h-10 animate-spin text-teal-400" />
                <div>
                  <h3 className="text-md font-semibold text-slate-200">Placing Request in Active Queue...</h3>
                  <p className="text-xs text-slate-500 mt-1">Anonymity metrics remain secured. A professional will handle your chat shortly.</p>
                </div>
              </div>
            )}

            {(counselorStatus === 'active' || counselorStatus === 'completed') && (
              <div className="flex-1 flex flex-col h-full overflow-hidden">
                <div className="flex-1 overflow-y-auto p-6 space-y-4">
                  {counselorMessages.map((msg, index) => (
                    <div key={msg.id || index} className={`flex ${msg.sender_type === 'user' ? 'justify-end' : 'justify-start'}`}>
                      <div className={`max-w-xl rounded-2xl px-4 py-3 text-sm ${
                        msg.sender_type === 'user' ? 'bg-teal-600 text-white rounded-br-none' : 'bg-indigo-900/60 text-indigo-100 border border-indigo-500/20 rounded-bl-none'
                      }`}>
                        {msg.content}
                      </div>
                    </div>
                  ))}
                  <div ref={chatBottomRef} />
                </div>

                {counselorStatus === 'completed' ? (
                  <div className="p-4 bg-slate-950/40 text-center text-xs text-slate-500 border-t border-slate-800">
                    This live guidance stream has been successfully concluded by the attending specialist.
                  </div>
                ) : (
                  <form onSubmit={handleSendCounselorMessage} className="p-4 bg-slate-900 border-t border-slate-800 flex space-x-3">
                    <input
                      type="text"
                      value={inputMessage}
                      onChange={(e) => setInputMessage(e.target.value)}
                      placeholder="Type a response to the active counselor..."
                      className="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-teal-500"
                    />
                    <button type="submit" className="bg-teal-600 text-white rounded-xl px-5 flex items-center justify-center flex-shrink-0">
                      <Send className="w-4 h-4" />
                    </button>
                  </form>
                )}
              </div>
            )}
          </div>
        )}

        {/* VIEWPORTS C: STRESS MODULES PANEL */}
        {currentView === 'stress-modules' && (
          <div className="flex-1 flex flex-col h-full overflow-y-auto">
            <header className="p-4 border-b border-slate-800 bg-slate-900 flex items-center lg:hidden">
              <button
                onClick={() => setIsDrawerOpen(true)}
                className="p-2 rounded-xl bg-slate-950/40 text-slate-400 hover:text-slate-200 border border-slate-800/80 focus:outline-none transition-all"
              >
                <Menu className="w-5 h-5" />
              </button>
            </header>

            <div className="p-6">
              <div className="mb-6">
                <h2 className="text-xl font-bold text-slate-100">Stress Reduction Repository</h2>
                <p className="text-sm text-slate-400 mt-1">Access modular guidelines and resource attachments uploaded by platform specialists.</p>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {modules.length > 0 ? (
                  modules.map((m) => (
                    <div key={m.id} className="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between hover:border-slate-700 transition-all h-fit space-y-4">
                      <div>
                        <div className="bg-teal-500/10 border border-teal-500/20 w-10 h-10 rounded-xl flex items-center justify-center text-teal-400 mb-3 font-bold">
                          {m.title[0]}
                        </div>
                        <h4 className="font-semibold text-slate-200 text-base">{m.title}</h4>
                        <p className="text-xs text-slate-400 mt-2 leading-relaxed">{m.description}</p>
                      </div>

                      {/* Download Link Wrapper and Metadata Metrics */}
                      <div className="pt-3 border-t border-slate-800/60 flex items-center justify-between text-xs text-slate-500">
                        <span className="flex items-center space-x-1">
                          <Download className="w-3.5 h-3.5 text-slate-600" />
                          <span>Downloads: <strong>{m.download_count}</strong></span>
                        </span>
                        <a
                          href={`/${m.file_path}`}
                          download
                          onClick={() => handleDownloadTrack(m.id)}
                          className="text-teal-400 hover:underline font-medium flex items-center space-x-1"
                        >
                          <span>Download Resource</span>
                          <span>&rarr;</span>
                        </a>
                      </div>

                      {/* Interactive Feedback & Comment Systems Block */}
                      <div className="border-t border-slate-800/80 pt-3">
                        <button
                          onClick={() => toggleCommentsView(m.id)}
                          className="flex items-center space-x-1.5 text-xs text-slate-400 hover:text-slate-200 transition-colors focus:outline-none"
                        >
                          <MessageSquare className="w-3.5 h-3.5 text-teal-500" />
                          <span>Feedback ({m.comments ? m.comments.length : 0})</span>
                        </button>

                        {/* Expandable Comments Drawer Area */}
                        {activeModuleComments[m.id] && (
                          <div className="mt-3 space-y-3 bg-slate-950/50 p-3 rounded-xl border border-slate-800">
                            {/* Inner Feedback Content Feed */}
                            <div className="max-h-40 overflow-y-auto space-y-2 pr-1 custom-scrollbar">
                              {m.comments && m.comments.length > 0 ? (
                                m.comments.map((comment, cIdx) => (
                                  <div key={comment.id || cIdx} className="text-xs border-b border-slate-900/60 pb-2 last:border-0 last:pb-0">
                                    <div className="flex justify-between text-slate-500 mb-0.5 font-mono text-[10px]">
                                      <span>{comment.author_alias || 'Anonymous Peer'}</span>
                                      <span>{comment.created_at || 'Just now'}</span>
                                    </div>
                                    <p className="text-slate-300 leading-normal">{comment.content || comment.comment}</p>
                                  </div>
                                ))
                              ) : (
                                <p className="text-[11px] text-slate-600 italic py-1">No comments submitted yet.</p>
                              )}
                            </div>

                            {/* Comment Input Field */}
                            <form onSubmit={(e) => handlePostModuleComment(e, m.id)} className="flex space-x-2 pt-1">
                              <input
                                type="text"
                                placeholder="Write response anonymously..."
                                value={moduleCommentInputs[m.id] || ''}
                                onChange={(e) => handleCommentInputChange(m.id, e.target.value)}
                                className="flex-1 bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-[11px] text-slate-300 placeholder-slate-600 focus:outline-none focus:border-teal-500 transition-colors"
                              />
                              <button
                                type="submit"
                                className="bg-teal-600 hover:bg-teal-500 text-white text-[11px] font-medium px-3 rounded-lg transition-colors flex items-center"
                              >
                                Send
                              </button>
                            </form>
                          </div>
                        )}
                      </div>
                    </div>
                  ))
                ) : (
                  <div className="col-span-2 text-center p-12 text-slate-500 border border-dashed border-slate-800 rounded-2xl">
                    No managed files uploaded yet. Check back later.
                  </div>
                )}
              </div>
            </div>
          </div>
        )}

        {/* VIEWPORTS D: PEER STORIES FORUM PANEL */}
        {currentView === 'peer-stories' && (
          <div className="flex-1 flex flex-col h-full overflow-y-auto">
            <header className="p-4 border-b border-slate-800 bg-slate-900 flex items-center lg:hidden">
              <button
                onClick={() => setIsDrawerOpen(true)}
                className="p-2 rounded-xl bg-slate-950/40 text-slate-400 hover:text-slate-200 border border-slate-800/80 focus:outline-none transition-all"
              >
                <Menu className="w-5 h-5" />
              </button>
            </header>

            <div className="p-6 space-y-8">
              <div>
                <h2 className="text-xl font-bold text-slate-100">Shared Journeys & Anecdotes</h2>
                <p className="text-sm text-slate-400 mt-1">Read accounts posted anonymously by student peers, or share your own thoughts without revealing your identity.</p>
              </div>

              {/* Sharing Input Card Block */}
              <form onSubmit={handlePostStory} className="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-4 shadow-xl shadow-black/10">
                <div className="flex items-center space-x-2 text-teal-400 font-medium text-sm">
                  <Feather className="w-4 h-4" />
                  <span>Express Your Experience Anonymously</span>
                </div>
                <div className="space-y-3">
                  <input
                    type="text"
                    value={storyTitle}
                    onChange={(e) => setStoryTitle(e.target.value)}
                    placeholder="Give your story a clear theme title..."
                    className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-teal-500"
                  />
                  <textarea
                    value={storyContent}
                    onChange={(e) => setStoryContent(e.target.value)}
                    placeholder="Share details safely. No personal names, addresses, or identifiers are logged..."
                    rows="3"
                    className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-teal-500 resize-none"
                  />
                </div>
                <div className="flex justify-end">
                  <button type="submit" className="bg-teal-600 hover:bg-teal-500 text-white px-5 py-2 rounded-xl text-xs font-semibold transition-all">
                    Post Story to Forum
                  </button>
                </div>
              </form>

              {/* Forum Stream */}
              <div className="space-y-4">
                <h3 className="text-sm font-semibold text-slate-400 uppercase tracking-wider">Community Submissions</h3>
                {stories.length > 0 ? (
                  stories.map((story) => (
                    <div key={story.id} className="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5 space-y-3">
                      <div className="flex items-center justify-between">
                        <span className="text-xs bg-slate-800 text-slate-400 px-2.5 py-1 rounded-md border border-slate-700/60 font-medium">By: {story.author_alias}</span>
                      </div>
                      <h4 className="font-bold text-slate-200 text-base">{story.title}</h4>
                      <p className="text-sm text-slate-400 leading-relaxed whitespace-pre-wrap">{story.content}</p>
                    </div>
                  ))
                ) : (
                  <p className="text-sm text-slate-500 italic text-center py-6">No anonymous peer accounts posted yet.</p>
                )}
              </div>
            </div>
          </div>
        )}

      </div>
    </div>
  );
}
