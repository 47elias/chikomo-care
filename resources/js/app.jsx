import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom/client';
import ChikomoChat from './components/ChikomoChat';

// Initialize the root container and render the main interactive layout
const rootElement = document.getElementById('root');

if (rootElement) {
    const root = ReactDOM.createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <div className="min-h-screen bg-gray-50 text-gray-900 antialiased">
                <ChikomoChat />
            </div>
        </React.StrictMode>
    );
} else {
    console.error("Root element selector '#root' was not found in the DOM hierarchy execution layer.");
}
