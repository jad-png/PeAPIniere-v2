export default function PopularPlantsCard({ data = [] }) {
  const validData = Array.isArray(data) ? data : [];
  const totalOrders = validData.reduce(
    (sum, plant) => sum + (plant.count || 0),
    0
  );

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
