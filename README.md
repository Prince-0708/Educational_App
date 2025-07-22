# Educational_App
Project : Java and Web Development

________________________________________
Edu Platform – Education System for Students and Teachers
Edu Platform is an educational system management application that is helpful to students and teachers web application built using HTML, CSS, JS, PHP, and MYSQL.
It allows users to register, log in, manage assignments, receive questions, and view and update academic grades.
 
 
  
________________________________________
🚀 Features
●	User Registration & Login (with password hashing)
●	Student Dashboard to track progress, assignments, and discuss questions
●	The Teacher Dashboard to track students' progress, add assignments, and formative feedback
●	Clean, intuitive dashboard UI
________________________________________
🛠️ Tech Stack
●	Backend: PHP
●	Database: MySQL
●	Frontend: HTML, CSS, JavaScript
________________________________________

🧪 Installation & Setup
# 1: Clone the Repository
Open VSCode Terminal and run:
	git clone https://github.com/your-username/your-repo-name.git

# 2: Move into the Project Directory
cd your-repo-name

# 3: Set Up a Local Server
First to create a local server environment:
●	Download XAMPP: https://www.apachefriends.org/index.html
●	Install and start Apache and MySQL from the XAMPP control panel.

 # 4: Place Project in htdocs
Move the cloned project folder into:
	C:\xampp\htdocs\
So your path becomes something like:
	C:\xampp\htdocs\your-repo-name

# 5: Import the Database
●	Open phpMyAdmin at http://localhost/phpmyadmin
●	Create a new database (e.g., my_project_db)
●	Import the .sql file (usually found in your project, like database.sql) into this database.

# 6: Update Database Connection Settings
Open the PHP file that connects to the database (usually named config.php, db.php, or inside the login/register files), and set the credentials:
$servername = "localhost";
$username = "root";
$password = "";
$database = "my_project_db"; // your created DB name

# 7: Run the Project
	Open a browser and go to: http://localhost/your-repo-name/
________________________________________
🎯 Usage
●	Register as a new student or teacher, or log in.
●	If logged in as a teacher, add assignments with titles and due times.
●	If logged in as a student, then they can view and submit assignments, and pursue their progress
________________________________________
Test Cases
1.	🔐 User Authentication 	
Test Case 1: User can sign up with valid credentials.
Navigate to registration.
Enter name, email, and password.
Click "Register".
Expected: Authenticated, redirected to login.
Test Case 2: User cannot sign up with an existing email.
Navigate to registration.
Enter registered email, password.
Click "Register".
Expected: Error — "Email already registered"
Test Case 3: User can log in with valid credentials.
Navigate to login.
Enter email and password.
Click "Login".
Expected: Authenticated, redirected to dashboard.
Test Case 4: User cannot log in with invalid credentials.
Navigate to login.
Enter wrong email/password.
Click "Login".
Expected: Error — "Invalid credentials"
Test Case 5: User cannot access dashboard without login.
Log out.
Enter /dashboard in browser.
Expected: Redirected to login.
Test Case 6: User can log out.
Log in.
Click "Logout".
Expected: Session ends, redirected to login.
________________________________________
2.	✅ Teacher Dashboard Management 
Test Case 1: A User as a teacher can add an assignment.
Click "Add Assignment".
Enter title, due date, subject name, and special notes.
Click "Save".
Expected: Task appears in the student dashboard.
Test Case 2: A User as a teacher can give marks on a complete assignment.
Click  "Progress Tracking".
Enter marks according to the student's performance.
Expected: Task marked complete and will be shown on the student's profile
Test Case 3: Formative feedback to answer student questions
Click  "Formative Feedback".
Can reply accordingly to the student's question.
Expected: The submitted answer will be shown on the student dashboard
________________________________________
3.	✅ Student Dashboard Management 
Test Case 1: A User as a student can view and submit an assignment.
Click "Online Assignment".
Enter the subject assignment PDF
Click "Save".
Expected: Task appears in the teacher dashboard.
Test Case 2: Discussion Form to ask any questions to the admin
Click  "Discussion Form".
Ask any questions that will appear on the teacher dashboard’s  "Formative Feedback".
Expected: The submitted question will be shown on the teacher dashboard
Test Case 3: Track the user’s (student’s) own progress in a graph
Click  "Progress Pursuit".
Expected: The student's academic subjects performance will be shown can be filtered by years.
________________________________________

