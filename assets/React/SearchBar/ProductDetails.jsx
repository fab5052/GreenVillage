import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";

const ProductDetails = () => {
  const { slug } = useParams();
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    const fetchProduct = async () => {
      try {
        const response = await fetch(`/api/search/${slug}`);
        if (!response.ok) {
          throw new Error("Produit introuvable.");
        }
        const data = await response.json();
        setProduct(data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchProduct();
  }, [slug]);

  if (loading) return <p>Chargement...</p>;
  if (error) return <p>Erreur : {error}</p>;
  if (!product) return <p>Produit introuvable.</p>;

  return (
    <div className="p-6">
      <h2 className="text-xl font-bold">{product.label}</h2>
      <p className="text-gray-500">{product.description}</p>
      <p className="text-lg text-indigo-600">{product.price} €</p>
    </div>
  );
};

export default ProductDetails;
