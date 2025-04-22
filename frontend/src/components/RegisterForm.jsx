import React, { useState } from "react";
import { FaApple, FaGoogle, FaGithub } from "react-icons/fa";
import { Link, useNavigate } from "react-router-dom";
import useAuthStore from "../store/useAuthStore.js";
import axios from "axios";

const RegisterForm = () => {
  const navigate = useNavigate();
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);

  const [formData, setFormData] = useState({
    fullname: "",
    email: "",
    password: "",
    password_confirmation: "",
    role_id: 3,
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleRegister = async (e) => {
    e.preventDefault();
    setLoading(true);

    try {
      await axios.post("/api/register", formData);
      navigate("/login");
    } catch (error) {
      setError(error.response.data);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="flex flex-col gap-6 shadow-md">
      <div className="grid p-0 md:grid-cols-2">
        <form className="p-6 md:p-8 bg-white" onSubmit={handleRegister}>
          <div className="flex flex-col gap-6">
            <div className="flex flex-col items-center text-center">
              <h1 className="text-2xl font-bold">Create an account</h1>
              <p className="text-gray-500 text-sm">Join Acme Inc today</p>
            </div>
            <span className="text-red-700 bg-red-50 px-1">
              {error?.message}
            </span>

            {/* Full Name Field */}
            <div className="grid gap-2 text-sm">
              <label htmlFor="fullname">Full Name</label>
              <input
                id="fullname"
                name="fullname"
                type="text"
                placeholder="John Doe"
                className="p-1 rounded-md border border-gray-200"
                required
                value={formData.fullname}
                onChange={handleChange}
              />
              <span className="text-red-700 bg-red-50 px-1">
                {error?.data?.fullname}
              </span>
            </div>

            {/* Email Field */}
            <div className="grid gap-2 text-sm">
              <label htmlFor="email">Email</label>
              <input
                id="email"
                name="email"
                type="email"
                placeholder="m@example.com"
                className="p-1 rounded-md border border-gray-200"
                required
                value={formData.email}
                onChange={handleChange}
              />
              <span className="text-red-700 bg-red-50 px-1">
                {error?.data?.email}
              </span>
            </div>

            {/* Password Field */}
            <div className="grid gap-2 text-sm">
              <label htmlFor="password">Password</label>
              <input
                id="password"
                name="password"
                type="password"
                className="p-1 rounded-md border border-gray-200"
                required
                value={formData.password}
                onChange={handleChange}
              />
              <span className="text-red-700 bg-red-50 px-1">
                {error?.data?.password}
              </span>
            </div>

            {/* Password Confirmation Field */}
            <div className="grid gap-2 text-sm">
              <label htmlFor="password_confirmation">Confirm Password</label>
              <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                className="p-1 rounded-md border border-gray-200"
                required
                value={formData.password_confirmation}
                onChange={handleChange}
              />
            </div>

            {/* Role Selection */}
            <div className="grid gap-2 text-sm">
              <label htmlFor="role_id">Account Type</label>
              <select
                id="role_id"
                name="role_id"
                className="p-1 rounded-md border border-gray-200"
                value={formData.role_id}
                onChange={handleChange}
              >
                <option value={3}>Client</option>
                <option value={2}>Employee</option>
              </select>
            </div>

            <button
              type="submit"
              className={`w-full p-2 font-medium rounded-md ${
                loading ? "bg-gray-700 text-white" : "bg-black text-white"
              }`}
              disabled={loading}
            >
              {loading ? "Creating account..." : "Register"}
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
              Already have an account?{" "}
              <Link to="/login" className="underline underline-offset-4">
                Sign in
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

export default RegisterForm;
