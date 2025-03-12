import React from 'react';
import React, { useState, useEffect, useSyncExternalStore } from "react";

import FullCalendar from "@fullcalendar/react";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction"; // Pour le clic et le drag
import timeGridPlugin from "@fullcalendar/timegrid";
//import 'react-big-calendar/lib/css/react-big-calendar.css'; // Import necessary styles
import { Calendar, momentLocalizer, myEventsList } from 'react-big-calendar'; 
import moment from 'moment'; 
import Calendar from "./calendar";

// Localizer for the calendar using moment.js
const localizer = momentLocalizer(moment);

// Sample events list
const myEventsList = [
  {
    title: 'Team Meeting',
    start: new Date(2025, 0, 6, 10, 0), // Year, Month (0-indexed), Day, Hours, Minutes
    end: new Date(2025, 0, 6, 11, 30),
  },
  {
    title: 'Lunch Break',
    start: new Date(2025, 0, 7, 13, 0),
    end: new Date(2025, 0, 7, 14, 0),
  },
  {
    title: 'Project Review',
    start: new Date(2025, 0, 8, 15, 0),
    end: new Date(2025, 0, 8, 16, 30),
  },
];
 
const App = () => {
  return (
    <div style={{ padding: '20px' }}>
      <h1>My Calendar</h1>
      <Calendar
        localizer={localizer}
        events={myEventsList}
        startAccessor="start"
        endAccessor="end"
        style={{ height: 500 }}
      />
    </div>
  );
};

export default App;

// const CalendarComponent = () => {
//   const [events, setEvents] = useState([]);

//   // Charger les événements depuis l'API
//   useEffect(() => {
//     fetch("/api/events")
//       .then((response) => response.json())
//       .then((data) => setEvents(data))
//       .catch((error) => console.error("Erreur lors du chargement des événements :", error));
//   }, []);

//   // Ajouter un événement
//   const addEvent = async () => {
//     const newEvent = { title: "Nouvel Événement", start: new Date().toISOString() };

//     const response = await fetch("/api/events", {
//       method: "POST",
//       headers: { "Content-Type": "application/json" },
//       body: JSON.stringify(newEvent),
//     });

//     if (response.ok) {
//       const createdEvent = await response.json();
//       setEvents([...events, createdEvent]);
//     }
//   };

//   // Supprimer un événement
//   const deleteEvent = async (id) => {
//     const response = await fetch(`/api/events/${id}`, {
//       method: "DELETE",
//     });

//     if (response.ok) {
//       setEvents(events.filter((event) => event.id !== id));
//     }
//   };

//   // Modifier un événement (exemple simple)
//   const updateEvent = async (eventUpdate) => {
//     const response = await fetch(`/api/events/${eventUpdate.id}`, {
//       method: "PUT",
//       headers: { "Content-Type": "application/json" },
//       body: JSON.stringify(eventUpdate),
//     });

//     if (response.ok) {
//       setEvents(events.map((event) => (event.id === eventUpdate.id ? eventUpdate : event)));
//     }
//   };

//   return (
//     <div>
//       <button onClick={addEvent} className="bg-blue-500 text-white p-2 rounded">
//         Ajouter un événement
//       </button>

//       <FullCalendar
//         plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin]}
//         initialView="dayGridMonth"
//         events={events}
//         editable={true}
//         eventClick={(info) => {
//           if (window.confirm("Supprimer cet événement ?")) {
//             deleteEvent(info.event.id);
//           }
//         }}
//         eventDrop={(info) => {
//           const updatedEvent = {
//             id: info.event.id,
//             title: info.event.title,
//             start: info.event.startStr,
//             end: info.event.endStr || null,
//           };
//           updateEvent(updatedEvent);
//         }}
//       />
//     </div>
//   );
// };

 
// export default CalendarComponent