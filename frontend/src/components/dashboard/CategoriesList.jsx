import { useEffect, useState } from "react";
import axios from "axios";
import AddCategoryModal from "../AddCategoryModal";
import useAuthStore from "../../store/useAuthStore";

export default function CategoriesList() {
  const { user } = useAuthStore();
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  const fetchCategories = async () => {
    try {
      setLoading(true);
      const response = await axios.get("/api/categories");
      const categoriesData = response.data.data || response.data;
      setCategories(Array.isArray(categoriesData) ? categoriesData : []);
    } catch (err) {
      setError(err.response?.data?.message || "Erreur de chargement");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchCategories();
  }, []);

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("fr-FR");
  };

  const handleAddCategory = (newCategory) => {
    setCategories([newCategory, ...categories]);
  };

  if (loading) return <div className="p-4 text-center">Chargement...</div>;
  if (error) return <div className="p-4 text-red-500 text-center">{error}</div>;

  return (
    <section className="bg-white p-4 rounded-lg shadow">
      <div className="flex justify-between items-center mb-4">
        <h2 className="text-xl font-semibold">Liste des Catégories</h2>
        <button
          onClick={() => setIsModalOpen(true)}
          className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm"
        >
          Ajouter une catégorie
        </button>
      </div>

      <div className="overflow-x-auto">
        <table className="min-w-full bg-white rounded-lg overflow-hidden text-xs">
          <thead className="text-sm bg-gray-50">
            <tr>
              <th className="py-3 px-4 text-left font-medium text-gray-500">
                ID
              </th>
              <th className="py-3 px-4 text-left font-medium text-gray-500">
                Nom
              </th>
              <th className="py-3 px-4 text-left font-medium text-gray-500">
                Date de création
              </th>
              <th className="py-3 px-4 text-left font-medium text-gray-500">
                Dernière modification
              </th>
              <th className="py-3 px-4 text-right font-medium text-gray-500">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            {categories.length > 0 ? (
              categories.map((category) => (
                <tr
                  key={category.id}
                  className="border-b border-gray-200 hover:bg-gray-50"
                >
                  <td className="py-4 px-4 font-medium">#{category.id}</td>
                  <td className="py-4 px-4 font-medium">
                    <p>{category.name}</p>
                  </td>
                  <td className="py-4 px-4">
                    {formatDate(category.created_at)}
                  </td>
                  <td className="py-4 px-4">
                    {formatDate(category.updated_at)}
                  </td>
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
                  colSpan={user?.role_id === 1 ? 5 : 4}
                  className="px-4 py-8 text-center text-gray-500"
                >
                  Aucune catégorie trouvée
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      <AddCategoryModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        onCategoryAdded={handleAddCategory}
      />
    </section>
  );
}
