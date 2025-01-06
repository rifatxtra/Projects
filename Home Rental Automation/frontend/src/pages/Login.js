import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import api from "../services/api"; // Ensure your API service is set up correctly

const Login = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [role, setRole] = useState("tenant"); // Default to "tenant"
  const navigate = useNavigate(); // Use navigate here

  // Get the values from localStorage (Handle parsing safely)
  const userID = localStorage.getItem('userID');
  const userRole = localStorage.getItem('userRole');
  const token = localStorage.getItem('token');
  
  const data = { userID, userRole };
  
  // Function to verify token on the server
  const tokenVerify = async (userID, userRole, token) => {
    try {
      const response = await api.post("/tokenverify", { data });
      if (response.data.status) {
        return response.data.token ? true : false;
      } else {
        console.error("Invalid response structure:", response);
        return false;
      }
    } catch (error) {
      console.error("Error verifying token:", error);
      return false;
    }
  };

  // If user is already logged in and has valid token, redirect to dashboard
  useEffect(() => {
    const verifyToken = async () => {
      if (userID && userRole && token) {
        const isValid = await tokenVerify(userID, userRole, token);
        if (isValid) {
          navigate(userRole === "owner" ? "/admin/dashboard" : "/dashboard");
        }
      }
    };
    verifyToken();
  }, [userID, userRole, token, navigate]);

  const handleLogin = async (e) => {
    e.preventDefault();

    const loginRoute = role === "owner" ? "/login/owners" : "/login/tenants";

    try {
      const response = await api.post(loginRoute, { email, password });
      console.log(email,password,loginRoute);
      console.log(response);
      if (response.data.status) {
        // Store token, userID, and role securely in localStorage
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('userID', response.data.id); // Use JSON.stringify to store number/ID
        localStorage.setItem('userRole', response.data.role); // Store role as string

        // Redirect to the appropriate dashboard
        navigate(role === "owner" ? "/admin/dashboard" : "/tenant-dashboard");
      } else {
        setError("Invalid credentials");
      }
    } catch (error) {
      setError("Something went wrong, please try again.");
    }
  };

  return (
    <div className="h-screen flex items-center justify-center bg-gray-100">
      <div className="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 className="text-2xl font-bold mb-4">Login</h2>
        {error && <div className="text-red-500 text-sm mb-4">{error}</div>}
        <form onSubmit={handleLogin}>
          <div className="mb-4">
            <label className="block text-sm font-semibold mb-2" htmlFor="role">
              Login As:
            </label>
            <select
              id="role"
              value={role}
              onChange={(e) => setRole(e.target.value)}
              className="w-full px-4 py-2 border rounded-md"
              required
            >
              <option value="tenant">Tenant</option>
              <option value="owner">Admin</option>
            </select>
          </div>

          <div className="mb-4">
            <label className="block text-sm font-semibold mb-2" htmlFor="email">
              Email:
            </label>
            <input
              type="email"
              id="email"
              className="w-full px-4 py-2 border rounded-md"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />
          </div>

          <div className="mb-4">
            <label className="block text-sm font-semibold mb-2" htmlFor="password">
              Password:
            </label>
            <input
              type="password"
              id="password"
              className="w-full px-4 py-2 border rounded-md"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
          </div>

          <button
            type="submit"
            className="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600"
          >
            Login
          </button>
        </form>
      </div>
    </div>
  );
};

export default Login;
