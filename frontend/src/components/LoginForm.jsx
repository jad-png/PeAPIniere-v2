import React, { useState } from "react";
import { FaApple } from "react-icons/fa6";
import { FaGoogle, FaGithub } from "react-icons/fa";
import { Link, useNavigate } from "react-router-dom";
import useAuthStore from "../store/useAuthStore.js";

const LoginForm = () => {
  const navigate = useNavigate();
  const { login, loading, error } = useAuthStore();

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  const handleLogin = async (e) => {
    e.preventDefault();
    await login({ email: email, password: password });

    if (useAuthStore.getState().user) {
      navigate("/");
    }
  };

  return (
    <div className="flex flex-col gap-6 shadow-md">
      <div className="grid p-0 md:grid-cols-2">
        <form className="p-6 md:p-8 bg-white" onSubmit={handleLogin}>
          <div className="flex flex-col gap-6">
            <div className="flex flex-col items-center text-center">
              <h1 className="text-2xl font-bold">Welcome back</h1>
              <p className="text-gray-500 text-sm">
                Login to your Acme Inc account
              </p>
            </div>
            <span className="text-red-700 bg-red-50 px-1">{error}</span>
            <div className="grid gap-2 text-sm">
              <label htmlFor="email">Email</label>
              <input
                id="email"
                type="email"
                placeholder="m@example.com"
                className="p-1 rounded-md border border-gray-200"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
              />
            </div>
            <div className="grid gap-2 text-sm">
              <div className="flex items-center">
                <label htmlFor="password">Password</label>
                <a
                  href=""
                  className="ml-auto text-xs underline-offset-2 hover:underline"
                >
                  Forgot your password?
                </a>
              </div>
              <input
                id="password"
                type="password"
                className="p-1 rounded-md border border-gray-200"
                required
                value={password}
                onChange={(e) => setPassword(e.target.value)}
              />
            </div>
            <button
              type="submit"
              className={`w-full p-2 font-medium rounded-md ${
                true == true ? "bg-gray-700 text-white" : "bg-black text-white"
              }`}
            >
              {/* {loading ? "Login in ..." : "Login"} */}
              Login
            </button>
            <div className="relative text-center text-sm after:absolute after:inset-0 after:top-1/2 after:z-0 after:flex after:items-center after:border-t after:border-border">
              <span className="relative z-10 text-gray-500 bg-white px-2 text-xs">
                Or continue with
              </span>
            </div>
            <div className="grid grid-cols-3 gap-4">
              <div className="border border-gray-200 text-center p-2">
                <FaApple className="size-5 w-full" />
              </div>
              <div className="border border-gray-200 text-center p-2">
                <FaGoogle className="size-5 w-full" />
              </div>
              <div className="border border-gray-200 text-center p-2">
                <FaGithub className="size-5 w-full" />
              </div>
            </div>
            <div className="text-center text-xs">
              Don&apos;t have an account?{" "}
              <Link to={"/register"} className="underline underline-offset-4">
                Sign up
              </Link>
            </div>
          </div>
        </form>
        <div className="relative hidden bg-muted md:block">
          <img
            src="https://www.paysagiste.info/wp-content/uploads/2017/04/shutterstock_74581096.jpg"
            alt="Image"
            className="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
          />
        </div>
      </div>
    </div>
  );
};

export default LoginForm;
