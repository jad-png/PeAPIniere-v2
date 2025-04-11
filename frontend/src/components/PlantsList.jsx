import { useEffect, useState } from "react";
import axios from "axios";
import AddPlantModal from "../components/AddPlantModal";
import useAuthStore from "../store/useAuthStore";

export default function PlantsList() {
  const { user } = useAuthStore();
  const [plants, setPlants] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  const fetchPlants = async () => {
    try {
      setLoading(true);
      const response = await axios.get("/api/plants");
      const plantsData = response.data.data || response.data;
      setPlants(Array.isArray(plantsData) ? plantsData : []);
    } catch (err) {
      setError(err.response?.data?.message || "Erreur de chargement");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchPlants();
  }, []);

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("fr-FR");
  };

  const handleAddPlant = (newPlant) => {
    setPlants([newPlant, ...plants]);
  };

  if (loading) return <div className="p-4 text-center">Chargement...</div>;
  if (error) return <div className="p-4 text-red-500 text-center">{error}</div>;

  return (
    <section className="bg-white p-4 rounded-lg shadow">
      <div className="flex justify-between items-center mb-4">
        <h2 className="text-xl font-semibold">Liste des Plantes</h2>
        {/* {user?.role_id === 1 && ( */}
        <button
          onClick={() => setIsModalOpen(true)}
          className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm"
        >
          Ajouter une plante
        </button>
        {/* )} */}
      </div>

      <div className="overflow-x-auto">
        <table className="min-w-full bg-white rounded-lg overflow-hidden text-xs">
          <thead className="text-xs bg-gray-50">
            <tr>
              <th className="py-3 px-4 text-left font-medium text-gray-500">
                ID
              </th>
              <th className="py-3 px-4 text-left font-medium text-gray-500">
                Image
              </th>
              <th className="py-3 px-4 text-left font-medium text-gray-500">
                Nom
              </th>
              <th className="py-3 px-4 text-left font-medium text-gray-500">
                Prix
              </th>
              <th className="py-3 px-4 text-left font-medium text-gray-500">
                Catégorie
              </th>
              <th className="py-3 px-4 text-left font-medium text-gray-500">
                Date d'ajout
              </th>
              <th className="py-3 px-4 text-right font-medium text-gray-500">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            {plants.length > 0 ? (
              plants.map((plant) => (
                <tr
                  key={plant.id}
                  className="border-b border-gray-200 hover:bg-gray-50"
                >
                  <td className="py-4 px-4 font-medium">#{plant.id}</td>
                  <td className="py-4 px-4">
                    <img
                      src="https://images.ctfassets.net/ihx0a8chifpc/GTlzd4xkx4LmWsG1Kw1BB/ad1834111245e6ee1da4372f1eb5876c/placeholder.com-1280x720.png?w=1920&q=60&fm=webp"
                      alt={plant.name}
                      className="w-12 h-12 object-cover rounded"
                    />
                  </td>
                  <td className="py-4 px-4 font-medium">
                    <div>
                      <p>{plant.name}</p>
                      <p className="text-xs text-gray-500">{plant.slug}</p>
                    </div>
                  </td>
                  <td className="py-4 px-4">{plant.price} MAD</td>
                  <td className="py-4 px-4">
                    {plant.category?.name || plant.category || "-"}
                  </td>
                  <td className="py-4 px-4">{formatDate(plant.created_at)}</td>
                  <td className="py-4 px-4 text-right">
                    <button className="text-blue-600 hover:text-blue-800 mr-2">
                      Éditer
                    </button>
                    <button className="text-red-600 hover:text-red-800">
                      Supprimer
                    </button>
                  </td>
                </tr>
              ))
            ) : (
              <tr>
                <td
                  colSpan={user?.role_id === 1 ? 7 : 6}
                  className="px-4 py-8 text-center text-gray-500"
                >
                  Aucune plante trouvée
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {/* Modal d'ajout */}
      <AddPlantModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        onPlantAdded={handleAddPlant}
      />
    </section>
  );
}
