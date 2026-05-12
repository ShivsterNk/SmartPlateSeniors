# 🍽️ SmartPlate

> An AI-powered nutrition and meal planning web application built with PHP, MySQL, and the Claude Sonnet API.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![Claude API](https://img.shields.io/badge/Claude-Sonnet-D4A017?style=flat-square)
![USDA FDC](https://img.shields.io/badge/USDA-FDC%20API-4CAF50?style=flat-square)
![MealDB](https://img.shields.io/badge/MealDB-API-FF6B35?style=flat-square)

---

## 📖 About

SmartPlate is a full-stack nutrition web app that helps users track meals, explore nutritional data, generate AI-powered recipes, and get personalized dietary guidance through an intelligent chatbot. Built as a senior capstone project at Farmingdale State College using an Agile/Scrum methodology across 5 sprints.

---

## Features

| Category | Features |
|---|---|
| 🤖 **AI & Smart Features** | PlateBot chat, Recipe Generator, Custom Meal Plans |
| 🔍 **Nutrition & Meals** | Nutrition Explorer, MealDB browser, Ready Meals carousel |
| 📊 **User Dashboard** | Favorites, dietary preferences, nutrition summary card |
| 🛒 **Personal Tools** | Shopping list |
| 🔐 **Auth & Security** | Login, registration, change password, session management |

---

##  Tech Stack

**Backend**
- PHP 8.x (procedural + PDO)
- MySQL (`smart_plate_db`)
- AMPPS (local development server)

**Frontend**
- HTML5, CSS3, JavaScript
- Bootstrap 5
- jQuery & AJAX

**APIs**
- [Claude Sonnet API](https://www.anthropic.com/) — AI chat and recipe generation
- [USDA FoodData Central (FDC) API](https://fdc.nal.usda.gov/) — Nutritional data
- [TheMealDB API](https://www.themealdb.com/api.php) — Meal and recipe data

**Tools**
- Git & GitHub (version control)
- IntelliJ IDEA (IDE)
- Figma (UI design)

---

## Getting Started

### Prerequisites

- [AMPPS](https://ampps.com/) or any local PHP/MySQL server (XAMPP, WAMP, etc.)
- PHP 8.x
- MySQL 5.7+
- A Claude API key from [Anthropic](https://console.anthropic.com/)
- A USDA FDC API key from [FoodData Central](https://fdc.nal.usda.gov/api-guide.html)
- A MealDB API key from [TheMealDB](https://www.themealdb.com/api.php)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/smart-plate.git
   cd smart-plate
   ```

2. **Set up the database**
   - Create a MySQL database named `smart_plate_db`
   - Import the provided SQL schema:
     ```bash
     mysql -u root -p smart_plate_db < database/smart_plate_db.sql
     ```

3. **Configure API keys**
   - Copy the example config file:
     ```bash
     cp config/api-keys.example.php config/api-keys.php
     ```
   - Open `config/api-keys.php` and fill in your credentials:
     ```php
     <?php
     define('CLAUDE_API_KEY', 'your-claude-api-key-here');
     define('USDA_API_KEY', 'your-usda-fdc-api-key-here');
     ```
   > ⚠️ `config/api-keys.php` is gitignored and will never be committed. Never share this file.

4. **Configure your local server**
   - Place the project folder inside your server's `www` or `htdocs` directory
   - Ensure `getPDO()` in your DB connection file points to your local MySQL credentials

5. **Launch the app**
   - Start AMPPS (or your local server)
   - Navigate to `http://localhost/smart-plate` in your browser

---

## 📁 Project Structure

```
smart-plate/
├── .github/                   # Issue templates
├── ImagesSmartPlate/          # Project image assets
├── Nutrition Explore Page/    # Nutrition Explorer feature files
├── PHP/                       # Core PHP scripts
├── Pages/                     # Additional page files
├── api/                       # API handler scripts
├── assets/                    # Static assets (images, icons)
├── config/
│   ├── api-keys.example.php   # Safe template (committed)
│   ├── api-keys.php           # Your actual keys (gitignored)
│   ├── config.php             # App configuration
│   └── db.php                 # PDO connection (getPDO())
├── css/                       # Stylesheets
├── database/                  # SQL schema files
├── includes/
│   ├── ai-helper.php          # Claude API helper functions
│   ├── auth.php               # Authentication logic
│   ├── footer.php             # Shared footer
│   └── header.php             # Shared header
├── js/                        # JavaScript files
├── .gitignore
└── README.md
```

---

##  Team

| Name | Role |
|---|---|
| Eunice | Full-Stack Developer (Database, UI, Security) |
| Derek | API Integration & Backend Developer |
| Marvin | Frontend Developer |
| James | Frontend Developer |
| Shiva | Backend Developer |

Built as a capstone project for **BCS Senior Project** at **Farmingdale State College**, Spring 2026.

---

## 📸 Screenshots
<img width="1361" height="640" alt="image" src="https://github.com/user-attachments/assets/12493163-b5f5-4896-8bde-90d2e162afca" />
<img width="1350" height="591" alt="image" src="https://github.com/user-attachments/assets/ad03caf9-c9c4-40a1-811c-52f87095e325" />
<img width="1310" height="630" alt="image" src="https://github.com/user-attachments/assets/3611cce7-fa89-44b6-9e0f-63160bb71e67" />
<img width="1339" height="639" alt="image" src="https://github.com/user-attachments/assets/1a75a56f-4639-4c29-88a3-665beb2e5f74" />
<img width="1342" height="636" alt="image" src="https://github.com/user-attachments/assets/28f51208-2cb7-490d-949d-04b8b6bf3010" />







---

## 📄 License

This project was developed for academic purposes. All rights reserved © 2026 SmartPlate Team.
