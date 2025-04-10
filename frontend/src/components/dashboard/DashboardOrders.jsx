import { useState, useEffect } from "react";
import axios from "axios";
import useAuthStore from "../../store/useAuthStore";

export default function DashboardOrders() {
  const { user } = useAuthStore();
  // const [filter, setFilter] = useState("all");
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchOrders = async () => {
      try {
        setLoading(true);
        const response = await axios.get("/api/orders", {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        // Handle different API response structures
        const rawData = response.data.data || response.data;
        const ordersData = Array.isArray(rawData) ? rawData : [];

        setOrders(ordersData);
      } catch (err) {
        console.error("Order fetch error:", {
          status: err.response?.status,
          data: err.response?.data,
          message: err.message,
        });
        setError(err.response?.data?.message || "Erreur de chargement");
      } finally {
        setLoading(false);
      }
    };

    fetchOrders();
  }, []);

  // const filteredOrders =
  //   filter === "all"
  //     ? orders
  //     : orders.filter((order) => order.status === filter);

  const getStatusClass = (status) => {
    switch (status) {
      case "completed":
        return "bg-green-100 text-green-800";
      case "processing":
        return "bg-blue-100 text-blue-800";
      case "cancelled":
        return "bg-red-100 text-red-800";
      case "delivred": // Added to match your database schema
        return "bg-purple-100 text-purple-800";
      case "pending": // Added to match your database schema
        return "bg-yellow-100 text-yellow-800";
      default:
        return "bg-gray-100 text-gray-800";
    }
  };

  const getStatusText = (status) => {
    switch (status) {
      case "completed":
        return "Complétée";
      case "processing":
        return "En traitement";
      case "cancelled":
        return "Annulée";
      case "delivred": // Added to match your database schema
        return "Livrée";
      case "pending": // Added to match your database schema
        return "En attente";
      case "in preparation": // Added to match your database schema
        return "En préparation";
      default:
        return status;
    }
  };

  if (loading) {
    return (
      <section className="bg-white p-4">
        <div className="flex justify-center items-center h-64">
          <p>Chargement des commandes...</p>
        </div>
      </section>
    );
  }

  if (error) {
    return (
      <section className="bg-white p-4">
        <div className="flex justify-center items-center h-64 text-red-500">
          <p>{error}</p>
        </div>
      </section>
    );
  }

  return (
    <section className="bg-white p-4">
      <div className="flex flex-wrap items-center justify-between mb-6">
        <h2 className="text-xl font-semibold">Commandes Récentes</h2>
        {/* <div className="flex space-x-2 mt-2 sm:mt-0">
          <button
            className={`px-3 py-1 text-sm rounded-md ${
              filter === "all"
                ? "bg-green-600 text-white"
                : "bg-gray-200 text-gray-700"
            }`}
            onClick={() => setFilter("all")}
          >
            Toutes
          </button>
          <button
            className={`px-3 py-1 text-sm rounded-md ${
              filter === "completed"
                ? "bg-green-600 text-white"
                : "bg-gray-200 text-gray-700"
            }`}
            onClick={() => setFilter("completed")}
          >
            Complétées
          </button>
          <button
            className={`px-3 py-1 text-sm rounded-md ${
              filter === "processing"
                ? "bg-green-600 text-white"
                : "bg-gray-200 text-gray-700"
            }`}
            onClick={() => setFilter("processing")}
          >
            En traitement
          </button>
          <button
            className={`px-3 py-1 text-sm rounded-md ${
              filter === "cancelled"
                ? "bg-green-600 text-white"
                : "bg-gray-200 text-gray-700"
            }`}
            onClick={() => setFilter("cancelled")}
          >
            Annulées
          </button>
        </div> */}
      </div>

      <div className="overflow-x-auto">
        <table className="w-full text-sm">
          <thead>
            <tr className="border-b border-gray-200">
              <th className="py-2 text-left text-xs font-medium text-gray-500">
                ID
              </th>
              <th className="py-2 text-left text-sm font-medium text-gray-500">
                Client
              </th>
              <th className="py-2 text-left text-sm font-medium text-gray-500">
                Date
              </th>
              <th className="py-2 text-right text-sm font-medium text-gray-500">
                Montant
              </th>
              <th className="py-2 text-right text-sm font-medium text-gray-500">
                Statut
              </th>
            </tr>
          </thead>
          <tbody>
            {orders.length > 0 ? (
              orders.map((order) => (
                <tr key={order.id} className="border-b border-gray-200 text-xs">
                  <td className="py-3 font-medium">#{order.id}</td>
                  <td className="py-3">
                    {order.user?.fullname || "Client inconnu"}
                  </td>
                  <td className="py-3">
                    {new Date(order.created_at).toLocaleDateString("fr-FR")}
                  </td>
                  <td className="py-3 text-right font-medium">
                    {order.price_total} MAD
                  </td>
                  <td className="py-3 text-right">
                    <span
                      className={`px-2 py-1 text-xs rounded-full ${getStatusClass(
                        order.status
                      )}`}
                    >
                      {getStatusText(order.status)}
                    </span>
                  </td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="5" className="py-4 text-center text-gray-500">
                  Aucune commande trouvée
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </section>
  );
}
