import axios from "axios";
import { Link } from "react-router-dom";

const res = await axios.get("/api/plants");
const plants = res.data.data;

export default function FeaturedPlants() {
  return (
    <div className="container mx-auto w-full">
      <div className="grid grid-cols-5 gap-2 max-lg:grid-cols-3 max-md:grid-cols-2 max-sm:grid-cols-1">
        {plants.map((plant) => (
          <article
            key={plant.id}
            className="overflow-hidden rounded-lg bg-white shadow-md transition-transform"
          >
            <Link to={"/plants/" + plant.slug} className="block">
              <div className="relative w-full">
                <img
                  src="https://images.ctfassets.net/ihx0a8chifpc/GTlzd4xkx4LmWsG1Kw1BB/ad1834111245e6ee1da4372f1eb5876c/placeholder.com-1280x720.png?w=1920&q=60&fm=webp"
                  alt={plant.name}
                  fill
                  className="object-cover"
                />
              </div>
              <div className="p-4">
                <p className="text-xs font-medium text-green-700 mb-1">
                  {plant.category.name}
                </p>
                <h3 className="text-md font-semibold text-gray-800 mb-2">
                  {plant.name}
                </h3>
                <p className="text-lg font-bold">
                  {plant.price} MAD{" "}
                  <span className="text-xs text-gray-500">/ TTC</span>
                </p>
              </div>
            </Link>
          </article>
        ))}
      </div>
    </div>
  );
}
