import React, { useEffect, useState } from 'react';
import api from '../services/api';

function PaymentPage() {
  const [paymentMethods, setPaymentMethods] = useState({
    bkash: { numberType: '', number: '' },
    nagad: { numberType: '', number: '' },
    rocket: { numberType: '', number: '' },
  });

  const userID = localStorage.getItem('userID'); // Retrieve user ID from localStorage

  useEffect(() => {
    // Function to fetch data from API
    const fetchData = async () => {
      try {
        const response = await api.get(`/paymentmethod/${userID}`);
        if (response.data.status) {
          // Map the response data to the state
          const methods = response.data.data.reduce((acc, method) => {
            acc[method.payment_method.toLowerCase()] = {
              numberType: method.account_type || '',
              number: method.number || '',
            };
            return acc;
          }, {});

          // Update the state with the response data
          setPaymentMethods((prevState) => ({
            ...prevState,
            ...methods,
          }));
        } else {
          console.error('Error fetching payment methods');
        }
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    if (userID) {
      fetchData(); // Call fetchData if userID is available
    }
  }, [userID]);

  // Handle updating payment method details
  const handleInputChange = (method, e) => {
    const { name, value } = e.target;
    setPaymentMethods((prevState) => ({
      ...prevState,
      [method]: {
        ...prevState[method],
        [name]: value,
      },
    }));
  };

  // Handle saving updated data
  const handleUpdate = async () => {
    try {
      const response = await api.post(`/paymentmethod/${userID}`, paymentMethods);
      if (response.data.status) {
        alert('Payment methods updated successfully!');
      } else {
        alert('Failed to update payment methods. Please try again.');
        console.error('Error updating payment methods');
      }
    } catch (error) {
      alert('An error occurred while updating payment methods.');
      console.error('Error updating payment methods:', error);
    }
  };

  return (
    <div className="container mx-auto p-4">
      <h2 className="text-2xl font-bold mb-4 text-center">Manage Payment Methods</h2>
      <div className="bg-white shadow-md p-6 rounded-lg">
        {/* Payment Method Components */}
        {['bkash', 'nagad', 'rocket'].map((method) => (
          <div className="mb-4" key={method}>
            <h3 className="text-lg font-semibold mb-2">{method.charAt(0).toUpperCase() + method.slice(1)}</h3>
            <div className="mb-4">
              <label className="block text-gray-700">Number Type</label>
              <select
                name="numberType"
                value={paymentMethods[method]?.numberType || ''}
                onChange={(e) => handleInputChange(method, e)}
                className="w-full px-4 py-2 border border-gray-300 rounded mt-2"
              >
                <option value="">Select Number Type</option>
                <option value="personal">Personal</option>
                <option value="agent">Agent</option>
              </select>
            </div>
            <div className="mb-4">
              <label className="block text-gray-700">Phone Number</label>
              <input
                type="text"
                name="number"
                value={paymentMethods[method]?.number || ''}
                onChange={(e) => handleInputChange(method, e)}
                className="w-full px-4 py-2 border border-gray-300 rounded mt-2"
                placeholder={`Enter ${method.charAt(0).toUpperCase() + method.slice(1)} number`}
              />
            </div>
          </div>
        ))}

        {/* Save Button */}
        <button
          onClick={handleUpdate}
          className="bg-blue-500 text-white px-4 py-2 rounded mt-4 hover:bg-blue-600"
        >
          Update Payment Methods
        </button>
      </div>
    </div>
  );
}

export default PaymentPage;
