import React, { useEffect, useState } from 'react';
import api from '../services/api';

function Tenants() {
  const [tenants, setTenants] = useState([]);
  const [properties, setProperties] = useState([]);
  const [selectedTenant, setSelectedTenant] = useState(null);
  const [selectedFlat, setSelectedFlat] = useState('');
  const [modalOpen, setModalOpen] = useState(false);
  const [newTenantModalOpen, setNewTenantModalOpen] = useState(false);
  const [newTenantName, setNewTenantName] = useState('');
  const [newTenantEmail, setNewTenantEmail] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState('');
  const id=localStorage.getItem('userID');
  const fetchDatas = async () => {
    setIsLoading(true);
    setError('');
    try {
      const [propertyResponse, tenantResponse] = await Promise.all([
        api.get('/properties/getproperty'),
        api.get(`/tenants`),
      ]);

      if (propertyResponse.data.status) {
        setProperties(propertyResponse.data.data);
      }

      if (tenantResponse.data.status) {
        setTenants(tenantResponse.data.data);
      }
    } catch (error) {
      setError('Error fetching data. Please try again.');
      console.error('Error fetching data:', error);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchDatas();
  }, []);

  const handleFreeFlat = async (tenantId) => {
    setIsLoading(true);
    setError('');
    try {
      const flatToFree = properties.flatMap(property =>
        property.flats.find(flat => flat.tenants === tenantId)
      ).find(flat => flat);

      if (flatToFree) {
        const response = await api.get(`/tenants/free/${flatToFree.id}`);
        if (response.data.status) {
          await fetchDatas();
        } else {
          setError(response.data.msg || 'Failed to free flat');
        }
      }
    } catch (error) {
      setError('Error freeing flat. Please try again.');
      console.error('Error freeing flat:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const handleDeleteTenant = async (tenantId) => {
    setIsLoading(true);
    setError('');
    try {
      const response = await api.get(`/tenants/delete/${tenantId}`);
      if (response.data.status) {
        await fetchDatas();
      } else {
        setError(response.data.msg || 'Failed to delete tenant');
      }
    } catch (error) {
      setError('Error deleting tenant. Please try again.');
      console.error('Error deleting tenant:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const handleOpenModal = (tenantId) => {
    setSelectedTenant(tenantId);
    setSelectedFlat('');
    setModalOpen(true);
  };

  const handleChangeFlatOrHouse = async () => {
    if (!selectedFlat) {
      setError('Please select a flat');
      return;
    }
    setIsLoading(true);
    setError('');
    try {
      const response = await api.get(`/tenants/add/${selectedFlat}/${selectedTenant}`);
      if (response.data.status) {
        await fetchDatas();
        setModalOpen(false);
      } else {
        setError(response.data.msg || 'Failed to change flat');
      }
    } catch (error) {
      setError('Error changing flat. Please try again.');
      console.error('Error changing flat:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const handleAddTenant = async () => {
    if (!newTenantName || !newTenantEmail) {
      setError('Please fill in all fields');
      return;
    }
    setIsLoading(true);
    setError('');
    const tenantData = {
      name: newTenantName,
      email: newTenantEmail,
    };
    try {
      const response = await api.post('/tenants/add', tenantData);
      console.log(response);
      if (response.data.status) {
        setNewTenantModalOpen(false);
        setNewTenantName('');
        setNewTenantEmail('');
        await fetchDatas();
        const mailData = {
          email: response.data.email,
          subject: response.data.subject,
          body: response.data.body,
          name: response.data.name
        }
        console.log(response.data);
        const responsemail = await api.post('/sendemail', mailData);
        if (responsemail.data.status) {
          console.log(responsemail.data.msg);
        }
      } else {
        setError(response.data.msg || 'Failed to add tenant');
      }
    } catch (error) {
      setError('Error adding tenant. Please try again.');
      console.error('Error adding tenant:', error);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="container mx-auto p-4">
      <h2 className="text-2xl font-bold mb-4 text-center">Tenants</h2>
      {error && <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">{error}</div>}
      <button
        onClick={() => setNewTenantModalOpen(true)}
        className="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mb-4"
        disabled={isLoading}
      >
        Add New Tenant
      </button>
      {isLoading ? (
        <div className="text-center">Loading...</div>
      ) : (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {tenants.map((tenant) => (
            <div key={tenant.id} className="bg-white shadow-md p-4 rounded-lg">
              <h3 className="text-xl font-semibold">{tenant.name}</h3>
              <p className="text-gray-600">
                {properties.map((property) =>
                  property.flats.map((flat) => {
                    if (flat.tenants === tenant.id) {
                      return (
                        <span key={flat.id}>
                          Flat: {flat.flat_name}, Floor: {flat.floor}
                        </span>
                      );
                    }
                    return null;
                  })
                )}
                {!properties.some((property) =>
                  property.flats.some((flat) => flat.tenants === tenant.id)
                ) && 'No Flat Assigned'}
              </p>
              <div className="mt-4 flex space-x-2">
                <button
                  onClick={() => handleFreeFlat(tenant.id)}
                  className="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                  disabled={isLoading}
                >
                  Free
                </button>
                <button
                  onClick={() => handleOpenModal(tenant.id)}
                  className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                  disabled={isLoading}
                >
                  Change
                </button>
                <button
                  onClick={() => handleDeleteTenant(tenant.id)}
                  className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                  disabled={isLoading}
                >
                  Delete
                </button>
              </div>
            </div>
          ))}
        </div>
      )}

      {newTenantModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
          <div className="bg-white p-6 rounded shadow-lg">
            <h3 className="text-lg font-semibold mb-4">Add New Tenant</h3>
            <input
              type="text"
              placeholder="Tenant Name"
              value={newTenantName}
              onChange={(e) => setNewTenantName(e.target.value)}
              className="border p-2 rounded w-full mb-4"
            />
            <input
              type="email"
              placeholder="Email"
              value={newTenantEmail}
              onChange={(e) => setNewTenantEmail(e.target.value)}
              className="border p-2 rounded w-full mb-4"
            />
            <button
              onClick={handleAddTenant}
              className="bg-green-500 text-white px-4 py-2 rounded"
              disabled={isLoading}
            >
              Add Tenant
            </button>
            <button
              onClick={() => setNewTenantModalOpen(false)}
              className="ml-2 bg-gray-500 text-white px-4 py-2 rounded"
              disabled={isLoading}
            >
              Cancel
            </button>
          </div>
        </div>
      )}

      {modalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
          <div className="bg-white p-6 rounded shadow-lg">
            <h3 className="text-lg font-semibold mb-4">Change Flat/House</h3>
            <select
              value={selectedFlat}
              onChange={(e) => setSelectedFlat(e.target.value)}
              className="border p-2 rounded w-full mb-4"
            >
              <option value="">Select Flat</option>
              {properties.map((property) =>
                property.flats.map((flat) => (
                  <option
                    key={flat.id}
                    value={flat.id}
                    disabled={flat.tenants != selectedTenant && flat.tenants !== null}
                  >
                    {property.name}, Flat: {flat.flat_name}, Floor: {flat.floor}
                    {flat.tenants === selectedTenant ? ' (Current)' : flat.tenants !== null ? ' (Occupied)' : ''}
                  </option>
                ))
              )}
            </select>
            <button
              onClick={handleChangeFlatOrHouse}
              className="bg-blue-500 text-white px-4 py-2 rounded"
              disabled={isLoading || !selectedFlat}
            >
              Save
            </button>
            <button
              onClick={() => setModalOpen(false)}
              className="ml-2 bg-gray-500 text-white px-4 py-2 rounded"
              disabled={isLoading}
            >
              Cancel
            </button>
          </div>
        </div>
      )}
    </div>
  );
}

export default Tenants;

