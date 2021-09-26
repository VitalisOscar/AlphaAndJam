import React from 'react';
import ReactDOM from 'react-dom';

import AppRouter from './../AppRouter';

function App() {

    return (
        <div><AppRouter /></div>
    );
}

export default App;

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}
