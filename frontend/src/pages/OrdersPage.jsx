import { useEffect, useState } from "react";
import axios from "axios";
import useAuthStore from "../store/useAuthStore";
import { Link } from "react-router-dom";

const OrdersPage = () => {
  const { user } = useAuthStore();
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchOrders = async () => {
      try {
        setLoading(true);
        const endpoint =
          user.data.user.role_id === 2 ? "/api/orders/all" : "/api/orders";
        const res = await axios.get(endpoint);

        // Gérer les réponses sous forme de tableau et d'objet
        let ordersData = res.data;
        if (ordersData && !Array.isArray(ordersData)) {
          if (ordersData.data) {
            ordersData = ordersData.data;
          } else {
            ordersData = [ordersData];
          }
        }

        setOrders(Array.isArray(ordersData) ? ordersData : []);
      } catch (err) {
        console.error("Erreur de récupération:", err);
        setError(
          err.response?.data?.message ||
            "Échec de la récupération des commandes"
        );
        setOrders([]);
      } finally {
        setLoading(false);
      }
    };

    fetchOrders();
  }, [user]);

  const handleStatusUpdate = async (orderId, newStatus) => {
    try {
      await axios.patch(`/api/orders/${orderId}/status`, { status: newStatus });
      setOrders((prevOrders) =>
        prevOrders.map((order) =>
          order.id === orderId ? { ...order, status: newStatus } : order
        )
      );
    } catch (err) {
      setError(
        err.response?.data?.message || "Échec de la mise à jour du statut"
      );
    }
  };

  const statusOptions = {
    pending: { label: "En attente", color: "bg-blue-100 text-blue-800" },
    "in preparation": {
      label: "En préparation",
      color: "bg-yellow-100 text-yellow-800",
    },
    delivred: { label: "Livré", color: "bg-green-100 text-green-800" },
    cancelled: { label: "Annulé", color: "bg-red-100 text-red-800" },
  };

  if (loading)
    return <div className="text-center py-8">Chargement des commandes...</div>;
  if (error)
    return <div className="text-center text-red-500 py-8">{error}</div>;

  return (
    <div className="container mx-auto px-2 py-8">
      <h1 className="text-lg font-bold mb-6">
        {user.data.user.role_id === 2
          ? "Toutes les commandes"
          : "Vos commandes"}
      </h1>

      {!orders || orders.length === 0 ? (
        <p>Aucune commande trouvée</p>
      ) : (
        <div className="overflow-x-auto">
          <table className="min-w-full bg-white rounded-lg overflow-hidden text-xs">
            <thead className="bg-gray-100">
              <tr>
                <th className="py-3 px-4 text-left">N° de commande</th>
                <th className="py-3 px-4 text-left">Date</th>
                <th className="py-3 px-4 text-left">Articles</th>
                <th className="py-3 px-4 text-left">Total</th>
                <th className="py-3 px-4 text-left">Statut</th>
                <th className="py-3 px-4 text-left">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200">
              {orders.map((order) => (
                <tr key={order.id}>
                  <td className="py-4 px-4">#{order.id}</td>
                  <td className="py-4 px-4">
                    {new Date(order.created_at).toLocaleDateString("fr-FR")}
                  </td>
                  <td className="py-4 px-4">
                    {order.plants?.length > 0 ? (
                      <ul className="list-disc pl-5">
                        {order.plants.map((plant) => (
                          <li key={plant.id}>
                            <Link
                              to={`/plants/${plant.slug}`}
                              className="hover:text-green-600"
                            >
                              {plant.name} (x{plant.pivot?.quantity || 1})
                            </Link>
                          </li>
                        ))}
                      </ul>
                    ) : (
                      <span className="text-gray-400">Aucun article</span>
                    )}
                  </td>
                  <td className="py-4 px-4">
                    {order.price_total || "0.00"} MAD
                  </td>
                  <td className="py-4 px-4">
                    <span
                      className={`px-2 py-1 rounded-full text-xs ${
                        statusOptions[order.status]?.color ||
                        "bg-gray-100 text-gray-800"
                      }`}
                    >
                      {statusOptions[order.status]?.label || order.status}
                    </span>
                  </td>
                  <td className="py-4 px-4 space-x-2">
                    {/* Le client peut annuler les commandes en attente */}
                    {user.data.user.role_id === 3 &&
                      order.status === "pending" && (
                        <button
                          onClick={() =>
                            handleStatusUpdate(order.id, "cancelled")
                          }
                          className="text-red-600 hover:text-red-800 hover:bg-red-50 px-2 text-sm font-medium"
                        >
                          Annuler
                        </button>
                      )}

                    {/* L'employé peut mettre à jour le statut */}
                    {user.data.user.role_id === 2 &&
                      order.status !== "cancelled" && (
                        <select
                          value={order.status}
                          onChange={(e) =>
                            handleStatusUpdate(order.id, e.target.value)
                          }
                          className="border rounded px-2 py-1 text-sm"
                        >
                          <option value="pending">En attente</option>
                          <option value="in preparation">En préparation</option>
                          <option value="delivred">Livré</option>
                        </select>
                      )}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
};

export default OrdersPage;
