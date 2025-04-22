import axios from "axios";
import { useEffect, useState } from "react";
import { useParams } from "react-router";
import useOrderStore from "../store/useOrderStore";
import useAuthStore from "../store/useAuthStore";

const ProductDetail = () => {
  const [quantity, setQuantity] = useState(1);
  const { slug } = useParams();
  const [plants, setPlants] = useState(null);
  const {
    makeOrder,
    loading: orderLoading,
    error: orderError,
  } = useOrderStore();
  const { user } = useAuthStore();

  useEffect(() => {
    const fetchPlant = async () => {
      const res = await axios.get(`/api/plants/${slug}`);
      setPlants(res.data);
    };

    fetchPlant();
  }, [slug]);

  const incrementQuantity = () => setQuantity((prev) => prev + 1);
  const decrementQuantity = () =>
    setQuantity((prev) => (prev > 1 ? prev - 1 : 1));

  const formatDate = (dateString) => {
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("fr-FR", options);
  };

  const addToCart = async () => {
    if (!user) {
      alert("Veuillez vous connecter pour passer une commande");
      return;
    }

    const orderData = {
      plants: [
        {
          plant_id: plants.id,
          quantity: quantity,
        },
      ],
    };

    try {
      await makeOrder(orderData);
      alert("Commande passée avec succès!");
    } catch (error) {
      console.error("Error creating order:", error);
    }
  };

  if (!plants) {
    return (
      <div className="text-center flex items-center justify-center flex-grow pt-36">
        <span>Loading...</span>
      </div>
    );
  }

  return (
    <div className="bg-gray-50 py-8">
      <div className="container mx-auto px-4">
        {orderError && (
          <div className="mb-4 p-4 bg-red-100 text-red-700 rounded">
            {orderError.message || "Erreur lors de la commande"}
          </div>
        )}

        <nav className="flex mb-6 text-sm">
          <a href="/" className="text-gray-500 hover:text-green-600">
            Accueil
          </a>
          <span className="mx-2 text-gray-500">/</span>
          <span className="text-gray-700 font-medium">{plants.name}</span>
        </nav>

        <div className="bg-white rounded-lg shadow-sm overflow-hidden">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 p-6">
            <div className="lg:col-span-1">
              <div className="mb-4">
                <img
                  src="https://images.ctfassets.net/ihx0a8chifpc/GTlzd4xkx4LmWsG1Kw1BB/ad1834111245e6ee1da4372f1eb5876c/placeholder.com-1280x720.png?w=1920&q=60&fm=webp"
                  alt={plants.name}
                  className="w-full h-80 object-cover rounded-lg"
                />
              </div>
            </div>

            <div className="lg:col-span-2">
              <div className="mb-6">
                <p className="text-sm font-medium text-green-600 mb-2">
                  {plants.category?.name || "PLANTES"}
                </p>
                <h1 className="text-3xl font-bold text-gray-800 mb-2">
                  {plants.name}
                </h1>
                <div className="flex items-center mb-4">
                  <span className="text-2xl font-bold text-blue-900">
                    {plants.price} MAD
                  </span>
                </div>
                <div className="mb-6">
                  <p className="text-gray-600 text-sm">{plants.description}</p>
                </div>
                <div className="mb-6">
                  <p className="text-sm text-gray-500">
                    Date d'ajout: {formatDate(plants.created_at)}
                  </p>
                </div>

                <div className="flex flex-wrap items-center gap-4">
                  <div className="flex items-center border border-gray-300 rounded-md">
                    <button
                      onClick={decrementQuantity}
                      className="px-3 py-2 text-gray-600 hover:bg-gray-100"
                      disabled={quantity <= 1}
                    >
                      -
                    </button>
                    <input
                      type="number"
                      min="1"
                      value={quantity}
                      onChange={(e) =>
                        setQuantity(Math.max(1, Number(e.target.value)))
                      }
                      className="w-12 text-center py-2 border-x border-gray-300"
                    />
                    <button
                      onClick={incrementQuantity}
                      className="px-3 py-2 text-gray-600 hover:bg-gray-100"
                    >
                      +
                    </button>
                  </div>

                  <button
                    onClick={addToCart}
                    disabled={orderLoading}
                    className={`flex-1 ${
                      orderLoading
                        ? "bg-green-400"
                        : "bg-green-600 hover:bg-green-700"
                    } text-white py-2 px-4 rounded-md flex items-center justify-center`}
                  >
                    {orderLoading ? "Traitement..." : "Ajouter au panier"}
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div className="border-t border-gray-200 p-6">
            <h2 className="font-bold mb-4">Description détaillée</h2>
            <p className="text-sm text-gray-600">{plants.description}</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductDetail;
