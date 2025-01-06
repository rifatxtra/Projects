import React, { useEffect } from "react";
import { BrowserRouter as Router, Route, Routes, useNavigate } from "react-router-dom";
import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";
import Properties from "./pages/Properties";
import Tenants from "./pages/Tenants";
import PaymentPage from "./pages/PaymentPage";
import PaymentHistoryPage from "./pages/PaymentHistoryPage";
import StatisticsPage from "./pages/StatisticsPage";
import PendingPayment from "./pages/PendingPayment";
import VerifyPayment from "./pages/VerifyPayment";


//importtenants pages
import Profile from "./pages/Profile";
import SupportPage from "./pages/SupportPage";
import Payment from "./pages/Payment";
import PaymentHistory from "./pages/PaymentHistory";
import TenantDashboard from "./pages/TenantDashboard";

// import TenantDashboard from "./pages/TenantDashboard";
// import TenantProperties from "./pages/TenantProperties";
// import TenantPayments from "./pages/TenantPayments";
// import TenantSupport from "./pages/TenantSupport";
import OwnerNavbar from "./components/OwnerNavbar";
import TenantNavbar from "./components/TenantNavbar";

function App() {
  const navigate = useNavigate();

  // Retrieve userRole directly as a string from localStorage
  const userRole = localStorage.getItem('userRole'); // No need to use JSON.parse
  console.log(userRole);

  // If no userRole is found, redirect to Login page
  useEffect(() => {
    if (!userRole) {
      navigate("/"); // Redirect to the login page if no userRole
    }
  }, [userRole, navigate]);

  // If userRole is not available, render Login page
  if (!userRole) {
    return <Login />;
  }

  return (
    <>
      {/* Conditionally render the navbar based on userRole */}
      {userRole === "owner" ? <OwnerNavbar /> : <TenantNavbar />}

      <Routes>
        {/* Login route */}
        <Route path="/" element={<Login />} />
        
        {/* Tenant Routes */}
        {userRole === "tenant" && (
          <>
            {/* Uncomment and update tenant-specific routes */}
            <Route path="/tenant-dashboard" element={<TenantDashboard />} />
            <Route path="/tenant-profile" element={<Profile />} />
            <Route path="/tenant-payments" element={<Payment />} />
            <Route path="/tenant-support" element={<SupportPage />} />
            <Route path="/tenant-payment-history" element={<PaymentHistory />} />
          </>
        )}

        {/* Owner Routes */}
        {userRole === "owner" && (
          <>
            <Route path="/admin/dashboard" element={<Dashboard />} />
            <Route path="/admin/properties" element={<Properties />} />
            <Route path="/admin/tenants" element={<Tenants />} />
            <Route path="/admin/payment-method" element={<PaymentPage />} />
            <Route path="/admin/payment-history" element={<PaymentHistoryPage />} />
            <Route path="/admin/statistics" element={<StatisticsPage />} />
            <Route path="/admin/pending-payment" element={<PendingPayment />} />
            <Route path="/admin/Verify-payment" element={<VerifyPayment />} />
          </>
        )}
      </Routes>
    </>
  );
}

// Wrap the App component in Router
export default function AppWrapper() {
  return (
    <Router>
      <App />
    </Router>
  );
}
