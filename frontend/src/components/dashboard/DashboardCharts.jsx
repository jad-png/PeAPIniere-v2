import { Line, Pie } from "react-chartjs-2";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  ArcElement,
  Title,
  Tooltip,
  Legend,
  Filler,
} from "chart.js";

// Enregistrer les composants Chart.js nécessaires
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  ArcElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

const DashboardCharts = () => {
  // Données pour le graphique des ventes mensuelles
  const monthlySalesData = {
    labels: [
      "Jan",
      "Fév",
      "Mar",
      "Avr",
      "Mai",
      "Juin",
      "Juil",
      "Août",
      "Sep",
      "Oct",
      "Nov",
      "Déc",
    ],
    datasets: [
      {
        label: "Ventes Mensuelles",
        data: [
          12500, 15000, 18200, 14800, 16300, 19500, 22000, 21500, 24000, 26500,
          28000, 30000,
        ],
        borderColor: "#7ab51d",
        backgroundColor: "rgba(122, 181, 29, 0.1)",
        tension: 0.3,
        pointBackgroundColor: "#7ab51d",
        pointBorderColor: "#fff",
        pointBorderWidth: 2,
        pointRadius: 4,
        fill: false,
      },
    ],
  };

  // Options pour le graphique des ventes mensuelles
  const monthlySalesOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false,
      },
      title: {
        display: false,
      },
      tooltip: {
        callbacks: {
          label: (context) => context.parsed.y.toLocaleString() + " MAD",
        },
      },
    },
    scales: {
      x: {
        grid: {
          display: false,
        },
      },
      y: {
        beginAtZero: true,
        ticks: {
          callback: (value) => value.toLocaleString() + " MAD",
        },
        grid: {
          color: "rgba(0, 0, 0, 0.05)",
        },
      },
    },
  };

  // Données pour le graphique des ventes par catégorie
  const categorySalesData = {
    labels: [
      "Plantes Intérieurs",
      "Pots & Jardinières",
      "Arbres",
      "Fleurs",
      "Palmiers",
    ],
    datasets: [
      {
        data: [35, 25, 15, 15, 10],
        backgroundColor: [
          "#7ab51d", // Plantes Intérieurs - vert vif
          "#4caf50", // Pots & Jardinières - vert moyen
          "#8bc34a", // Arbres - vert clair
          "#cddc39", // Fleurs - jaune-vert
          "#dce775", // Palmiers - jaune pâle
        ],
        borderWidth: 0,
      },
    ],
  };

  // Options pour le graphique des ventes par catégorie
  const categorySalesOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: "bottom",
        labels: {
          padding: 20,
          boxWidth: 12,
        },
      },
      tooltip: {
        callbacks: {
          label: (context) => context.label + ": " + context.parsed + "%",
        },
      },
      datalabels: {
        formatter: (value) => {
          return value + "%";
        },
        color: "#fff",
        font: {
          weight: "bold",
          size: 16,
        },
      },
    },
  };

  return (
    <div className="bg-white rounded-lg shadow-sm p-6">
      <h2 className="text-2xl font-bold mb-6">Analyse des Ventes</h2>

      <div className="mb-10">
        <h3 className="text-xl font-semibold mb-4">Ventes Mensuelles</h3>
        <div className="h-80">
          <Line data={monthlySalesData} options={monthlySalesOptions} />
        </div>
      </div>

      <div>
        <h3 className="text-xl font-semibold mb-4">Ventes par Catégorie</h3>
        <div className="h-80 flex justify-center">
          <div className="w-full max-w-md">
            <Pie data={categorySalesData} options={categorySalesOptions} />
          </div>
        </div>

        {/* Légende personnalisée pour le graphique en camembert */}
        <div className="flex flex-wrap justify-center mt-4 gap-4">
          {categorySalesData.labels.map((label, index) => (
            <div key={index} className="flex items-center">
              <div
                className="w-4 h-4 mr-2"
                style={{
                  backgroundColor:
                    categorySalesData.datasets[0].backgroundColor[index],
                }}
              ></div>
              <span className="text-sm">
                {label} ({categorySalesData.datasets[0].data[index]}%)
              </span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default DashboardCharts;
