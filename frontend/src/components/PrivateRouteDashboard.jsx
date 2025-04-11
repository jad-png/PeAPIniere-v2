import { Navigate, Outlet } from "react-router-dom";
import useAuthStore from "../store/useAuthStore";

const PrivateRoute = () => {
  const { user } = useAuthStore();

  return user.data.user.role_id == 1 ? <Outlet /> : <Navigate to="/" />;
};

export default PrivateRoute;
