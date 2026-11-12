CREATE DATABASE IF NOT EXISTS lms CHARACTER SET utf8mb4;
USE lms;
CREATE TABLE IF NOT EXISTS users(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100) NOT NULL,email VARCHAR(120) NOT NULL UNIQUE,password VARCHAR(255) NOT NULL,role ENUM('admin','teacher','student') NOT NULL DEFAULT 'student',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS courses(id INT AUTO_INCREMENT PRIMARY KEY,title VARCHAR(150) NOT NULL,description TEXT,teacher_id INT NOT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(teacher_id) REFERENCES users(id) ON DELETE CASCADE);
CREATE TABLE IF NOT EXISTS enrollments(course_id INT,student_id INT,PRIMARY KEY(course_id,student_id),FOREIGN KEY(course_id) REFERENCES courses(id) ON DELETE CASCADE,FOREIGN KEY(student_id) REFERENCES users(id) ON DELETE CASCADE);
CREATE TABLE IF NOT EXISTS materials(id INT AUTO_INCREMENT PRIMARY KEY,course_id INT NOT NULL,title VARCHAR(150) NOT NULL,url VARCHAR(500) NOT NULL,FOREIGN KEY(course_id) REFERENCES courses(id) ON DELETE CASCADE);
CREATE TABLE IF NOT EXISTS assignments(id INT AUTO_INCREMENT PRIMARY KEY,course_id INT NOT NULL,title VARCHAR(150) NOT NULL,details TEXT,due_date DATE,FOREIGN KEY(course_id) REFERENCES courses(id) ON DELETE CASCADE);
CREATE TABLE IF NOT EXISTS submissions(id INT AUTO_INCREMENT PRIMARY KEY,assignment_id INT NOT NULL,student_id INT NOT NULL,answer TEXT,grade DECIMAL(5,2) NULL,feedback VARCHAR(255) NULL,UNIQUE(assignment_id,student_id),FOREIGN KEY(assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,FOREIGN KEY(student_id) REFERENCES users(id) ON DELETE CASCADE);
CREATE TABLE IF NOT EXISTS announcements(id INT AUTO_INCREMENT PRIMARY KEY,course_id INT NOT NULL,title VARCHAR(150) NOT NULL,body TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(course_id) REFERENCES courses(id) ON DELETE CASCADE);

INSERT INTO users(name,email,password,role)
SELECT 'Administrator','admin@lms.test','$2y$10$7.B0wVFfIRR4uHlBnolVQOTTkyW.eYu9RmCFhZG9m3lQOsd4b6GWe','admin'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='admin@lms.test');

INSERT INTO users(name,email,password,role)
SELECT 'Demo Teacher','teacher@lms.test','$2y$10$7.B0wVFfIRR4uHlBnolVQOTTkyW.eYu9RmCFhZG9m3lQOsd4b6GWe','teacher'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='teacher@lms.test');

INSERT INTO users(name,email,password,role)
SELECT 'Demo Student','student@lms.test','$2y$10$7.B0wVFfIRR4uHlBnolVQOTTkyW.eYu9RmCFhZG9m3lQOsd4b6GWe','student'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='student@lms.test');

INSERT INTO users(name,email,password,role)
SELECT 'Nimalka Perera','nimalka@example.com','$2y$10$7.B0wVFfIRR4uHlBnolVQOTTkyW.eYu9RmCFhZG9m3lQOsd4b6GWe','student'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='nimalka@example.com');

INSERT INTO courses(title,description,teacher_id)
SELECT 'Web Design Basics','Learn HTML, CSS, and layout fundamentals.', u.id
FROM users u
WHERE u.email='teacher@lms.test'
AND NOT EXISTS (SELECT 1 FROM courses WHERE title='Web Design Basics');

INSERT INTO courses(title,description,teacher_id)
SELECT 'Introduction to JavaScript','Build real interactive front-end experiences.', u.id
FROM users u
WHERE u.email='teacher@lms.test'
AND NOT EXISTS (SELECT 1 FROM courses WHERE title='Introduction to JavaScript');

INSERT INTO courses(title,description,teacher_id)
SELECT 'PHP for Beginners','Understand backend logic, forms, and database integration.', u.id
FROM users u
WHERE u.email='teacher@lms.test'
AND NOT EXISTS (SELECT 1 FROM courses WHERE title='PHP for Beginners');

INSERT INTO materials(course_id,title,url)
SELECT c.id,'Course Overview','https://example.com/overview'
FROM courses c
WHERE c.title='Web Design Basics'
AND NOT EXISTS (SELECT 1 FROM materials WHERE course_id=c.id AND title='Course Overview');

INSERT INTO assignments(course_id,title,details,due_date)
SELECT c.id,'Home Page Design','Create a simple landing page using HTML and CSS.','2026-11-25'
FROM courses c
WHERE c.title='Web Design Basics'
AND NOT EXISTS (SELECT 1 FROM assignments WHERE course_id=c.id AND title='Home Page Design');

INSERT INTO assignments(course_id,title,details,due_date)
SELECT c.id,'JavaScript Exercise','Write a script that changes text on button click.','2026-11-28'
FROM courses c
WHERE c.title='Introduction to JavaScript'
AND NOT EXISTS (SELECT 1 FROM assignments WHERE course_id=c.id AND title='JavaScript Exercise');

INSERT INTO announcements(course_id,title,body)
SELECT c.id,'Welcome','Welcome to the course! Please review the first module and introduce yourself to the class.'
FROM courses c
WHERE c.title='Web Design Basics'
AND NOT EXISTS (SELECT 1 FROM announcements WHERE course_id=c.id AND title='Welcome');

INSERT INTO enrollments(course_id,student_id)
SELECT c.id, u.id
FROM courses c, users u
WHERE c.title='Web Design Basics' AND u.email='student@lms.test'
AND NOT EXISTS (SELECT 1 FROM enrollments WHERE course_id=c.id AND student_id=u.id);

INSERT INTO enrollments(course_id,student_id)
SELECT c.id, u.id
FROM courses c, users u
WHERE c.title='Introduction to JavaScript' AND u.email='student@lms.test'
AND NOT EXISTS (SELECT 1 FROM enrollments WHERE course_id=c.id AND student_id=u.id);

INSERT INTO enrollments(course_id,student_id)
SELECT c.id, u.id
FROM courses c, users u
WHERE c.title='PHP for Beginners' AND u.email='nimalka@example.com'
AND NOT EXISTS (SELECT 1 FROM enrollments WHERE course_id=c.id AND student_id=u.id);
