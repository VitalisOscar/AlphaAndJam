import React from 'react';
import ReactDOM from 'react-dom';
import Header from '../fragments/Header';
import Sidebar from '../fragments/Sidebar';

function Dashboard() {
    let main_content = <div>
        Dashboard Main
    </div>

    return (
        <main>
            <Header />
            <Sidebar />
            { main_content }
        </main>
    );
}

export default Dashboard;
