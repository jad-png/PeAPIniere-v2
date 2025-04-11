import DashboardStats from "../components/dashboard/DashboardStats";
import DashboardCharts from "../components/dashboard/DashboardCharts";
import DashboardProducts from "../components/dashboard/DashboardProducts";
import DashboardOrders from "../components/dashboard/DashboardOrders";
import PlantsList from "../components/PlantsList";
import CategoriesList from "../components/dashboard/CategoriesList";
import SalesByUserCard from "../components/dashboard/SalesByUserCard";
import PopularPlantsCard from "../components/dashboard/PopularPlantsCard";
import CategoryDistributionCard from "../components/dashboard/CategoryDistributionCard";
import { useState } from "react";

const Dashboard = () => {
  const [categoriesData, setCategoriesData] = useState([]);

  return (
    <>
      <main className="flex-grow py-8">
        <div className="container mx-auto max-md:px-2">
          <div className="">
            <h1 className="text-xl md:text-2xl font-semibold mb-6">
              Tableau de bord Admin
            </h1>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div className="lg:col-span-2">
                <SalesByUserCard />
              </div>

              <div>
                <PopularPlantsCard />
              </div>

              <div>
                <CategoryDistributionCard data={categoriesData} />
              </div>
            </div>
          </div>

          <section className="mt-8">
            <DashboardOrders />
          </section>

          <section className="mt-8">
            <PlantsList />
          </section>

          <section className="mt-8">
            <CategoriesList />
          </section>
        </div>
      </main>
    </>
  );
};

export default Dashboard;
