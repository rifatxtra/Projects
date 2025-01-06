import React, { useState, useEffect } from 'react';
import api from '../services/api';

const PendingPayment = () => {
    const [payments, setPayments] = useState([]);

    // Fetch payment data from API
    useEffect(() => {
        api.get('/fetchPendingPayments')  // Ensure this points to the correct PHP endpoint
            .then(response => {
                setPayments(response.data);
            })
            .catch(error => {
                console.error("There was an error fetching the data!", error);
            });
    }, []);

    const sendReminder = async(email,month,amount,name) => {
        const sub="Payment Pending";
        const body=`<p>Hello ${name},</p> <br><p>You have ${month} months payment pending. Total Amount is ${amount} TK.</p><br><p>Please pay as soon as possible</p>`;
        const mailData={
            email:email,
            subject:sub,
            body:body,
            name: name
        }
        const response=await api.post('/sendemail',mailData);
        if(response.data.status){
            alert(response.data.msg);
        }
        console.log(`Sending reminder to ${email}`);
    };

    const formatAmount = (amount) => {
        return amount < 0 ? 0 : amount.toFixed(2);  // Ensure no negative amounts are displayed
    };

    return (
        <div>
            <h2 className="text-3xl font-bold text-center p-2">Pending Payments</h2>
            <table className="table-auto w-full mt-4 border-collapse">
                <thead>
                    <tr>
                        <th className="border p-2">Tenant Name</th>
                        <th className="border p-2">Flat Name</th>
                        <th className="border p-2">Months Pending</th>
                        <th className="border p-2">Total Pending Amount</th>
                        <th className="border p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {payments.map((payment, index) => (
                        <tr key={index}>
                            <td className="border p-2">{payment.tenant_name}</td>
                            <td className="border p-2">{payment.flat_name}</td>
                            <td className="border p-2">{payment.months_pending}</td>
                            <td className="border p-2">TK {formatAmount(payment.total_pending_amount)}</td>
                            <td className="border p-2">
                                <button
                                    className="bg-blue-500 text-white px-4 py-2 rounded"
                                    onClick={() => sendReminder(payment.email,payment.months_pending,payment.total_pending_amount,payment.tenant_name )}
                                >
                                    Send Reminder
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default PendingPayment;
