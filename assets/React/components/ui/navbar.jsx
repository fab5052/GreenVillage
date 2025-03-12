
import React from "react";
import { useState } from "react";
import { Menu, X } from "lucide-react";
//import { Button } from "./button"; // Vérifie si ce chemin est correct
import Button from "../ui/button";


export default function Navbar() {
  const [isOpen, setIsOpen] = useState(false);

  return (
    <nav className="bg-blue-600 p-4 shadow-md">
      <div className="container mx-auto flex justify-between items-center">
        {/* Logo */}
        <a href="#" className="text-blue text-xl font-bold"></a>
        
        {/* Menu pour écrans larges */}
        <div className=" md:flex space-x-6">
          <a href="#" className="text-grey text-xl font-bold border-white-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 rounded-md p-2 w-full max-w-md transition ease-in-out duration-150">Accueil</a>
          <a href="#" className="text-grey text-xl font-bold border-white-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 rounded-md p-2 w-full max-w-md transition ease-in-out duration-150">Services</a>
          <a href="#" className="text-grey text-xl font-bold border-white-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 rounded-md p-2 w-full max-w-md transition ease-in-out duration-150">Contact</a>
        </div>
        
        {/* Bouton de connexion */}
        <div className="hidden md:block">
          <Button variant="outline" className="text-white border-white">Connexion</Button>
        </div>
        
        {/* Icône du menu mobile */}
        <button
          className="md:hidden text-white"
          onClick={() => setIsOpen(!isOpen)}
        >
          {isOpen ? <X size={24} /> : <Menu size={24} />}
        </button>
      </div>
      
      {/* Menu mobile */}
      {isOpen && (
        <div className="md:hidden bg-blue-700 p-4 space-y-3">
          <a href="#" className="block text-white">Accueil</a>
          <a href="#" className="block text-white">Services</a>
          <a href="#" className="block text-white">Contact</a>
          <Button variant="outline" className="w-full text-white border-white">Connexion</Button>
        </div>
      )}
    </nav>
  );
}

