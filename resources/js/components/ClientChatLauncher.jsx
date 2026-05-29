import React, { useState, useEffect } from 'react';

function ClientChatLauncher() {
    const [loading, setLoading] = useState(false);
    const [activeChat, setActiveChat] = useState(null);
    const [isCounselorConnected, setIsCounselorConnected] = useState(false);

    // Track status when joined to queue
    useEffect(() => {
        let intervalId;

        if (activeChat && !isCounselorConnected) {
            // Poll the backend periodically to see if a counselor has assigned themselves
            intervalId = setInterval(async () => {
                try {
                    const response = await fetch(`/api/conversations/status/${activeChat.token}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                        }
                    });
                    const data = await response.json();

                    if (data.success && data.status === 'active') {
                        setIsCounselorConnected(true);
                        clearInterval(intervalId);
                        // Redirect or initialize full interactive chat pane view here
                        console.log("Counselor connected to session!");
                    }
                } catch (error) {
                    console.error("Error verification polling:", error);
                }
            }, 5000); // Poll every 5 seconds
        }

        return () => {
            if (intervalId) clearInterval(intervalId);
        };
    }, [activeChat, isCounselorConnected]);

    const handleRequestChat = async (selectedRisk = 'low') => {
        setLoading(true);
        try {
            const response = await fetch('/api/conversations/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    risk_level: selectedRisk
                })
            });

            const data = await response.json();

            if (data.success) {
                setActiveChat(data.conversation);
                setIsCounselorConnected(false);
                console.log("Chat entry initialized! Waiting for counselor to accept tracking code: ", data.conversation.token);
            } else {
                alert("Failed to initialize connection queue.");
            }
        } catch (error) {
            console.error("Error creating chat request pipeline:", error);
            alert("An error occurred. Please check network configurations.");
        } finally {
            setLoading(false);
        }
    };

    const handleCancelQueue = () => {
        if (window.confirm("Are you sure you want to leave the waiting queue?")) {
            setActiveChat(null);
            setIsCounselorConnected(false);
        }
    };

    return (
        <div className="w-full max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md border border-gray-100 text-center">
            {!activeChat ? (
                <div>
                    <h3 className="text-2xl font-bold text-gray-800 mb-3">Need someone to talk to?</h3>
                    <p className="text-sm text-gray-600 mb-6 leading-relaxed">
                        Clicking the button below assigns an anonymous placeholder profile and places your request live onto our active intake queue.
                    </p>

                    <div className="space-y-3">
                        <button
                            onClick={() => handleRequestChat('low')}
                            disabled={loading}
                            className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-md shadow-sm transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {loading ? 'Initializing Queue Placement...' : 'Connect to a Live Counselor Now'}
                        </button>
                    </div>
                </div>
            ) : (
                <div className="text-left space-y-4">
                    {!isCounselorConnected ? (
                        <div className="bg-blue-50 border border-blue-200 p-5 rounded-lg">
                            <div className="flex items-center justify-between mb-3">
                                <h4 className="text-lg font-bold text-blue-900">Connected to Queue</h4>
                                <span className="flex h-3 w-3 relative">
                                    <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                    <span className="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                                </span>
                            </div>

                            <div className="space-y-2 text-sm text-gray-700 border-b border-blue-100 pb-3 mb-3">
                                <p>Your Anonymous Alias: <strong className="text-gray-900">{activeChat.alias}</strong></p>
                                <p className="text-xs text-gray-500 font-mono overflow-x-auto whitespace-nowrap bg-white p-1.5 rounded border border-gray-200">
                                    Hash: {activeChat.token}
                                </p>
                            </div>

                            <div className="flex items-start text-sm text-gray-600">
                                <svg className="animate-spin h-5 w-5 mr-3 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                <p className="leading-tight">
                                    Waiting for an available counselor terminal connection to accept and claim your session room request...
                                </p>
                            </div>
                        </div>
                    ) : (
                        <div className="bg-green-50 border border-green-200 p-5 rounded-lg">
                            <h4 className="text-lg font-bold text-green-900 mb-2">Counselor Connected!</h4>
                            <p className="text-sm text-gray-700 mb-4">
                                A live professional has requested configuration parameters for your connection room. Handshaking complete.
                            </p>
                            <button
                                className="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-md transition-colors"
                                onClick={() => window.location.reload()}
                            >
                                Open Interactive Room
                            </button>
                        </div>
                    )}

                    {!isCounselorConnected && (
                        <button
                            onClick={handleCancelQueue}
                            className="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-md text-sm transition-colors duration-200"
                        >
                            Cancel Request & Leave Queue
                        </button>
                    )}
                </div>
            )}
        </div>
    );
}

export default ClientChatLauncher;
