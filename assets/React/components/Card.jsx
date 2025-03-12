import React from 'react';
import './card.css';

import card0 from '../../assets/image/card/(0).png';
import card1 from '../../assets/image/card/(1).png';
import card2 from '../../assets/image/card/(2).png';
// ... ajoute d'autres images selon tes besoins

// Mapping des images par numéro
const cardImages = {
  0: card0,
  1: card1,
  2: card2
  // ...
};

const Card = ({ cardNumber }) => {
  const cardImage = cardImages[cardNumber];

  return (
    <div className="card">
      <img src={cardImage} alt={`Card ${cardNumber}`} className="card-image" />
    </div>
  );
};

export default Card;