import React, { useState, useEffect, useCallback } from 'react';
import {
  BarChart,
  Bar,
  LineChart,
  Line,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
  ResponsiveContainer,
} from 'recharts';
import api from '../services/api';

const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#8884D8', '#82CA9D', '#A4DE6C'];

export default function StatisticsPage() {
  const [statistics, setStatistics] = useState({});
  const [filter, setFilter] = useState('thisYear');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api
      .get('/paymentstat')
      .then((response) => {
        console.log(response.data);
        setStatistics(response.data || {});
        setLoading(false);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
        setLoading(false);
      });
  }, []);

  const getFilteredData = useCallback(() => {
    if (typeof statistics !== 'object' || Object.keys(statistics).length === 0) {
      return [];
    }

    let filteredData = [];

    if (filter === 'thisYear') {
      filteredData = Object.entries(statistics).map(([flatId, flatData]) => ({
        flat: `Flat ${flatId}`,
        earnings: flatData.totalEarnings,
        year: new Date().getFullYear(),
      }));
    } else if (filter === 'previousYear' || filter === 'allTime') {
      const allEarnings = Object.values(statistics).flatMap(flat => flat.earnings);
      const groupedEarnings = allEarnings.reduce((acc, earning) => {
        const [year, month] = earning.month_year.split('-');
        const key = `${year}-${month}`;
        if (!acc[key]) acc[key] = 0;
        acc[key] += parseFloat(earning.amount);
        return acc;
      }, {});

      filteredData = Object.entries(groupedEarnings).map(([date, value]) => ({
        name: date,
        value: value / 1000, // Convert to thousands
      }));

      if (filter === 'previousYear') {
        const lastYear = new Date().getFullYear() - 1;
        filteredData = filteredData.filter(item => item.name.startsWith(lastYear.toString()));
      }
    }

    return filteredData;
  }, [filter, statistics]);

  const updateRandomFlat = () => {
    setStatistics((prevStats) => {
      const flatIds = Object.keys(prevStats);
      const randomFlatId = flatIds[Math.floor(Math.random() * flatIds.length)];
      const randomIncrease = Math.floor(Math.random() * 1000) + 100;
      const currentDate = new Date().toISOString().slice(0, 7);

      return {
        ...prevStats,
        [randomFlatId]: {
          ...prevStats[randomFlatId],
          totalEarnings: prevStats[randomFlatId].totalEarnings + randomIncrease,
          earnings: [
            ...prevStats[randomFlatId].earnings,
            {
              amount: randomIncrease.toFixed(2),
              month_year: currentDate,
              method: "Cash"
            }
          ]
        }
      };
    });
  };

  const downloadCSV = () => {
    const tableData = Object.entries(statistics).flatMap(([flatId, flatData]) =>
      flatData.earnings.map(earning => ({
        flat: `Flat ${flatId}`,
        month: earning.month_year,
        earnings: earning.amount,
        year: earning.month_year.split('-')[0],
      }))
    );

    const csvContent = [
      'Flat,Month,Earnings,Year',
      ...tableData.map((row) => `${row.flat},${row.month},${row.earnings},${row.year}`),
    ].join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `statistics_${filter}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  const lifetimeChartData = (() => {
    const years = Array.from({ length: 5 }, (_, i) => new Date().getFullYear() - i);
    return years.map(year => ({
      year,
      ...Object.entries(statistics).reduce((acc, [flatId, flatData]) => {
        acc[`Flat ${flatId}`] = flatData.earnings
          .filter(earning => earning.month_year.startsWith(year.toString()))
          .reduce((sum, earning) => sum + parseFloat(earning.amount), 0);
        return acc;
      }, {})
    }));
  })();

  const filteredData = getFilteredData();

  if (loading) {
    return <div>Loading...</div>;
  }

  return (
    <div className="container mx-auto px-4 py-6">
      <h1 className="text-3xl font-semibold text-center mb-6">Advanced Statistics</h1>

      <div className="mb-6">
        <label htmlFor="filter" className="text-lg font-medium">Filter by:</label>
        <select
          id="filter"
          className="ml-4 p-2 border border-gray-300 rounded"
          value={filter}
          onChange={(e) => setFilter(e.target.value)}
        >
          <option value="thisYear">This Year</option>
          <option value="previousYear">Previous Year</option>
          <option value="allTime">All Time</option>
        </select>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div className="p-4 border border-gray-200 rounded-lg shadow-lg">
          <h3 className="text-xl font-semibold mb-4">Monthly Earnings Comparison</h3>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={filteredData}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="name" />
              <YAxis />
              <Tooltip />
              <Legend />
              <Bar dataKey="value" fill="#8884d8" />
            </BarChart>
          </ResponsiveContainer>
        </div>

        <div className="p-4 border border-gray-200 rounded-lg shadow-lg">
          <h3 className="text-xl font-semibold mb-4">Lifetime Earnings (Yearly Comparison)</h3>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={lifetimeChartData}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="year" />
              <YAxis />
              <Tooltip />
              <Legend />
              {Object.keys(statistics).map((flatId, index) => (
                <Bar key={flatId} dataKey={`Flat ${flatId}`} fill={COLORS[index % COLORS.length]} />
              ))}
            </BarChart>
          </ResponsiveContainer>
        </div>

        <div className="p-4 border border-gray-200 rounded-lg shadow-lg">
          <h3 className="text-xl font-semibold mb-4">Total Earnings (Line Chart)</h3>
          <ResponsiveContainer width="100%" height={300}>
            <LineChart data={filteredData}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="name" />
              <YAxis />
              <Tooltip />
              <Legend />
              <Line type="monotone" dataKey="value" stroke="#8884d8" />
            </LineChart>
          </ResponsiveContainer>
        </div>
      </div>

      <div className="overflow-x-auto mb-8">
        <table className="min-w-full table-auto">
          <thead className="bg-gray-100">
            <tr>
              <th className="px-4 py-2 border-b">Flat</th>
              <th className="px-4 py-2 border-b">Month</th>
              <th className="px-4 py-2 border-b">Earnings</th>
              <th className="px-4 py-2 border-b">Year</th>
            </tr>
          </thead>
          <tbody>
            {Object.entries(statistics).flatMap(([flatId, flatData]) =>
              flatData.earnings.map((earning, index) => (
                <tr key={`${flatId}-${index}`}>
                  <td className="px-4 py-2 border-b">Flat {flatId}</td>
                  <td className="px-4 py-2 border-b">{earning.month_year}</td>
                  <td className="px-4 py-2 border-b">{earning.amount}</td>
                  <td className="px-4 py-2 border-b">{earning.month_year.split('-')[0]}</td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      <div className="flex justify-between">
        <button
          onClick={updateRandomFlat}
          className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700"
        >
          Update Random Flat
        </button>
        <button
          onClick={downloadCSV}
          className="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700"
        >
          Download CSV
        </button>
      </div>
    </div>
  );
}

