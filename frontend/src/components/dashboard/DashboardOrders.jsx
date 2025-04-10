import { useState } from "react";

const orders = [
  {
    id: "CMD-2023-1254",
    customer: "Ahmed Benali",
    date: "2023-04-08",
    amount: 1250,
    status: "completed",
  },
  {
    id: "CMD-2023-1253",
    customer: "Fatima Zahra",
    date: "2023-04-07",
    amount: 850,
    status: "processing",
  },
  {
    id: "CMD-2023-1252",
    customer: "Karim Idrissi",
    date: "2023-04-07",
    amount: 2100,
    status: "completed",
  },
  {
    id: "CMD-2023-1251",
    customer: "Nadia Tazi",
    date: "2023-04-06",
    amount: 450,
    status: "completed",
  },
  {
    id: "CMD-2023-1250",
    customer: "Youssef Alami",
    date: "2023-04-05",
    amount: 1800,
    status: "cancelled",
  },
  {
    id: "CMD-2023-1249",
    customer: "Samira Bennani",
    date: "2023-04-05",
    amount: 750,
    status: "processing",
  },
  {
    id: "CMD-2023-1248",
    customer: "Omar Chraibi",
    date: "2023-04-04",
    amount: 1350,
    status: "completed",
  },
  {
    id: "CMD-2023-1247",
    customer: "Leila Mansouri",
    date: "2023-04-03",
    amount: 920,
    status: "completed",
  },
];

export default function DashboardOrders() {
  const [filter, setFilter] = useState("all");

  const filteredOrders =
    filter === "all"
      ? orders
      : orders.filter((order) => order.status === filter);

  const getStatusClass = (status) => {
    switch (status) {
      case "completed":
        return "bg-green-100 text-green-800";
      case "processing":
        return "bg-blue-100 text-blue-800";
      case "cancelled":
        return "bg-red-100 text-red-800";
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
      default:
        return status;
    }
  };

  return (
    <section className="bg-white p-4">
      <div className="flex flex-wrap items-center justify-between mb-6">
        <h2 className="text-xl font-semibold">Commandes Récentes</h2>
        <div className="flex space-x-2 mt-2 sm:mt-0">
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
        </div>
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
              <th className="py-2 text-right text-sm font-medium text-gray-500">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            {filteredOrders.map((order) => (
              <tr key={order.id} className="border-b border-gray-200 text-xs">
                <td className="py-3 font-medium">{order.id}</td>
                <td className="py-3">{order.customer}</td>
                <td className="py-3">
                  {new Date(order.date).toLocaleDateString("fr-FR")}
                </td>
                <td className="py-3 text-right font-medium">
                  {order.amount} MAD
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
                <td className="py-3 text-right">
                  <button className="text-gray-500 hover:text-green-600">
                    <span className="sr-only">Voir</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      className="h-5 w-5"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                      />
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                      />
                    </svg>
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </section>
  );
}
