import { useState, useEffect } from "react";

const slides = [
  {
    id: 1,
    title: "Plus de 30 modèles de pots et jardinières",
    subtitle: "LE MEILLEUR RAPPORT QUALITÉ PRIX AU MAROC",
    description:
      "Ses derniers comprennent des modèles en terre cuite, le plastique, le métal, la fibre de verre, le bois, la céramique et la pierre.",
    buttonText: "Nos pots et jardinières",
    buttonLink: "/produits/pots",
    image:
      "https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-UIPRyKHShaqVMKfXGsCeIm3GdGoITA.png",
  },
  {
    id: 2,
    title: "Large sélection de plantes d'intérieur",
    subtitle: "POUR EMBELLIR VOTRE ESPACE",
    description:
      "Découvrez notre collection de plantes d'intérieur adaptées à tous les environnements.",
    buttonText: "Voir les plantes",
    buttonLink: "/produits/plantes-interieurs",
    image:
      "https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-6FJRwpi4yApVeNEgyQj32EKalI7eSK.png",
  },
];

export default function HeroSlider() {
  const [currentSlide, setCurrentSlide] = useState(0);

  const nextSlide = () => {
    setCurrentSlide((prev) => (prev + 1) % slides.length);
  };

  const prevSlide = () => {
    setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);
  };

  useEffect(() => {
    const interval = setInterval(() => {
      nextSlide();
    }, 5000);

    return () => clearInterval(interval);
  }, []);

  return (
    <section className="relative h-[500px] md:h-[600px] overflow-hidden">
      <div
        className="flex h-full transition-transform duration-500 ease-in-out"
        style={{ transform: `translateX(-${currentSlide * 100}%)` }}
      >
        {slides.map((slide) => (
          <article
            key={slide.id}
            className="flex-shrink-0 w-full h-full relative"
          >
            <img
              src={slide.image}
              alt={slide.title}
              className="absolute inset-0 w-full h-full object-cover"
            />
            <div className="absolute inset-0 bg-black bg-opacity-20 flex items-center">
              <div className="container mx-auto px-4">
                <div className="max-w-lg bg-white bg-opacity-90 p-8 rounded-lg">
                  <p className="text-sm font-medium text-green-700 mb-2">
                    {slide.subtitle}
                  </p>
                  <h2 className="text-3xl md:text-4xl font-bold mb-4">
                    {slide.title}
                  </h2>
                  <p className="text-gray-700 mb-6">{slide.description}</p>
                  <a
                    href={slide.buttonLink}
                    className="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md transition-colors"
                  >
                    {slide.buttonText}
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      className="h-5 w-5 ml-2"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                        clipRule="evenodd"
                      />
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </article>
        ))}
      </div>

      <button
        className="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-md z-10"
        onClick={prevSlide}
        aria-label="Slide précédent"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          className="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={2}
            d="M15 19l-7-7 7-7"
          />
        </svg>
      </button>

      <button
        className="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-md z-10"
        onClick={nextSlide}
        aria-label="Slide suivant"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          className="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={2}
            d="M9 5l7 7-7 7"
          />
        </svg>
      </button>
    </section>
  );
}
