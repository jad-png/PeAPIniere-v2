import axios from "axios";

const res = await axios.get("/api/categories");
const categories = res.data.data;

export default function ProductCategories() {
  return (
    <div className="container mx-auto w-full">
      <div className="flex items-center flex-wrap gap-2">
        {categories.map((category) => (
          <article
            key={category.id}
            className="rounded-lg transition-transform"
          >
            <div className="block">
              <div className="bg-white border border-gray-100 p-4 text-center">
                <label className="text-sm font-semibold text-gray-800">
                  {category.name}
                </label>
              </div>
            </div>
          </article>
        ))}
      </div>
    </div>
  );
}
