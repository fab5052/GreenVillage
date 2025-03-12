import React from "react";
import { createRoot } from "react-dom/client"; 
//import { createApp } from 'vue'
//import DemoApp from './DemoApp.vue'
import './index.css'
import './card.css'
//import Calendar from "./components/calendar";
//import Card from "./components/Card";
import CardGrid from "./components/CardGrid";


//createApp(DemoApp).mount('#app')
// import Navbar from "./components/ui/navbar"; 
// import SearchBar from "./components/ui/searchbar"; 
//import FullCalendar from "../React/FullCalendar/Calendar";
// import App from "../React/SearchBar/App";
//import FullCalendar from ".components/ui/Calendar";
//import CalendarComponent from "./components/ui/fullcalendar";

const rootElement = document.getElementById("root");

if (rootElement) {
  createRoot(rootElement).render(
    <React.StrictMode>
      {/* <Navbar >*/ }
      <CardGrid />  
      {/* <Calendar />
      <FullCalendar /> */}
    </React.StrictMode>
  );
} else {
  console.error("Aucune div avec l'ID 'root' trouvée dans le DOM !");
}