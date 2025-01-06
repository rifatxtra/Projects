import React, { useEffect, useState } from "react";
import api from '../services/api';
import { useNavigate } from "react-router-dom";

const Dashboard = () => {
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState('');
  const [totalProperties, setTotalProperties] = useState(0);
  const [activeTenants, setActiveTenants] = useState(0);
  const [pendingPayments, setPendingPayments] = useState(0);
  const [verifyPayment,setVerifyPayment]=useState(0);

  const navigate = useNavigate();

  useEffect(() => {
    const fetchDashboardData = async () => {
      setIsLoading(true);
      setError('');
      try {
        const [propertiesResponse, tenantsResponse, paymentsResponse, verifypaymentresponse] = await Promise.all([
          api.get('/properties/getproperty'),
          api.get('/tenants'),
          api.get('/fetchPendingPayments'),
          api.get('/verifypayment/getnumber')
        ]);

        // Log paymentsResponse.data to check its structure
        console.log("Payments Response:", paymentsResponse.data);

        if (propertiesResponse.data) {
          console.log('propery',propertiesResponse.data);
          setTotalProperties(propertiesResponse.data.data.length);
        }

        if (tenantsResponse.data) {
          setActiveTenants(tenantsResponse.data.data.length);
        }
        if(verifypaymentresponse.data){
          setVerifyPayment(verifypaymentresponse.data.number);
        }
        if (paymentsResponse.data) {
          // Check the structure of paymentsResponse.data
          if (Array.isArray(paymentsResponse.data)) {
            const pendingAmount = paymentsResponse.data
              .filter(payment => payment.total_pending_amount > 0)  // Only payments with pending amount > 0
              .reduce((total, payment) => total + parseFloat(payment.total_pending_amount), 0);
            setPendingPayments(pendingAmount);
          } else {
            console.error('Payments response is not an array', paymentsResponse.data);
          }
        }
      } catch (error) {
        console.error('Error fetching dashboard data:', error);
        setError('Failed to load dashboard data. Please try again later.');
      } finally {
        setIsLoading(false);
      }
    };

    fetchDashboardData();
  }, []);

  const stats = [
    {
      id: 1,
      title: "Total Properties",
      value: totalProperties,
      icon: "🏠",
      color: "bg-gradient-to-r from-blue-500 to-green-400",
      link: 'admin/properties'
    },
    {
      id: 2,
      title: "Active Tenants",
      value: activeTenants,
      icon: "👥",
      color: "bg-gradient-to-r from-purple-500 to-indigo-400",
      link: 'admin/tenants'
    },
    {
      id: 3,
      title: "Pending Payments",
      value: `TK ${pendingPayments.toFixed(2)}`,
      icon: "💵",
      color: "bg-gradient-to-r from-red-500 to-orange-400",
      link: 'admin/pending-payment'
    },
    {
      id:4,
      title: "Verify Payment",
      icon: "👥",
      value: `${verifyPayment}`,
      color: "bg-gradient-to-r from-blue-500 to-red-400",
      link: 'admin/verify-payment'
    }
  ];

  if (isLoading) {
    return <div className="p-6 text-center">Loading dashboard data...</div>;
  }

  if (error) {
    return <div className="p-6 text-center text-red-500">{error}</div>;
  }

  return (
    <div className="p-6">
      <h1 className="text-3xl font-bold mb-6 text-gray-800 text-center">Dashboard</h1>
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {stats.map((stat) => (
          <div
            onClick={() => navigate(`/${stat.link}`)}
            key={stat.id}
            className={`${stat.color} text-white p-6 rounded-lg shadow-lg flex items-center space-x-4 cursor-pointer`}
          >
            <span className="text-4xl">{stat.icon}</span>
            <div>
              <h2 className="text-lg font-semibold">{stat.title}</h2>
              <p className="text-2xl font-bold">{stat.value}</p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Dashboard;
