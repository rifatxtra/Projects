

# Rental Management System (University Project)

## Professional Rental Management System Using React (Frontend) and PHP (Backend)

Welcome to the **Rental Management System University Project**! This comprehensive project features a robust rental management system built with **React** for the frontend and **raw PHP** for the backend. It utilizes `.env`, Composer, and Context API for seamless integration and performance.

---

## Features

- **User-Friendly Interface**: Intuitive UI built with React.
- **Secure Backend**: Raw PHP backend utilizing `.env` for environment variables and Composer for dependency management.
- **Context API**: Efficient state management.
- **Responsive Design**: Mobile-friendly and accessible across devices.
- **Real-Time Updates**: Notifications and updates for rental transactions.

---

## Project Structure

```
Rental-Management-System/
├── public/    # Frontend code (React)
└── api/       # Backend code (PHP)
```

---

## Getting Started

### Prerequisites

Ensure you have the following installed:

- **Node.js**
- **npm** (Node Package Manager)
- **Composer**
- **PHP**
- **MySQL**

---

### Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/rifatxtra/Rental-Management-System-University-Project-.git
   cd Rental-Management-System-University-Project-
   ```

2. **Install Frontend Dependencies**:
   ```bash
   cd public
   npm install
   ```

3. **Install Backend Dependencies**:
   ```bash
   cd ../api
   composer install
   ```

4. **Setup Environment Variables**:
   - Rename `.env.example` to `.env` in the `api` folder.
   - Configure the database settings in the `.env` file.

5. **Configure `.htaccess` File**:
   Create an `.htaccess` file in the `api` directory with the following content:
   ```plaintext
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)$ index.php [QSA,L]
   ```

6. **Start the Development Servers**:
   - **Frontend**:
     ```bash
     cd ../public
     npm start
     ```
   - **Backend**:
     ```bash
     cd ../api
     php -S localhost:8000
     ```

---

## Frontend Details

### Technologies Used

- **React**: JavaScript library for building user interfaces.
- **Context API**: For state management.
- **Tailwind CSS**: Utility-first CSS framework for styling.

### Commands

- **Navigate to the public directory**:
  ```bash
  cd public
  ```
- **Install dependencies**:
  ```bash
  npm install
  ```
- **Start the development server**:
  ```bash
  npm start
  ```
- **Build for production**:
  ```bash
  npm run build
  ```

---

## Backend Details

### Technologies Used

- **PHP**: Server-side scripting language.
- **Composer**: Dependency management tool.
- **.env**: For managing environment variables.
- **MySQL**: Database for storing rental information.

### Commands

- **Navigate to the api directory**:
  ```bash
  cd api
  ```
- **Install dependencies**:
  ```bash
  composer install
  ```
- **Setup environment variables**:
  Rename `.env.example` to `.env` and configure the database settings.

- **Start the development server**:
  ```bash
  php -S localhost:8000
  ```

---

## Key Features

- Secure API endpoints.
- User authentication and authorization.
- Rental management and notifications.

---

## Contributing

Contributions are welcome!  
Please fork the repository and submit a pull request for improvements or bug fixes.

---

## License

This project is licensed under the **MIT License**.

---
