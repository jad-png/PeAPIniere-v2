import React from "react";
import { assets } from "../assets/assets.js";

const Footer = () => {
  return (
    <footer className="bg-white text-gray-600 py-12 text-xs">
      <div className="container mx-auto px-6">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-12">
          <div>
            <div className="flex items-center space-x-2 mb-6">
              <img src={assets.logo} alt="Ramad-on logo" className="h-8 w-8" />
              <span className="text-xl font-semibold">PeAPIniere</span>
            </div>
            <p className="text-gray-400">
              PéAPInière est une API pour une pépinière permet aux clients de
              parcourir des plantes et de gérer leurs commandes, avec une
              authentification sécurisée.
            </p>
          </div>

          <div>
            <h4 className="font-semibold mb-4">Liens Rapides</h4>
            <ul className="space-y-2 text-gray-400">
              <li>
                <a href="#" className="hover:text-gray-600">
                  À Propos
                </a>
              </li>
              <li>
                <a href="#" className="hover:text-gray-600">
                  Contact
                </a>
              </li>
              <li>
                <a href="#" className="hover:text-gray-600">
                  Conditions d'Utilisation
                </a>
              </li>
              <li>
                <a href="#" className="hover:text-gray-600">
                  Politique de Confidentialité
                </a>
              </li>
            </ul>
          </div>

          <div>
            <h4 className="font-semibold mb-4">Catégories</h4>
            <ul className="space-y-2 text-gray-400">
              <li>
                <a href="#" className="hover:text-gray-600">
                  Recettes Iftar
                </a>
              </li>
              <li>
                <a href="#" className="hover:text-gray-600">
                  Recettes Suhoor
                </a>
              </li>
              <li>
                <a href="#" className="hover:text-gray-600">
                  Desserts
                </a>
              </li>
              <li>
                <a href="#" className="hover:text-gray-600">
                  Boissons
                </a>
              </li>
            </ul>
          </div>

          <div>
            <h4 className="font-semibold mb-4">Newsletter</h4>
            <p className="text-gray-400 mb-4">
              Abonnez-vous pour recevoir les dernières recettes et mises à jour.
            </p>
            <form className="flex">
              <input
                type="email"
                placeholder="Votre email"
                className="px-4 py-2 rounded-l-lg w-full text-gray-900"
              />
              <button className="bg-gray-600 px-4 py-2 rounded-r-lg hover:bg-gray-700 text-white">
                S'abonner
              </button>
            </form>
          </div>
        </div>

        <div className="border-t border-gray-200 mt-12 pt-8 text-center text-gray-400">
          <p>&copy; 2025 PeAPIniere. Tous droits réservés.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
