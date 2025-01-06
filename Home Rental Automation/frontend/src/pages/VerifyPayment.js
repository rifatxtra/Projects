import { act, useEffect, useState } from 'react';
import React from 'react';
import api from '../services/api';

const VerifyPayment = () => {
    const id = localStorage.getItem('userID');
    const [verifyPayment, setVerifyPayment] = useState([]);
    
    const getPayment = async () => {
        const res = await api.get(`/verifypayment/getpayments`);
        console.log(res);
        if (res.data.status) setVerifyPayment(res.data.data);
    };

    useEffect(() => {
        getPayment();
    }, [id]);

    const handleAction = async(paymentId, action) => {
        const res=await api.get(`/verifypayment/${action}/${paymentId}`);
        console.log(res);
        console.log(`Action: ${action}, Payment ID: ${paymentId}`);
    };

    return (
        <div className="p-4">
            <h2 className="text-2xl font-bold mb-4">Verify Payments</h2>
            <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-gray-200">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sl No</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property ID</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flat Name</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="bg-white divide-y divide-gray-200">
                        {verifyPayment.map((payment, index) => (
                            <tr key={payment.id}>
                                <td className="px-6 py-4 whitespace-nowrap">{index + 1}</td>
                                <td className="px-6 py-4 whitespace-nowrap">{payment.name}</td>
                                <td className="px-6 py-4 whitespace-nowrap">{payment.created}</td>
                                <td className="px-6 py-4 whitespace-nowrap">{payment.pm}</td>
                                <td className="px-6 py-4 whitespace-nowrap">{payment.txid}</td>
                                <td className="px-6 py-4 whitespace-nowrap">{payment.amount}</td>
                                <td className="px-6 py-4 whitespace-nowrap">{payment.pid}</td>
                                <td className="px-6 py-4 whitespace-nowrap">{payment.fname}</td>
                                <td className="px-6 py-4 whitespace-nowrap">
                                    <div className="flex space-x-2">
                                        <button
                                            onClick={() => handleAction(payment.id, 'approve')}
                                            className="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Approve
                                        </button>
                                        <button
                                            onClick={() => handleAction(payment.id, 'reject')}
                                            className="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
};

export default VerifyPayment;
