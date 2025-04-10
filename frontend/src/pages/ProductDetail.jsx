import axios from "axios";
import { useEffect, useState } from "react";
import { useParams } from "react-router";

const ProductDetail = () => {
  const [quantity, setQuantity] = useState(1);

  const product = {
    id: "prod-123",
    name: "Spathiphyllum",
    category: "PLANTES INTÉRIEURS",
    price: 99,
    description:
      "Le Spathiphyllum, également connu sous le nom de « Fleur de Lune » ou « Lys de Paix », est une plante d'intérieur populaire appréciée pour son feuillage vert brillant et ses fleurs blanches élégantes. Cette plante tropicale est originaire des forêts humides d'Amérique centrale et du Sud. Elle est non seulement esthétique mais aussi connue pour ses propriétés purificatrices d'air, éliminant efficacement les toxines comme le benzène, le formaldéhyde et le trichloréthylène.",
    createdAt: "2023-03-15",
    image:
      "https://images.ctfassets.net/ihx0a8chifpc/GTlzd4xkx4LmWsG1Kw1BB/ad1834111245e6ee1da4372f1eb5876c/placeholder.com-1280x720.png?w=1920&q=60&fm=webp",
  };

  const { slug } = useParams();
  const [plants, setPlants] = useState(null);

  useEffect(() => {
    const fetchPlant = async () => {
      const res = await axios.get("/api/plants/" + slug);
      setPlants(res.data);
    };

    fetchPlant();
  }, []);

  // Fonction pour incrémenter la quantité
  const incrementQuantity = () => {
    setQuantity(quantity + 1);
  };

  // Fonction pour décrémenter la quantité
  const decrementQuantity = () => {
    if (quantity > 1) {
      setQuantity(quantity - 1);
    }
  };

  // Fonction pour formater la date
  const formatDate = (dateString) => {
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("fr-FR", options);
  };

  // Fonction pour ajouter au panier
  const addToCart = () => {
    alert(`${quantity} ${product?.name} ajouté(s) au panier`);
    // Logique d'ajout au panier ici
  };

  return plants ? (
    <>
      <div className="bg-gray-50 py-8">
        <div className="container mx-auto px-4">
          {/* Fil d'Ariane */}
          <nav className="flex mb-6 text-sm">
            <a href="/" className="text-gray-500 hover:text-green-600">
              Accueil
            </a>
            <span className="mx-2 text-gray-500">/</span>
            <span className="text-gray-700 font-medium">{plants.name}</span>
          </nav>

          {/* Section principale du produit */}
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 p-6">
              {/* Galerie d'images */}
              <div className="lg:col-span-1">
                <div className="mb-4">
                  <img
                    src="https://images.ctfassets.net/ihx0a8chifpc/GTlzd4xkx4LmWsG1Kw1BB/ad1834111245e6ee1da4372f1eb5876c/placeholder.com-1280x720.png?w=1920&q=60&fm=webp"
                    alt={plants.name}
                    className="w-full h-80 object-cover rounded-lg"
                  />
                </div>
              </div>

              {/* Informations produit */}
              <div className="lg:col-span-2">
                <div className="mb-6">
                  <p className="text-sm font-medium text-green-600 mb-2">
                    {plants.category.name}
                  </p>
                  <h1 className="text-3xl font-bold text-gray-800 mb-2">
                    {plants.name}
                  </h1>

                  {/* Prix */}
                  <div className="flex items-center mb-4">
                    <span className="text-2xl font-bold text-blue-900">
                      {plants.price} MAD
                    </span>
                  </div>

                  {/* Description courte */}
                  <div className="mb-6">
                    <p className="text-gray-600 text-sm">
                      {plants.description}
                    </p>
                  </div>

                  {/* Date de création */}
                  <div className="mb-6">
                    <p className="text-sm text-gray-500">
                      Date d'ajout: {formatDate(plants.created_at)}
                    </p>
                  </div>

                  {/* Sélecteur de quantité et boutons d'action */}
                  <div className="">
                    <div className="flex flex-wrap items-center gap-4">
                      <div className="flex items-center border border-gray-300 rounded-md">
                        <button
                          onClick={decrementQuantity}
                          className="px-3 py-2 text-gray-600 hover:bg-gray-100"
                          disabled={quantity <= 1}
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            className="h-4 w-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                          >
                            <path
                              fillRule="evenodd"
                              d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                              clipRule="evenodd"
                            />
                          </svg>
                        </button>
                        <input
                          type="number"
                          min="1"
                          value={quantity}
                          onChange={(e) =>
                            setQuantity(Number.parseInt(e.target.value) || 1)
                          }
                          className="w-12 text-center py-2 border-x border-gray-300"
                        />
                        <button
                          onClick={incrementQuantity}
                          className="px-3 py-2 text-gray-600 hover:bg-gray-100"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            className="h-4 w-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                          >
                            <path
                              fillRule="evenodd"
                              d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                              clipRule="evenodd"
                            />
                          </svg>
                        </button>
                      </div>

                      <button
                        onClick={addToCart}
                        className="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md flex items-center justify-center"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          className="h-5 w-5 mr-2"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                        >
                          <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                        Ajouter au panier
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div className="border-t border-gray-200">
              <div className="p-6">
                <div className="mb-8">
                  <h2 className="font-bold mb-4">Description détaillée</h2>
                  <div className="text-sm text-gray-600">
                    <p>{plants.description}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  ) : (
    <>
      <div className="text-center flex items-center justify-center flex-grow pt-36">
        <span>Loading...</span>
      </div>
    </>
  );
};

export default ProductDetail;
