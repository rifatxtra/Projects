import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom'; // Ensure you're using React Router
import api from '../services/api';

const TenantDashboard = () => {
    const [name, setName] = useState('');
    const id = localStorage.getItem('userID');

    useEffect(() => {
        const getData = async () => {
            try {
                const res = await api.get(`tenants/get/${id}`);
                if (res.data.status && res.data.data.length > 0) {
                    setName(res.data.data[0].name); // Accessing the first object in the array
                } else {
                    console.error('No data available or status is false.');
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        getData();
    }, [id]);

    return (
        <div className="min-h-screen bg-gray-100 flex flex-col items-center justify-center">
            <div className="bg-white shadow-md rounded-lg p-8 max-w-lg w-full">
                <h1 className="text-2xl font-bold text-gray-800 mb-4">
                    Hello, <span className="text-blue-500">{name}</span>
                </h1>
                <p className="text-gray-600 mb-6">
                    Welcome to <span className="font-semibold">Home Manager</span>. Please select one of the options below to get started.
                </p>
                <div className="space-y-4">
                    <Link
                        to="/tenant-profile"
                        className="block bg-blue-500 hover:bg-blue-600 text-white font-semibold text-center py-2 px-4 rounded-md"
                    >
                        My Profile
                    </Link>
                    <Link
                        to="/tenant-payments"
                        className="block bg-green-500 hover:bg-green-600 text-white font-semibold text-center py-2 px-4 rounded-md"
                    >
                        Make Payment
                    </Link>
                    <Link
                        to="/tenant-payment-history"
                        className="block bg-indigo-500 hover:bg-indigo-600 text-white font-semibold text-center py-2 px-4 rounded-md"
                    >
                        Payment History
                    </Link>
                    <Link
                        to="/tenant-support"
                        className="block bg-red-500 hover:bg-red-600 text-white font-semibold text-center py-2 px-4 rounded-md"
                    >
                        Support
                    </Link>
                </div>
            </div>
        </div>
    );
};

export default TenantDashboard;
