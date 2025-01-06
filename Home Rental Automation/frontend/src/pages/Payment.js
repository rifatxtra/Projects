import React, { useState, useEffect } from 'react';
import { QRCodeCanvas } from 'qrcode.react';
import api from '../services/api';

const Payment = () => {
    const [paymentMethods, setPaymentMethods] = useState([]);
    const [selectedMethod, setSelectedMethod] = useState(null);
    const [transactions, setTransactions] = useState([]);
    const [txnid, setTxnID] = useState('');
    const id = localStorage.getItem('userID');

    const fetchTransactions = async () => {
        try {
            const response = await api.get(`/pending-payments/${id}`);
            if (response.data.status) {
                setTransactions(response.data.data);
            } else {
                console.error('Error fetching transactions:', response.data.error);
            }
        } catch (error) {
            console.error('Error fetching transactions:', error);
        }
    };
    
    useEffect(() => {
        const fetchPaymentMethods = async () => {
            try {
                const response = await api.get('/make-payment');
                if (Array.isArray(response.data.data)) {
                    setPaymentMethods(response.data.data);
                } else {
                    console.error('Unexpected response format:', response.data);
                }
            } catch (error) {
                console.error('Error fetching payment methods:', error);
            }
        };

        const fetchTransactions = async () => {
            try {
                const response = await api.get(`/pending-payments/${id}`);
                if (response.data.status) {
                    setTransactions(response.data.data);
                } else {
                    console.error('Error fetching transactions:', response.data.error);
                }
            } catch (error) {
                console.error('Error fetching transactions:', error);
            }
        };

        fetchPaymentMethods();
        fetchTransactions();
    }, [id]);

    const handleSelectChange = (e) => {
        const methodName = e.target.value;
        const method = paymentMethods.find(m => m.payment_method === methodName);
        setSelectedMethod(method);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!txnid || !selectedMethod) {
            console.error('Transaction ID or payment method is missing.');
            return;
        }

        const data = {
            payment_method: selectedMethod.payment_method, // Send method name
            tenant_id: id,
            transaction_id: txnid,
        };

        console.log('Submitting payment data:', data);

        try {
            const response = await api.post('/pending-payments', data);
            if (response.data.status) {
                console.log('Payment submitted successfully:', response.data);
                setTxnID(''); // Reset Transaction ID field
                alert('Payment submitted successfully!');
                fetchTransactions(); // Refresh transactions
            } else {
                console.error('Error submitting payment:', response.data.error);
                alert('Failed to submit payment. Please try again.');
            }
        } catch (error) {
            console.error('Error submitting payment:', error);
            alert('An error occurred while submitting payment.');
        }
    };

    return (
        <div className="flex flex-col md:flex-row">
            {/* Left Side: Payment Methods */}
            <div className="w-full md:w-1/2 p-4">
                <h2 className="text-3xl font-bold text-center p-4">Payment Methods</h2>
                <div className="p-4">
                    <label className="block mb-2">Select Payment Method:</label>
                    <select onChange={handleSelectChange} className="border p-2 w-full mb-4">
                        <option value="">Select</option>
                        {paymentMethods.length > 0 ? (
                            paymentMethods.map(method => (
                                <option key={method.payment_method_id} value={method.payment_method}>
                                    {method.payment_method}
                                </option>
                            ))
                        ) : (
                            <option disabled>No payment methods available</option>
                        )}
                    </select>

                    {selectedMethod && (
                        <div className="mt-4 flex flex-col items-center">
                            <h3 className="text-2xl font-bold">{selectedMethod.payment_method}</h3>
                            <p>Account Type: {selectedMethod.account_type}</p>
                            <p>Number: {selectedMethod.number}</p>

                            <h4 className="text-xl font-bold mt-4">QR Code</h4>
                            <QRCodeCanvas value={selectedMethod.number} className="border p-4" />

                            {/* Transaction Form */}
                            <form className="flex flex-col items-center mt-4" onSubmit={handleSubmit}>
                                <label className="font-bold text-lg mb-2">Transaction ID</label>
                                <input
                                    type="text"
                                    className="border border-gray-300 rounded p-2 mb-4 w-64"
                                    name="txnid"
                                    value={txnid}
                                    onChange={(e) => setTxnID(e.target.value)}
                                    placeholder="Enter Transaction ID"
                                />
                                <button
                                    type="submit"
                                    className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700"
                                >
                                    Submit
                                </button>
                            </form>
                        </div>
                    )}
                </div>
            </div>

            {/* Right Side: Transactions */}
            <div className="w-full md:w-1/2 p-4">
                <h2 className="text-3xl font-bold text-center p-4">Transactions</h2>
                <div className="overflow-x-auto">
                    <table className="min-w-full bg-white border">
                        <thead>
                            <tr>
                                <th className="py-2 px-4 border-b">Amount</th>
                                <th className="py-2 px-4 border-b">Date</th>
                                <th className="py-2 px-4 border-b">Method</th>
                                <th className="py-2 px-4 border-b">Transaction ID</th>
                                <th className="py-2 px-4 border-b">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {transactions.length > 0 ? (
                                transactions.map(transaction => (
                                    <tr key={transaction.id}>
                                        <td className="py-2 px-4 border-b">{transaction.amount}</td>
                                        <td className="py-2 px-4 border-b">{transaction.created_at}</td>
                                        <td className="py-2 px-4 border-b">{transaction.payment_method}</td>
                                        <td className="py-2 px-4 border-b">{transaction.transaction_id}</td>
                                        <td className="py-2 px-4 border-b">
                                            {transaction.status === 0 && 'Unconfirmed'}
                                            {transaction.status === 1 && 'Confirmed'}
                                            {transaction.status === 2 && 'Rejected'}
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="5" className="text-center py-4">
                                        No transactions found.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
};

export default Payment;
