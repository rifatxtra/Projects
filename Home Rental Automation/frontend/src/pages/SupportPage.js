import React, { useState, useEffect } from 'react';
import api from '../services/api';

const SupportPage = () => {
    const [message, setMessage] = useState('');
    const [supports, setSupports] = useState([]);
    const id = localStorage.getItem('userID');

    const fetchSupports = async () => {
        try {
            const response = await api.get(`/support/${id}`);
            if (response.data.status) {
                setSupports(response.data.data);
            } else {
                console.error('Error fetching supports:', response.data.error);
            }
        } catch (error) {
            console.error('Error fetching supports:', error);
        }
    };

    useEffect(() => {
        fetchSupports();
    }, [id]);

    const submitSupportRequest = async () => {
        try {
            const data = {
                query: message,
                tenant_id: id
            };

            const response = await api.post(`/support/${id}`, data);
            console.log(response);
            if (response.data.status) {
                let sub = "New Support Request";
                let body = `<p>Hello Owner,</p><br><p>New Support Requested from ${response.data.data.tenantemail}</p>`;
                let maildata = {
                    email: response.data.data.owneremail,
                    subject: sub,
                    body: body,
                    name: 'Owner'
                };
                let res = await api.post('/sendemail', maildata);

                sub = "Your Support Request Has Been Received";
                body = `<p>Soon, Owner will contact you</p>`;
                maildata = {
                    email: response.data.data.tenantemail,
                    subject: sub,
                    body: body,
                    name: 'Tenant'
                };
                res = await api.post('/sendemail', maildata);

                if (res.data.status) {
                    alert('Support request submitted!');
                }
                
                // Add the new support to the top of the list
                const newSupport = { id: response.data.data.id, query: message, status: 0 }; // Use actual ID from the response if available
                setSupports([newSupport, ...supports]);
            }
        } catch (error) {
            console.error('Error submitting support request:', error);
            alert('There was an error submitting your support request.');
        } finally {
            setMessage('');
        }
    };

    return (
        <div>
            <h2 className="text-3xl font-bold text-center p-4">Support</h2>
            <div className="p-4">
                <label>Enter Your Query:</label>
                <textarea
                    value={message}
                    onChange={(e) => setMessage(e.target.value)}
                    className="border p-2 w-full"
                />
                <button
                    className="bg-green-500 text-white px-4 py-2 mt-4 rounded"
                    onClick={submitSupportRequest}
                >
                    Submit
                </button>
            </div>
            <div className="p-4">
                <h2 className="text-3xl font-bold text-center p-4">Recent Supports</h2>
                <table className="min-w-full bg-white border">
                    <thead>
                        <tr>
                            <th className="py-2 px-4 border-b">Query</th>
                            <th className="py-2 px-4 border-b">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        {supports.map(support => (
                            <tr key={support.id}>
                                <td className="py-2 px-4 border-b w-[80%]">{support.query}</td>
                                <td className={`text-center py-2 px-4 border-b ${support.status === 1 ? 'text-green-500' : ''}`}>
                                    {support.status === 1 ? 'Resolved' : 'Unresolved'}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
};

export default SupportPage;
