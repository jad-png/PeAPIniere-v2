import React, { useState, useEffect } from "react";
import { assets } from "../assets/assets.js";
import { Link } from "react-router-dom";
import { PiUserCircleLight } from "react-icons/pi";
import { IoCartOutline } from "react-icons/io5";
import { FiMenu, FiX } from "react-icons/fi";
import useAuthStore from "../store/useAuthStore.js";

const Navbar = () => {
  const { user, logout } = useAuthStore();
  const [isOpen, setIsOpen] = useState(false);
  const [isMobile, setIsMobile] = useState(window.innerWidth < 768);

  const handleLogout = async (e) => {
    e.preventDefault();
    await logout();
  };

  useEffect(() => {
    const handleResize = () => {
      setIsMobile(window.innerWidth < 768);
      if (window.innerWidth >= 768) {
        setIsOpen(false);
      }
    };

    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  return (
    <nav className="w-full px-4 md:px-8 text-gray-600 sticky z-50 top-0 shadow-md bg-gray-50">
      <div className="container mx-auto flex justify-between items-center h-12 md:h-8">
        <div className="flex items-center">
          <a className="flex items-center gap-1">
            <img src={assets.logo} className="size-5" alt="" />
            <span className="font-medium">PeAPIniere</span>
          </a>
        </div>

        {/* Desktop Navigation */}
        <ul className="hidden md:flex text-sm items-center h-full">
          <NavLink to="/" text="Home" />
          <NavLink to="/plants" text="Plants" />
          <NavLink to="/dashboard" text="Dashboard" />
          <AccountLink user={user} />
          <button
            onClick={handleLogout}
            className="flex items-center gap-1 hover:bg-gray-200 h-full px-2 cursor-pointer"
          >
            <img src={assets.logout} className="size-5" alt="" />
          </button>
          <CartLink />
        </ul>

        {/* Mobile hamburger button */}
        <button
          className="md:hidden text-gray-600 focus:outline-none"
          onClick={() => setIsOpen(!isOpen)}
        >
          {isOpen ? <FiX size={24} /> : <FiMenu size={24} />}
        </button>
      </div>

      {/* Mobile menu */}
      {isOpen && isMobile && (
        <div className="md:hidden bg-gray-50 pb-4 px-4 shadow-md">
          <ul className="flex flex-col space-y-2">
            <MobileNavLink to="/" text="Home" setIsOpen={setIsOpen} />
            <MobileNavLink to="/plants" text="Plants" setIsOpen={setIsOpen} />
            <MobileNavLink
              to="/dashboard"
              text="Dashboard"
              setIsOpen={setIsOpen}
            />
            <MobileAccountLink user={user} setIsOpen={setIsOpen} />
            <button
              onClick={(e) => {
                handleLogout(e);
                setIsOpen(false);
              }}
              className="flex items-center gap-2 px-4 py-2 hover:bg-gray-200 rounded"
            >
              <img src={assets.logout} className="size-5" alt="" />
              <span>Logout</span>
            </button>
            <MobileCartLink setIsOpen={setIsOpen} />
          </ul>
        </div>
      )}
    </nav>
  );
};

// Reusable components for cleaner code
const NavLink = ({ to, text }) => (
  <Link
    to={to}
    className="flex items-center gap-1 hover:bg-gray-200 h-full px-4 cursor-pointer"
  >
    <span className="font-medium">{text}</span>
  </Link>
);

const MobileNavLink = ({ to, text, setIsOpen }) => (
  <Link
    to={to}
    onClick={() => setIsOpen(false)}
    className="px-4 py-2 hover:bg-gray-200 rounded font-medium"
  >
    {text}
  </Link>
);

const AccountLink = ({ user }) => (
  <Link
    to="/account"
    className="flex items-center gap-1 hover:bg-gray-200 h-full px-2 cursor-pointer"
  >
    <PiUserCircleLight className="size-5" />
    <span className="font-medium">
      {user ? user.data.user.fullname : "account"}
    </span>
  </Link>
);

const MobileAccountLink = ({ user, setIsOpen }) => (
  <Link
    to="/account"
    onClick={() => setIsOpen(false)}
    className="flex items-center gap-2 px-4 py-2 hover:bg-gray-200 rounded"
  >
    <PiUserCircleLight className="size-5" />
    <span className="font-medium">
      {user ? user.data.user.fullname : "account"}
    </span>
  </Link>
);

const CartLink = () => (
  <Link
    to="/cart"
    className="flex items-center gap-1 hover:bg-gray-200 h-full px-2 cursor-pointer"
  >
    <IoCartOutline className="size-5" />
  </Link>
);

const MobileCartLink = ({ setIsOpen }) => (
  <Link
    to="/cart"
    onClick={() => setIsOpen(false)}
    className="flex items-center gap-2 px-4 py-2 hover:bg-gray-200 rounded"
  >
    <IoCartOutline className="size-5" />
    <span>Cart</span>
  </Link>
);

export default Navbar;
