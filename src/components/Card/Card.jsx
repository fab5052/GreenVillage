import React from 'react';
import './Card.css';

const Card = ({ cardNumber }) => {
  // Import all card images dynamically
  const cardImage = require(`../../assets/image/card/${cardNumber}.png`);

  return (
    <div className="card">
      <img src={cardImage} alt={`Card ${cardNumber}`} className="card-image" />
    </div>
  );
};

export default Card; 