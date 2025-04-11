import { useEffect, useState } from "react";
import axios from "axios";

export default function CategoryDistributionCard() {
  const [categoriesData, setCategoriesData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchCategoriesData = async () => {
      try {
        setLoading(true);
        const response = await axios.get("/api/admin/plants-repartition");

        // Vérification de la réponse
        if (response.data.success && Array.isArray(response.data.data)) {
          setCategoriesData(response.data.data);
        } else {
          throw new Error("Format de données invalide");
        }
      } catch (err) {
        console.error("Erreur de récupération:", err);
        setError(err.message);
        setCategoriesData([]);
      } finally {
        setLoading(false);
      }
    };

    fetchCategoriesData();
  }, []);

  // Préparation des données pour l'affichage
  const validData = Array.isArray(categoriesData) ? categoriesData : [];
  const filteredData = validData.filter((cat) => (cat.count || 0) > 0);

  if (loading)
    return <div className="p-4 text-center">Chargement en cours...</div>;
  if (error)
    return <div className="p-4 text-red-500 text-center">Erreur: {error}</div>;

  return (
    <div className="bg-white p-6 rounded-lg shadow">
      <h2 className="text-lg font-semibold mb-4">Répartition par catégorie</h2>
      {filteredData.length === 0 ? (
        <p className="text-gray-500">Aucune donnée disponible</p>
      ) : (
        <div className="space-y-3">
          {filteredData.map((category, index) => (
            <div
              key={`${category.name}-${index}`}
              className="flex items-center"
            >
              <span className="w-32 text-sm text-gray-600 truncate">
                {category.name || "Catégorie sans nom"}
              </span>
              <div className="flex-1 mx-2">
                <div className="flex items-center">
                  <div className="w-full bg-gray-200 rounded-full h-2.5">
                    <div
                      className="bg-green-600 h-2.5 rounded-full animate-progress"
                      style={{ width: `${(category.count || 0) * 20}%` }}
                    ></div>
                  </div>
                </div>
              </div>
              <span className="text-sm font-medium w-8 text-right">
                {category.count}
              </span>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
