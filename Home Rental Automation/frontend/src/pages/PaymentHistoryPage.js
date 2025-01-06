import React, { useEffect, useState } from "react";
import api from "../services/api";

const PaymentHistory = () => {
  const [payments, setPayments] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const userID = localStorage.getItem("userID");

  console.log("userID", userID);

  useEffect(() => {
    const fetchHistory = async () => {
      try {
        setLoading(true);
        const response = await api.get(`/paymenthistory/${userID}`);
        console.log(response.data);
        setPayments(response.data.data);
        setError("");
      } catch (err) {
        console.error("Error fetching payment history:", err);
        setError("Failed to fetch payment history. Please try again later.");
      } finally {
        setLoading(false);
      }
    };

    if (userID) {
      fetchHistory();
    } else {
      setLoading(false);
      setError("User not found. Please log in again.");
    }
  }, [userID]);

  if (loading) return <p className="text-center text-gray-500">Loading...</p>;
  if (error) return <p className="text-center text-red-500">{error}</p>;

  return (
    <div className="container mx-auto p-6">
      <h1 className="text-2xl font-bold text-gray-800 mb-6 text-center">Payment History</h1>
      {payments.length > 0 ? (
        <table className="w-full table-auto border-collapse border border-gray-300">
          <thead>
            <tr className="bg-gray-100">
              <th className="border border-gray-300 px-4 py-2">#</th>
              <th className="border border-gray-300 px-4 py-2">Tenant Name</th>
              <th className="border border-gray-300 px-4 py-2">Amount</th>
              <th className="border border-gray-300 px-4 py-2">Date</th>
              <th className="border border-gray-300 px-4 py-2">Method</th>
            </tr>
          </thead>
          <tbody>
            {payments.map((payment, index) => (
              <tr
                key={payment.payment_id}
                className={index % 2 === 0 ? "bg-white" : "bg-gray-50"}
              >
                <td className="border border-gray-300 px-4 py-2 text-center">
                  {payment.id}
                </td>
                <td className="border border-gray-300 px-4 py-2">
                  {payment.name}
                </td>
                <td className="border border-gray-300 px-4 py-2">
                  {payment.amount}
                </td>
                <td className="border border-gray-300 px-4 py-2">
                  {payment.date}
                </td>
                <td className="border border-gray-300 px-4 py-2">
                  {payment.method}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      ) : (
        <p className="text-gray-500 text-center">No payment history found.</p>
      )}
    </div>
  );
};

export default PaymentHistory;
