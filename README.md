# 🚀 BlogAli - Hacker Themed Content Manager

**BlogAli** is a sleek, PHP-powered web application featuring a Matrix-inspired "Hacker" UI. It allows users to authenticate through a stylized terminal interface and share stories or upload text files to be displayed in a glowing console environment.

## ✨ Key Features
*   **OOP Architecture:** Built using Object-Oriented Programming principles in PHP for clean and modular logic.
*   **Secure Sessions:** Implements PHP session handling for user state management.
*   **Dual-Input System:** Supports both direct text input ("Tell your story") and text file uploads.
*   **Immersive UI:** 
    *   Matrix-style rain background (Canvas API).
    *   Glitch text effects and scanline overlays.
    *   Animated terminal-style content display.
*   **Input Validation:** Checks for password length (>7 characters) and empty message handling.

## 📂 Project Structure
*   `Authorization.php` – The entry point for the application (Login/Register).
*   `Classes.php` – Contains core classes: `User`, `Auth`, `MessageHandler`, and `FileHandler`.
*   `main.php` – The user dashboard where content is uploaded and viewed.
*   `style.css` – Custom CSS for the hacker aesthetic and animations.
*   `script.js` – JavaScript logic for the Matrix background animation.

## 🛠 Prerequisites
*   **PHP 8.0 or higher** (The code uses PHP 8 constructor property promotion).
*   A local web server like **XAMPP**, **WAMP**, or **Laragon**.

## 🚀 How to Run
1.  **Clone or Copy** the project files into your web server's root directory (e.g., `htdocs` for XAMPP).
2.  Start your **Apache** server.
3.  Open your browser and navigate to: http://localhost/your-project-folder/Authorization.php
4.  **Login/Register:**
    *   Enter any **Username**.
    *   Enter a **Password** longer than 7 characters.
    *   Click "Register" to be automatically redirected to the dashboard.
5.  **Dashboard:**
    *   Use the text input to write a message.
    *   Use the file input to upload a `.txt` file.
    *   Click "Upload All" to see your content rendered in the terminal box.

## 💡 Technical Notes
*   **Entry Point:** You must start from `Authorization.php`. If you try to access `main.php` directly without logging in, the system will redirect you back to the authorization page.
*   **Credits:** 
    *   **Backend Logic:** Developed by Ali.
    *   **UI/UX Design:** Styling and Matrix animations were enhanced with the help of AI to achieve the retro-hacker vibe.

---
*Developed for educational purposes. Happy Coding!* 💻
