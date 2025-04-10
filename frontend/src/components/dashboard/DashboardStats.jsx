import { useState } from "react";

const stats = [
  {
    id: 1,
    title: "Ventes Totales",
    value: "152,430 MAD",
    change: "+12.5%",
    isPositive: true,
  },
  {
    id: 2,
    title: "Commandes",
    value: "1,245",
    change: "+8.2%",
    isPositive: true,
  },
  {
    id: 3,
    title: "Clients",
    value: "854",
    change: "+15.3%",
    isPositive: true,
  },
  {
    id: 4,
    title: "Taux de Conversion",
    value: "3.2%",
    change: "-0.4%",
    isPositive: false,
  },
];

export default function DashboardStats() {
  const [period, setPeriod] = useState("month");

  return (
    <section>
      <div className="flex flex-wrap items-center justify-between mb-4">
        <h2 className="text-xl font-semibold">Statistiques</h2>
        <div className="flex space-x-2 mt-2 sm:mt-0">
          <button
            className={`px-3 py-1 text-sm rounded-md ${
              period === "week"
                ? "bg-green-600 text-white"
                : "bg-gray-200 text-gray-700"
            }`}
            onClick={() => setPeriod("week")}
          >
            Semaine
          </button>
          <button
            className={`px-3 py-1 text-sm rounded-md ${
              period === "month"
                ? "bg-green-600 text-white"
                : "bg-gray-200 text-gray-700"
            }`}
            onClick={() => setPeriod("month")}
          >
            Mois
          </button>
          <button
            className={`px-3 py-1 text-sm rounded-md ${
              period === "year"
                ? "bg-green-600 text-white"
                : "bg-gray-200 text-gray-700"
            }`}
            onClick={() => setPeriod("year")}
          >
            Année
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {stats.map((stat) => (
          <article key={stat.id} className="bg-white rounded-lg shadow-sm p-6">
            <h3 className="text-gray-500 text-sm font-medium mb-1">
              {stat.title}
            </h3>
            <p className="text-2xl font-bold mb-2">{stat.value}</p>
            <div
              className={`text-sm font-medium ${
                stat.isPositive ? "text-green-600" : "text-red-600"
              }`}
            >
              {stat.change}{" "}
              {period === "week"
                ? "cette semaine"
                : period === "month"
                ? "ce mois"
                : "cette année"}
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}
