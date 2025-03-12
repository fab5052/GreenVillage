import React from 'react';
import Card from './Card';

const CardGrid = () => {
  const cards = [
    {
      imageUrl: '/assets/images/card.png',
      title: 'Card Title 1',
      description: 'Description for card 1'
    },
    {
      imageUrl: '/assets/images/card.png',
      title: 'Card Title 2',
      description: 'Description for card 2'
    },
    {
      imageUrl: '/assets/images/card.png',
      title: 'Card Title 3',
      description: 'Description for card 3'
    }
  ];

  return (
    <div className="container mx-auto px-4">
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {cards.map((card, index) => (
          <Card
            key={index}
            imageUrl={card.imageUrl}
            title={card.title}
            description={card.description}
          />
        ))}
      </div>
    </div>
  );
};

export default CardGrid; 