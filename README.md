# 🚀 BlogAli - Hacker Themed Content Manager

**BlogAli** is a sleek, PHP-powered web application featuring a Matrix-inspired "Hacker" UI. It allows users to authenticate through a stylized terminal interface and securely store stories or text files in a persistent database.

## ✨ Key Features
*   **Persistent Data Storage:** Uses MySQL to ensure your stories and files remain saved even after you log out and return later.
*   **Secure Authentication:** Full registration and login system. Data is linked to specific user accounts (1:N Relationship).
*   **Secure Logout:** A dedicated session-destruction handler to safely terminate your current terminal session.
*   **OOP Architecture:** Built using Object-Oriented Programming principles in PHP for clean, modular logic.
*   **Immersive UI:**
    *   Matrix-style rain background (Canvas API).
    *   Glitch text effects and scanline overlays.
    *   Animated terminal-style content display.

## 🗄️ Database & Persistence
The application uses a relational database to manage users and their content:
1.  **Registration:** New users are stored securely.
2.  **Persistence:** When you upload text or files, they are mapped to your `user_id`. When you log out and log back in, the system retrieves your specific data from the database, ensuring nothing is lost.

## 📂 Project Structure
*   `Authorization.php` – The entry point (Login/Register).
*   `main.php` – The secure dashboard where content is uploaded and viewed.
*   `Classes.php` – Core logic: User, Auth, and Database handlers.
*   `DB.php` – Database connection and configuration.
*   `DataBase.sql` – The SQL file to set up your tables.
*   `style.css` & `script.js` – Frontend animations and aesthetics.

## 🛠 Prerequisites
*   PHP 8.0 or higher.
*   MySQL/MariaDB (XAMPP/WAMP/Laragon recommended).
*   A browser that supports modern CSS/JS.

## 🚀 How to Run
1.  **Database Setup:** Import the `DataBase.sql` file into your MySQL database (e.g., via phpMyAdmin).
2.  **Configuration:** Update `DB.php` with your database credentials (username, password, db_name).
3.  **Deployment:** Move the project files into your server's root folder (`htdocs`).
4.  **Launch:** Navigate to `http://localhost/your-project-folder/Authorization.php`.
5.  **Experience:**
    *   **Register:** Create an account.
    *   **Write:** Add your stories or upload files.
    *   **Logout:** Use the logout button to clear your session safely.
    *   **Return:** Log back in to see all your previous data still waiting for you.

## 💡 Technical Notes
*   **Security:** If you attempt to access `main.php` directly without logging in, the system will automatically redirect you to the authorization page.
*   **Developed for educational purposes.** Happy Coding! 💻
