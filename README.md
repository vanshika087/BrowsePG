# BrowsePG
BrowsePG is a web application designed to simplify the process of finding and listing Paying Guest (PG) accommodations. It provides a seamless platform for tenants to search and book PGs, and for owners to add and manage their listings.

The project is built using the following technologies:
HTML and CSS for structuring and styling the user interface
JavaScript for adding interactivity on the client side
PHP for handling server-side logic and data processing
MySQL as the database for storing user accounts, PG listings, and appointments.

With features like filtered search, detailed listings, and appointment booking, BrowsePG offers a practical solution for PG rentals, combining both functionality and user-friendly design.

##  Features

- PG owners can register, log in, and list their properties.
- Tenants can browse PG listings with filters like:
  - Location
  - Rent
  - Gender Preference
  - Amenities (AC, WiFi, TV, Geyser, etc.)
- Appointment booking system for tenants.
- Responsive design for both desktop and mobile users.
- Admin-level access for managing records (if extended).

---

**##Tech Stack**

- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
- Tools: XAMPP / WAMP (for local development)

##  Installation & Setup

1. Clone the Repository
   git clone https://github.com/yourusername/BrowsePG.git
2. Setup Local Server
    Download & install XAMPP or WAMP
    Place the BrowsePG folder inside htdocs/ (XAMPP) or www/ (WAMP)
3. Start Apache & MySQL using the control panel.

## Database Configuration
1. Create a new database named:browse_pg
2. Import the SQL dump:
  Go to Import
  Upload file: sql/browsepg.sql
  Click Go

## Run the Application
In your browser, go to:
http://localhost/BrowsePG/index.php
You should now see the homepage of your BrowsePG application.
