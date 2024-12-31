# Mini Blog Project

This project is a mini blog application built using PHP, MySQL, and Docker. The application supports CRUD operations for blog posts and is designed with security, modularity, and scalability in mind. Below is a detailed explanation of how the project was developed.

---

## **Step-by-Step Development Process**

### **1. Backend Development**
1. **Set Up PHP**:
   - Chose PHP as the backend language due to its simplicity and compatibility with MySQL.
   - Created an `api.php` file to handle REST API requests.
   
2. **Database Integration**:
   - Used MySQL as the database and set up a `posts` table to store blog posts:
     ```sql
     CREATE TABLE posts (
         id INT AUTO_INCREMENT PRIMARY KEY,
         title VARCHAR(50) NOT NULL,
         content TEXT NOT NULL,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );
     ```
   - Connected to the database using PHP's PDO for secure and efficient interaction.

3. **API Endpoints**:
   - Implemented the following endpoints in `api.php`:
     - **GET**: Fetch all blog posts.
     - **POST**: Create a new blog post with validation (title limited to 50 characters).
     - **PUT**: Update an existing blog post.
     - **DELETE**: Delete a blog post.

4. **Error Handling**:
   - Added error handling for database connections and invalid requests.
   - Ensured proper HTTP status codes (e.g., `400` for bad requests, `200` for success).

---

### **2. Docker Setup**
1. **Dockerfile**:
   - Created a `Dockerfile` to containerize the PHP application:
     ```dockerfile
     FROM php:8.1-apache
     COPY ./backend /var/www/html
     RUN docker-php-ext-install pdo pdo_mysql
     ```

2. **docker-compose.yml**:
   - Configured Docker Compose to run the application with a MySQL database and PHP:
     ```yaml
     version: '3.8'
     services:
       app:
         build:
           context: .
         ports:
           - "8080:80"
         environment:
           DB_HOST: db
           DB_USER: root
           DB_PASSWORD: password
         depends_on:
           - db
       db:
         image: mysql:8.0
         environment:
           MYSQL_ROOT_PASSWORD: password
           MYSQL_DATABASE: blog
         ports:
           - "3306:3306"
     ```

3. **Environment Variables**:
   - Used a `.env` file to store sensitive data like database credentials:
     ```
     DB_HOST=db
     DB_USER=root
     DB_PASSWORD=password
     ```

---

### **3. CI/CD Pipeline**
1. **Pipeline Design**:
   - Used GitHub Actions to automate the build, test, and deployment processes.
   - Created a workflow file at `.github/workflows/deploy.yml`:
     ```yaml
     name: CI/CD Pipeline

     on:
       push:
         branches:
           - main

     jobs:
       build-and-test:
         runs-on: ubuntu-latest
         steps:
           - name: Checkout code
             uses: actions/checkout@v3

           - name: Set up Docker
             uses: docker/setup-buildx-action@v2

           - name: Build and Test
             run: |
               docker-compose build
               docker-compose up -d

       deploy:
         runs-on: ubuntu-latest
         needs: build-and-test
         steps:
           - name: Deploy to Server
             run: ssh user@server 'cd /path/to/project && git pull && docker-compose up -d'
             env:
               SSH_PRIVATE_KEY: ${{ secrets.SSH_PRIVATE_KEY }}
     ```

2. **Testing**:
   - Verified that the pipeline successfully builds the application on every push and deploys it to the server.

---

### **4. Security Implementation**
1. **Environment Variables**:
   - Used environment variables to store sensitive data like database credentials.
   - Excluded `.env` from version control using `.gitignore`.

2. **File Permissions**:
   - Ensured proper permissions for sensitive files (e.g., `chmod 600 .env`).

3. **Access Restrictions**:
   - Restricted access to non-essential files using Apache `.htaccess`.

---

### **5. Deployment**
1. **Local Deployment**:
   - Used Docker Compose to run the application locally:
     ```bash
     docker-compose up -d
     ```

2. **Server Deployment**:
   - Deployed the application to a cloud server using Docker Compose.
   - Automated deployment using GitHub Actions.

---

## **How to Run the Application Locally**
1. Clone the repository:
   ```bash
   git clonehttps://github.com/pronaycse/Test.git
   cd mini-blog
