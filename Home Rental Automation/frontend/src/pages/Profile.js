import React, { useEffect, useState } from 'react';
import api from '../services/api';

const Profile = () => {
    const [profile, setProfile] = useState({
        name: '',
        email: '',
        contact: '',
        nid: '',
        address: '',
    });
    const [errors, setErrors] = useState({
        phone: '',
        nid: '',
    });

    // Fetch user ID from localStorage
    const id = localStorage.getItem('userID');

    useEffect(() => {
        const fetchProfile = async () => {
            try {
                const response = await api.get(`/tenant-profile/${id}`);
                console.log(response);
                setProfile(response.data.data);
            } catch (error) {
                console.error('Error fetching profile:', error);
            }
        };

        fetchProfile();
    }, [id]);

    const handleInputChange = (e) => {
        const { name, value } = e.target;

        // Reset error messages when the user types
        if (name === 'contact' || name === 'nid') {
            setErrors({ ...errors, [name]: '' });
        }

        setProfile({ ...profile, [name]: value });
    };

    const validateForm = () => {
        let isValid = true;
        const newErrors = {};

        // Validate contact number
        if (!/^0\d{10}$/.test(profile.contact)) {
            newErrors.phone = 'Contact number must be 11 digits and start with 0.';
            isValid = false;
        }

        // Validate NID number
        if (!/^\d{13}$/.test(profile.nid) && !/^\d{11}$/.test(profile.nid)) {
            newErrors.nid = 'NID number must be either 11 or 13 digits.';
            isValid = false;
        }

        setErrors(newErrors);
        return isValid;
    };

    const saveProfile = async () => {
        if (validateForm()) {
            try {
                const response = await api.post(`/tenant-profile/${id}`, profile);
                alert('Profile updated successfully!');
                console.log('Update Response:', response.data);
            } catch (error) {
                console.error('Error updating profile:', error);
                alert('Failed to update profile.');
            }
        }
    };

    return (
        <div>
            <h2 className="text-3xl font-bold text-center p-4">Profile</h2>
            <div className="p-4">
                <div className="mb-4">
                    <label>Name:</label>
                    <input
                        type="text"
                        name="name"
                        value={profile.name}
                        onChange={handleInputChange}
                        className="border p-2 w-full"
                    />
                </div>
                <div className="mb-4">
                    <label>Email:</label>
                    <input
                        type="email"
                        name="email"
                        value={profile.email}
                        onChange={handleInputChange}
                        className="border p-2 w-full"
                    />
                </div>
                <div className="mb-4">
                    <label>Phone:</label>
                    <input
                        type="text"
                        name="contact"
                        value={profile.contact}
                        onChange={handleInputChange}
                        className="border p-2 w-full"
                    />
                    {errors.phone && (
                        <p className="text-red-500 text-sm mt-1">{errors.phone}</p>
                    )}
                </div>
                <div className="mb-4">
                    <label>NID:</label>
                    <input
                        type="text"
                        name="nid"
                        value={profile.nid}
                        onChange={handleInputChange}
                        className="border p-2 w-full"
                    />
                    {errors.nid && (
                        <p className="text-red-500 text-sm mt-1">{errors.nid}</p>
                    )}
                </div>
                <div className="mb-4">
                    <label>Address:</label>
                    <textarea
                        name="address"
                        value={profile.address}
                        onChange={handleInputChange}
                        className="border p-2 w-full"
                    />
                </div>
                <button
                    className="bg-green-500 text-white px-4 py-2 rounded"
                    onClick={saveProfile}
                >
                    Save Profile
                </button>
            </div>
        </div>
    );
};

export default Profile;
