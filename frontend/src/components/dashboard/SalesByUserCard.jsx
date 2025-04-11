import axios from "axios";
import { useEffect, useState } from "react";

export default function SalesByUserCard() {
  const [salesData, setSalesData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchSalesData = async () => {
      try {
        setLoading(true);
        const response = await axios.get("/api/admin/sales");

        // Vérification de la réponse
        if (response.data.success && Array.isArray(response.data.data)) {
          setSalesData(response.data.data);
        } else {
          throw new Error("Format de données invalide");
        }
      } catch (err) {
        console.error("Erreur de récupération:", err);
        setError(err.message);
        setSalesData([]);
      } finally {
        setLoading(false);
      }
    };

    fetchSalesData();
  }, []);

  const validData = Array.isArray(salesData) ? salesData : [];

  const maxTotal =
    validData.reduce((max, item) => {
      const price = parseFloat(item.price_total) || 0;
      return price > max ? price : max;
    }, 0) * 1.2;

  return (
    <div className="bg-white p-6 rounded-lg shadow">
      <h2 className="text-lg font-semibold mb-4">Ventes par utilisateur</h2>
      {validData.length === 0 ? (
        <p className="text-gray-500">Aucune donnée disponible</p>
      ) : (
        <div className="space-y-4">
          {validData.map((user, index) => {
            const price = parseFloat(user.price_total) || 0;
            const widthPercentage = maxTotal > 0 ? (price / maxTotal) * 100 : 0;

            return (
              <div key={`${user.fullname}-${index}`} className="space-y-1">
                <div className="flex justify-between text-sm">
                  <span>{user.fullname || "Utilisateur inconnu"}</span>
                  <span className="font-medium">{price.toFixed(2)} MAD</span>
                </div>
                <div className="w-full bg-gray-200 rounded-full h-2.5">
                  <div
                    className="bg-green-600 h-2.5 rounded-full animate-progress"
                    style={{ width: `${widthPercentage}%` }}
                  ></div>
                </div>
              </div>
            );
          })}
        </div>
      )}
    </div>
  );
}
