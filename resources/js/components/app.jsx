import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Navbar from './layout/Navbar';
import EventList from './events/EventList';
import EventDetail from './events/EventDetail';
import LoginForm from './auth/LoginForm';
import ProfileView from './profile/ProfileView';
import PaymentProcess from './payment/PaymentProcess';

const App = () => {
  return (
    <Router>
      <Navbar />
      <main className="container py-4">
        <Routes>
          <Route path="/" element={<EventList />} />
          <Route path="/events" element={<EventList />} />
          <Route path="/events/:id" element={<EventDetail />} />
          <Route path="/login" element={<LoginForm />} />
          <Route path="/profile" element={<ProfileView />} />
          <Route path="/payment/:registration" element={<PaymentProcess />} />
        </Routes>
      </main>
    </Router>
  );
};

export default App;