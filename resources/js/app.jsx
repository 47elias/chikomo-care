import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom/client';
import ChikomoChat from './components/ChikomoChat';

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
    <React.StrictMode>
        <ChikomoChat />
    </React.StrictMode>
);
