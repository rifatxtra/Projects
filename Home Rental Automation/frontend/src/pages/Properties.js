import React, { useState, useEffect } from "react";
import api from "../services/api";

const Properties = () => {
  const [properties, setProperties] = useState([]);
  const [showPropertyModal, setShowPropertyModal] = useState(false);
  const [showFlatModal, setShowFlatModal] = useState(false);
  const [editingProperty, setEditingProperty] = useState(null);
  const [editingFlat, setEditingFlat] = useState(null);
  const [deletingProperty, setDeletingProperty] = useState(null);
  const [deletingFlat, setDeletingFlat] = useState(null);
  const [newFlat, setNewFlat] = useState({
    propertyId: "",
    flatName: "",
    floor: "",
    rent:"",
    tenants: '',
  });
  const [newProperty, setNewProperty] = useState({
    name: "",
    location: "",
  });

  const fetchProperties = async () => {
    try {
      const response = await api.get("/properties/getproperty");
      if (response.data && Array.isArray(response.data.data)) {
        const propertiesWithFlats = response.data.data.map((property) => ({
          ...property,
          flats: Array.isArray(property.flats) ? property.flats : [],
        }));
        setProperties(propertiesWithFlats);
      } else {
        console.error("Unexpected API response:", response.data);
      }
    } catch (error) {
      console.error("Error fetching properties:", error);
    }
  };

  useEffect(() => {
    fetchProperties();
  }, []);

  const handleAddFlat = async () => {
    try {
      const response = await api.post("/properties/addflat", newFlat);
      if (response.data.status) {
        fetchProperties();
        setShowFlatModal(false);
        setNewFlat({ propertyId: "", flatName: "", floor: "", tenants: 0 });
      }
    } catch (error) {
      console.error("Error adding Flat:", error);
    }
  };

  const handleEditFlat = async () => {
    if (!editingFlat) return;
    try {
      const response = await api.post("/properties/editflat", editingFlat);
      if (response.data.status) {
        fetchProperties();
        setEditingFlat(null);
      }
    } catch (error) {
      console.error("Error editing flat:", error);
    }
  };

  const handleDeleteFlat = async () => {
    if (!deletingFlat) return;
    try {
      const response = await api.post("/properties/deleteflat", { id: deletingFlat.id });
      if (response.data.status) {
        fetchProperties();
        setDeletingFlat(null);
      }
    } catch (error) {
      console.error("Error deleting flat:", error);
    }
  };

  const handleAddProperty = async () => {
    try {
      const response = await api.post("/properties/addproperty", newProperty);
      if (response.data.status) {
        fetchProperties();
        setShowPropertyModal(false);
        setNewProperty({ name: "", location: "" });
      }
    } catch (error) {
      console.error("Error adding property:", error);
    }
  };

  const handleEditProperty = async () => {
    if (!editingProperty) return;
    try {
      const response = await api.post("/properties/editproperty", editingProperty);
      if (response.data.status) {
        fetchProperties();
        setEditingProperty(null);
      }
    } catch (error) {
      console.error("Error editing property:", error);
    }
  };

  const handleDeleteProperty = async () => {
    if (!deletingProperty) return;
    try {
      const response = await api.post("/properties/deleteproperty", { id: deletingProperty.id });
      if (response.data.status) {
        fetchProperties();
        setDeletingProperty(null);
      }
    } catch (error) {
      console.error("Error deleting property:", error);
    }
  };

  return (
    <div className="p-6">
      <h1 className="text-3xl font-bold mb-6 text-gray-800 text-center">Manage Properties</h1>
      <div className="flex space-x-4 mb-4">
        <button
          className="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
          onClick={() => setShowPropertyModal(true)}
        >
          Add Property
        </button>
        <button
          className="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600"
          onClick={() => setShowFlatModal(true)}
        >
          Add Flat
        </button>
      </div>

      <div className="overflow-x-auto">
        <table className="min-w-full bg-white rounded-lg shadow-md">
          <thead>
            <tr className="bg-gray-100">
              <th className="text-left py-3 px-4 font-medium text-gray-600">Property Name</th>
              <th className="text-left py-3 px-4 font-medium text-gray-600">Location</th>
              <th className="text-left py-3 px-4 font-medium text-gray-600">Flats</th>
              <th className="text-center py-3 px-4 font-medium text-gray-600">Actions</th>
            </tr>
          </thead>
          <tbody>
            {properties.map((property) => (
              <tr key={property.id} className="border-t">
                <td className="py-3 px-4">{property.name}</td>
                <td className="py-3 px-4">{property.location}</td>
                <td className="py-3 px-4">
                  {property.flats && property.flats.length > 0 ? (
                    <ul>
                      {property.flats.map((flat) => (
                        <li key={flat.id} className="mb-2">
                          ({flat.flat_name} ,{flat.floor} Floor,{flat.tenants} Tenants)
                          <button
                            className="ml-2 text-blue-500 hover:underline"
                            onClick={() => setEditingFlat(flat)}
                          >
                            Edit
                          </button>
                          <button
                            className="ml-2 text-red-500 hover:underline"
                            onClick={() => setDeletingFlat(flat)}
                          >
                            Delete
                          </button>
                        </li>
                      ))}
                    </ul>
                  ) : (
                    <span className="text-gray-500">No Flats</span>
                  )}
                </td>
                <td className="py-3 px-4 text-center">
                  <button
                    className="text-blue-500 hover:underline mr-2"
                    onClick={() => setEditingProperty(property)}
                  >
                    Edit
                  </button>
                  <button
                    className="text-red-500 hover:underline"
                    onClick={() => setDeletingProperty(property)}
                  >
                    Delete
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Add Property Modal */}
      {showPropertyModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
          <div className="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 className="text-xl font-bold mb-4">Add New Property</h2>
            <input
              type="text"
              placeholder="Property Name"
              className="w-full mb-4 p-2 border rounded"
              value={newProperty.name}
              onChange={(e) => setNewProperty({ ...newProperty, name: e.target.value })}
            />
            <input
              type="text"
              placeholder="Location"
              className="w-full mb-4 p-2 border rounded"
              value={newProperty.location}
              onChange={(e) => setNewProperty({ ...newProperty, location: e.target.value })}
            />
            <div className="flex justify-end space-x-2">
              <button
                className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                onClick={() => setShowPropertyModal(false)}
              >
                Cancel
              </button>
              <button
                className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                onClick={handleAddProperty}
              >
                Save
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Add Flat Modal */}
      {showFlatModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
          <div className="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 className="text-xl font-bold mb-4">Add New Flat</h2>
            <select
              className="w-full mb-4 p-2 border rounded"
              value={newFlat.propertyId}
              onChange={(e) => setNewFlat({ ...newFlat, propertyId: e.target.value })}
            >
              <option value="">Select Property</option>
              {properties.map((property) => (
                <option key={property.id} value={property.id}>
                  {property.name}
                </option>
              ))}
            </select>
            <input
              type="text"
              placeholder="Flat Name"
              className="w-full mb-4 p-2 border rounded"
              value={newFlat.flatName}
              onChange={(e) => setNewFlat({ ...newFlat, flatName: e.target.value })}
            />
            <input
              type="text"
              placeholder="Floor"
              className="w-full mb-4 p-2 border rounded"
              value={newFlat.floor}
              onChange={(e) => setNewFlat({ ...newFlat, floor: e.target.value })}
            />
            <input
              type="number"
              placeholder="Rent"
              className="w-full mb-4 p-2 border rounded"
              value={newFlat.rent}
              onChange={(e) => setNewFlat({ ...newFlat, rent: e.target.value })}
            />
            <input
              type="number"
              placeholder="Number of Tenants"
              className="w-full mb-4 p-2 border rounded"
              value={newFlat.tenants}
              onChange={(e) => setNewFlat({ ...newFlat, tenants: parseInt(e.target.value) || 0 })}
            />
            <div className="flex justify-end space-x-2">
              <button
                className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                onClick={() => setShowFlatModal(false)}
              >
                Cancel
              </button>
              <button
                className="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
                onClick={handleAddFlat}
              >
                Save Flat
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Edit Property Modal */}
      {editingProperty && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
          <div className="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 className="text-xl font-bold mb-4">Edit Property</h2>
            <input
              type="text"
              placeholder="Property Name"
              className="w-full mb-4 p-2 border rounded"
              value={editingProperty.name}
              onChange={(e) => setEditingProperty({ ...editingProperty, name: e.target.value })}
            />
            <input
              type="text"
              placeholder="Location"
              className="w-full mb-4 p-2 border rounded"
              value={editingProperty.location}
              onChange={(e) => setEditingProperty({ ...editingProperty, location: e.target.value })}
            />
            <div className="flex justify-end space-x-2">
              <button
                className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                onClick={() => setEditingProperty(null)}
              >
                Cancel
              </button>
              <button
                className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                onClick={handleEditProperty}
              >
                Save Changes
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Edit Flat Modal */}
      {editingFlat && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
          <div className="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 className="text-xl font-bold mb-4">Edit Flat</h2>
            <input
              type="text"
              placeholder="Flat Name"
              className="w-full mb-4 p-2 border rounded"
              value={editingFlat.flatName}
              onChange={(e) => setEditingFlat({ ...editingFlat, flatName: e.target.value })}
            />
            <input
              type="text"
              placeholder="Floor"
              className="w-full mb-4 p-2 border rounded"
              value={editingFlat.floor}
              onChange={(e) => setEditingFlat({ ...editingFlat, floor: e.target.value })}
            />
            <input
              type="number"
              placeholder="Rent"
              className="w-full mb-4 p-2 border rounded"
              value={editingFlat.rent}
              onChange={(e) => setEditingFlat({ ...editingFlat, rent: e.target.value })}
            />
            <input
              type="number"
              placeholder="Number of Tenants"
              className="w-full mb-4 p-2 border rounded"
              value={editingFlat.tenants}
              onChange={(e) => setEditingFlat({ ...editingFlat, tenants: parseInt(e.target.value) || 0 })}
            />
            <div className="flex justify-end space-x-2">
              <button
                className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                onClick={() => setEditingFlat(null)}
              >
                Cancel
              </button>
              <button
                className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                onClick={handleEditFlat}
              >
                Save Changes
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Delete Property Confirmation Modal */}
      {deletingProperty && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
          <div className="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 className="text-xl font-bold mb-4">Confirm Delete Property</h2>
            <p>Are you sure you want to delete the property "{deletingProperty.name}"?</p>
            <div className="flex justify-end space-x-2 mt-4">
              <button
                className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                onClick={() => setDeletingProperty(null)}
              >
                Cancel
              </button>
              <button
                className="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                onClick={handleDeleteProperty}
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Delete Flat Confirmation Modal */}
      {deletingFlat && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
          <div className="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 className="text-xl font-bold mb-4">Confirm Delete Flat</h2>
            <p>Are you sure you want to delete the flat "{deletingFlat.flatName}"?</p>
            <div className="flex justify-end space-x-2 mt-4">
              <button
                className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                onClick={() => setDeletingFlat(null)}
              >
                Cancel
              </button>
              <button
                className="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                onClick={handleDeleteFlat}
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default Properties;

