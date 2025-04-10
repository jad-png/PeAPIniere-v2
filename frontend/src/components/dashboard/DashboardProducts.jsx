const topProducts = [
  {
    id: 1,
    name: "Spathiphyllum",
    category: "Plantes Intérieurs",
    sales: 124,
    stock: 45,
  },
  {
    id: 2,
    name: "Pot géométrique P-03",
    category: "Pots & Jardinières",
    sales: 98,
    stock: 32,
  },
  {
    id: 3,
    name: "Pritchardia",
    category: "Palmiers",
    sales: 76,
    stock: 18,
  },
  {
    id: 4,
    name: "Begonia",
    category: "Plantes Intérieurs",
    sales: 65,
    stock: 27,
  },
  {
    id: 5,
    name: "Melia azedarach",
    category: "Arbres",
    sales: 54,
    stock: 12,
  },
];

export default function DashboardProducts() {
  return (
    <section className="bg-white rounded-lg shadow-sm p-6">
      <h2 className="text-xl font-semibold mb-4">Produits Populaires</h2>

      <div className="overflow-x-auto">
        <table className="w-full">
          <thead>
            <tr className="border-b border-gray-200">
              <th className="py-2 text-left text-xs font-medium text-gray-500">
                Produit
              </th>
              <th className="py-2 text-right text-xs font-medium text-gray-500">
                Ventes
              </th>
            </tr>
          </thead>
          <tbody>
            {topProducts.map((product) => (
              <tr key={product.id} className="border-b border-gray-200 text-sm">
                <td className="py-3">
                  <div className="font-medium">{product.name}</div>
                  <div className="text-xs text-gray-500">
                    {product.category}
                  </div>
                </td>
                <td className="py-3 text-right font-medium">{product.sales}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </section>
  );
}
