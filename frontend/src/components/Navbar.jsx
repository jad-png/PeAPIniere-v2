import React, { useEffect } from "react";
import { assets } from "../assets/assets.js";
import { Link } from "react-router-dom";
import { PiUserCircleLight } from "react-icons/pi";
import { IoCartOutline } from "react-icons/io5";
import useAuthStore from "../store/useAuthStore.js";

const Navbar = () => {
  const { user } = useAuthStore();
  console.log(user.data.user)

  return (
    <nav className="h-8 w-full px-8 text-gray-600 sticky z-50 top-0 shadow-md bg-gray-50">
      <div className="container mx-auto flex justify-between h-full items-center">
        <a className="flex items-center gap-1">
          <img src={assets.logo} className="size-5" alt="" />
          <span className="font-medium">PeAPIniere</span>
        </a>

        <ul className="text-sm flex items-center h-full">
          <Link
            to={"/"}
            className="flex items-center gap-1 hover:bg-gray-200 h-full px-4 cursor-pointer"
          >
            <span className="font-medium">Home</span>
          </Link>
          <Link
            to={"/plants"}
            className="flex items-center gap-1 hover:bg-gray-200 h-full px-4 cursor-pointer"
          >
            <span className="font-medium">Plants</span>
          </Link>
          <Link
            to={"/dashboard"}
            className="flex items-center gap-1 hover:bg-gray-200 h-full px-4 cursor-pointer"
          >
            <span className="font-medium">Dashboard</span>
          </Link>

          <Link
            to={"/account"}
            className="flex items-center gap-1 hover:bg-gray-200 h-full px-2 cursor-pointer"
          >
            <PiUserCircleLight className="size-5" />
            <span className="font-medium">
              {user ? user.data.user.fullname : "account"}
            </span>
            <img src={assets.chevron_down} className="size-3" alt="" />
          </Link>
          <Link
            to={"/cart"}
            className="flex items-center gap-1 hover:bg-gray-200 h-full px-2 cursor-pointer"
          >
            <IoCartOutline className="size-5" />
            <span className="font-medium">Cart</span>
          </Link>
        </ul>
      </div>
    </nav>
  );
};

export default Navbar;
