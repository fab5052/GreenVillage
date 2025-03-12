import React from 'react';
import Card from './Card';
import './CardGrid.css';

const CardGrid = ({ numberOfCards = 4 }) => {
  return (
    <div className="card-grid">
      {[...Array(numberOfCards)].map((_, index) => (
        <Card key={index + 1} cardNumber={index + 1} />
      ))}
    </div>
  );
};

export default CardGrid; 