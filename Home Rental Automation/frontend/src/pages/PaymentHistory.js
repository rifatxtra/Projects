import React, { useState, useEffect } from 'react';
import api from '../services/api';

const PaymentHistory = () => {
    const [transactions, setTransactions] = useState([]);
    const id = localStorage.getItem('userID');

    useEffect(() => {
        const fetchTransactions = async () => {
            try {
                const response = await api.get(`/payments/${id}`);
                if (response.data.status) {
                    setTransactions(response.data.data);
                } else {
                    console.error('Error fetching transactions:', response.data.error);
                }
            } catch (error) {
                console.error('Error fetching transactions:', error);
            }
        };

        fetchTransactions();
    }, [id]);

    return (
        <div className="flex flex-col items-center min-h-screen bg-gray-100 p-4">
            <div className="w-full bg-white p-4 rounded-lg shadow-lg">
                <h2 className="text-3xl font-bold text-center p-4">Recent Transactions</h2>
                <table className="w-full bg-white border">
                    <thead>
                        <tr>
                            <th className="py-2 px-4 border-b">ID</th>
                            <th className="py-2 px-4 border-b">Amount</th>
                            <th className="py-2 px-4 border-b">Date</th>
                            <th className="py-2 px-4 border-b">Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        {transactions.map(transaction => (
                            <tr key={transaction.id}>
                                <td className="py-2 px-4 border-b text-center">{transaction.id}</td>
                                <td className="py-2 px-4 border-b text-center">{transaction.amount}</td>
                                <td className="py-2 px-4 border-b text-center">{transaction.date}</td>
                                <td className="py-2 px-4 border-b text-center">{transaction.method}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
};

export default PaymentHistory;
