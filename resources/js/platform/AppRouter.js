import React from 'react';
import { BrowserRouter, Route } from 'react-router-dom'
import CreateAd from './components/ads/CreateAd';
import ListAds from './components/ads/ListAds';
import Dashboard from './components/Dashboard';

const AppRouter = () => (
    <BrowserRouter>
      <div>
        <Route exact path="/app/dashboard" component={Dashboard}/>
        <Route exact path="/app/ads" component={ListAds}/>
        <Route exact path="/app/ads/create" component={CreateAd}/>
      </div>
    </BrowserRouter>
);

export default AppRouter;
