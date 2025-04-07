import { Outlet } from "react-router";

function App() {
  return (
    <div className="flex flex-col min-h-screen justify-between">
      <main className="flex-grow">
        <Outlet />
      </main>
    </div>
  );
}

export default App;
