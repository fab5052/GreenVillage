import React from 'react';
import Card from './Card';
import cardImage0 from '../../image/card/card(0).png';
import cardImage1 from '../../image/card/card(1).png';
import cardImage2 from '../../image/card/card(2).png';
import cardImage3 from '../../image/card/card(3).png';
//import './card.css';

const cardImages = [cardImage0, cardImage1, cardImage2, cardImage3];


const CardGrid = ({ numberOfCards = 4 }) => {
  // const cards = [
  //   {
  //     id: 1,
  //     title: 'Card Title 1',
  //     description: 'Description pour la carte 1.',
  //     imageIndex: 0,  // index dans le tableau cardImages
  //   },
  //   {
  //     id: 2,
  //     title: 'Card Title 2',
  //     description: 'Description pour la carte 2.',
  //     imageIndex: 1,
  //   },
  //   {
  //     id: 3,
  //     title: 'Card Title 3',
  //     description: 'Description pour la carte 3.',
  //     imageIndex: 2,
  //   }

  // ];

  // const CardGrid = () => {
  //   const handleCardClick = (id) => {
  //     console.log(`Card ${id} clicked`);
  //   };
  
    return (
      <div className="card-grid">
      {[...Array(numberOfCards)].map((_, index) => (
        <Card key={index + 1} cardNumber={index + 1} />
      ))}
    </div>
    );
  };

export default CardGrid; 