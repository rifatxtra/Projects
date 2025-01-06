import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';

const OwnerNavbar = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const navigate = useNavigate(); // To navigate after logout

  const toggleMenu = () => {
    setIsMenuOpen(!isMenuOpen);
  };

  const handleLogout = () => {
    // Perform logout action (e.g., clear localStorage or sessionStorage)
    localStorage.removeItem('userRole');
    // Redirect to the login page
    navigate('/login');
  };

  return (
    <nav className="bg-blue-600 p-4">
      <div className="max-w-7xl mx-auto flex justify-between items-center">
        {/* Logo Section */}
        <div className="text-white font-bold text-xl">
          <a href="#">Owner Dashboard</a>
        </div>

        {/* Navbar Links for Desktop */}
        <div className="hidden md:flex space-x-6">
          <Link to="/admin/dashboard" className="text-white hover:bg-blue-500 px-3 py-2 rounded-md">Dashboard</Link>
          <Link to="/admin/tenants" className="text-white hover:bg-blue-500 px-3 py-2 rounded-md">Tenants</Link>
          <Link to="/admin/statistics" className="text-white hover:bg-blue-500 px-3 py-2 rounded-md">Statistics</Link>
          <Link to="/admin/payment-method" className="text-white hover:bg-blue-500 px-3 py-2 rounded-md">Payment Method</Link>
          <Link to="/admin/pending-payment" className="text-white hover:bg-blue-500 px-3 py-2 rounded-md">Pending Payments</Link>
          <Link to="/admin/Verify-payment" className="text-white hover:bg-blue-500 px-3 py-2 rounded-md">Verify Payments</Link>
          <Link to="/admin/payment-history" className="text-white hover:bg-blue-500 px-3 py-2 rounded-md">Payment History</Link>
          <Link to="/admin/properties" className="text-white hover:bg-blue-500 px-3 py-2 rounded-md">Properties</Link>
          {/* Logout Link */}
          <button onClick={handleLogout} className="text-white hover:bg-blue-500 px-3 py-2 rounded-md">
            Logout
          </button>
        </div>

        {/* Hamburger Menu for Mobile */}
        <div className="md:hidden flex items-center">
          <button onClick={toggleMenu} className="text-white focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" className="w-6 h-6">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>

      {/* Mobile Menu */}
      <div className={`${isMenuOpen ? 'block' : 'hidden'} md:hidden bg-blue-600 p-4`}>
        <Link to="/admin/dashboard" className="block text-white hover:bg-blue-500 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>Dashboard</Link>
        <Link to="/admin/tenants" className="block text-white hover:bg-blue-500 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>Tenants</Link>
        <Link to="/admin/statistics" className="block text-white hover:bg-blue-500 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>Statistics</Link>
        <Link to="/admin/payment-method" className="block text-white hover:bg-blue-500 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>Payment Method</Link>
        <Link to="/admin/pending-payment" className="block text-white hover:bg-blue-500 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>Pending Payments</Link>
        <Link to="/admin/Verify-payment" className="block text-white hover:bg-blue-500 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>Verify Payments</Link>
        <Link to="/admin/payment-history" className="block text-white hover:bg-blue-500 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>Payment History</Link>
        <Link to="/admin/properties" className="block text-white hover:bg-blue-500 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>Properties</Link>
        {/* Mobile Logout Button */}
        <button 
          onClick={handleLogout} 
          className="block text-white hover:bg-blue-500 px-3 py-2 rounded-md mt-2">
          Logout
        </button>
      </div>
    </nav>
  );
};

export default OwnerNavbar;
