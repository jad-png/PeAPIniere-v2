import DashboardStats from "../components/dashboard/DashboardStats";
import DashboardCharts from "../components/dashboard/DashboardCharts";
import DashboardProducts from "../components/dashboard/DashboardProducts";
import DashboardOrders from "../components/dashboard/DashboardOrders";
import PlantsList from "../components/PlantsList";

const Dashboard = () => {
  return (
    <>
      <main className="flex-grow py-8">
        <div className="container mx-auto max-md:px-2">
          <DashboardStats />

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
            <div className="lg:col-span-2">
              <DashboardCharts />
            </div>
            <div>
              <DashboardProducts />
            </div>
          </div>

          <section className="mt-8">
            <DashboardOrders />
          </section>

          <section className="mt-8">
            <PlantsList />
          </section>
        </div>
      </main>
    </>
  );
};

export default Dashboard;
