import React, { useState } from "react";
import { Link, useNavigate } from "react-router-dom";

const TenantNavbar = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const navigate = useNavigate(); // To navigate after logout

  const toggleMenu = () => {
    setIsMenuOpen(!isMenuOpen);
  };

  const handleLogout = () => {
    // Remove user role from localStorage
    localStorage.removeItem('userRole');
    // Redirect to the login page
    navigate('/');
  };

  return (
    <nav className="bg-blue-800 p-4">
      <div className="max-w-7xl mx-auto flex justify-between items-center">
        {/* Logo Section */}
        <div className="text-white font-bold text-xl">
          <a href="#">Tenant Dashboard</a>
        </div>

        {/* Navbar Links for Desktop */}
        <div className="hidden md:flex space-x-6">
          <Link to="/tenant-dashboard" className="text-white hover:bg-blue-600 px-3 py-2 rounded-md">Dashboard</Link>
          <Link to="/tenant-profile" className="text-white hover:bg-blue-600 px-3 py-2 rounded-md">My Profile</Link>
          <Link to="/tenant-payments" className="text-white hover:bg-blue-600 px-3 py-2 rounded-md">Make Payment</Link>
          <Link to="/tenant-payment-history" className="text-white hover:bg-blue-600 px-3 py-2 rounded-md">Payment History</Link>
          <Link to="/tenant-support" className="text-white hover:bg-blue-600 px-3 py-2 rounded-md">Support</Link>
          {/* Logout Button */}
          <button 
            onClick={handleLogout} 
            className="text-white hover:bg-blue-600 px-3 py-2 rounded-md">
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
      <div className={`${isMenuOpen ? 'block' : 'hidden'} md:hidden bg-blue-800 p-4`}>
        <Link to="/tenant-dashboard" className="block text-white hover:bg-blue-600 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>Dashboard</Link>
        <Link to="/tenant-profile" className="block text-white hover:bg-blue-600 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>My Profile</Link>
        <Link to="/tenant-payments" className="block text-white hover:bg-blue-600 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>Make Payment</Link>
        <Link to="/tenant-payment-history" className="block text-white hover:bg-blue-600 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>Payment History</Link>
        <Link to="/tenant-support" className="block text-white hover:bg-blue-600 px-3 py-2 rounded-md" onClick={() => setIsMenuOpen(false)}>Support</Link>
        {/* Mobile Logout Button */}
        <button 
          onClick={handleLogout} 
          className="block text-white hover:bg-blue-600 px-3 py-2 rounded-md mt-2">
          Logout
        </button>
      </div>
    </nav>
  );
};

export default TenantNavbar;
