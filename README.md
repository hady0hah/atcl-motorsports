# Motorsports Website

## Overview

This project is a **Motorsports Website** built using **Symfony**, **Sonata Admin**, **Vue.js**, and **Axios**. The frontend communicates with the backend via APIs for seamless interaction. The system features a **CMS** for managing motorsports data, along with automated sports calculations and management of drivers, events, results, and more.

## Features

- **Drivers**: Manage driver information, registration, and profiles.
- **Licensing**: Handle driver licensing and validation.
- **Results**: Record and display race results.
- **Sections**: Manage different sections of the motorsports events (e.g., qualifying, heats).
- **Championships**: Track and manage ongoing championships.
- **Events**: Create, update, and view motorsports events.
- **Documents**: Manage important documents related to events, licenses, and more.
- **Grading System**: Track and calculate driver performance and rankings.
- **Participation**: Manage driver participation in events and championships.
- **Results View and Search**: Search and view race results with filtering options.

## Technologies

- **Frontend**:
  - Vue.js: JavaScript framework for dynamic user interfaces.
  - Axios: HTTP client for communication with the backend APIs.
  
- **Backend**:
  - Symfony: PHP framework for backend logic and API management.
  - Sonata Admin: Provides a powerful backend admin panel for managing data.
  
## Features in Detail

- **CMS**: 
  - Admin panel for managing drivers, events, results, and other motorsports data.
  - Use Sonata Admin to streamline backend operations.

- **Automated Sports Calculation**: 
  - The system calculates race results, driver rankings, and championship standings based on the performance in each event.

- **API Communication**: 
  - The frontend (Vue.js) communicates with the backend (Symfony) via APIs using Axios to fetch data and send requests, ensuring a smooth user experience.

- **Grading and Results**: 
  - The grading system tracks driver performance, while results from different events are automatically calculated and displayed.

- **Search and View Results**: 
  - View and search results using various filters to find specific events or driver performances.

## Prerequisites

To run this project, you need:

- Node.js and npm (for frontend)
- Composer (for Symfony)
- PHP 8.0 or later
- MySQL or any supported database

## Usage

### Frontend

1. Install dependencies:
   ```bash
   npm install
