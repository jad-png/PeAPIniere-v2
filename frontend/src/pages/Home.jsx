import React from "react";
import ProductCategories from "../components/ProductCategories";
import HeroSlider from "../components/HeroSlider";
import AboutSection from "../components/AboutSection";
import FeaturedPlants from "../components/FeaturedPlants";

export default function Home() {
  return (
    <div className="min-h-screen flex flex-col bg-white border-b border-gray-200">
      <main className="flex-grow">
        <HeroSlider />
        <section className="py-12">
          <div className="container mx-auto px-4">
            <h2 className="text-2xl font-bold mb-6">Nos Catégories</h2>
            <ProductCategories />
          </div>
        </section>
        <section className="py-12 bg-gray-50">
          <div className="container mx-auto px-4">
            <h2 className="text-2xl font-bold mb-6">Nos Produits Populaires</h2>
            <FeaturedPlants />
          </div>
        </section>
        <AboutSection />
      </main>
    </div>
  );
}
