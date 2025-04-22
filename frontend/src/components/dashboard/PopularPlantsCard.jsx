import { useEffect, useState } from "react";
import axios from "axios";

export default function PopularPlantsCard() {
  const [plantsData, setPlantsData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchPopularPlants = async () => {
      try {
        setLoading(true);
        const response = await axios.get("/api/admin/popular-plants");

        // Vérification de la réponse
        if (response.data.success && Array.isArray(response.data.data)) {
          setPlantsData(response.data.data);
        } else {
          throw new Error("Format de données invalide");
        }
      } catch (err) {
        console.error("Erreur de récupération:", err);
        setError(err.message);
        setPlantsData([]);
      } finally {
        setLoading(false);
      }
    };

    fetchPopularPlants();
  }, []);

  // Calcul des données pour l'affichage
  const validData = Array.isArray(plantsData) ? plantsData : [];
  const totalOrders = validData.reduce(
    (sum, plant) => sum + (plant.count || 0),
    0
  );

  if (loading)
    return <div className="p-4 text-center">Chargement en cours...</div>;
  if (error)
    return <div className="p-4 text-red-500 text-center">Erreur: {error}</div>;

  return (
    <div className="bg-white p-6 rounded-lg shadow">
      <h2 className="text-lg font-semibold mb-4">Plantes populaires</h2>
      {validData.length === 0 ? (
        <p className="text-gray-500">Aucune donnée disponible</p>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {validData.map((plant, index) => {
            const count = plant.count || 0;
            const percentage =
              totalOrders > 0 ? Math.round((count / totalOrders) * 100) : 0;
            return (
              <div
                key={`${plant.name}-${index}`}
                className="flex items-center space-x-3"
              >
                <div className="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                  <span className="text-blue-800 text-xs font-medium">
                    {count}
                  </span>
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium text-gray-900 truncate">
                    {plant.name || "Plante sans nom"}
                  </p>
                  <div className="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                    <div
                      className="bg-green-600 h-1.5 rounded-full animate-progress"
                      style={{ width: `${percentage}%` }}
                    ></div>
                  </div>
                </div>
                <span className="text-xs text-gray-500">{percentage}%</span>
              </div>
            );
          })}
        </div>
      )}
    </div>
  );
}
