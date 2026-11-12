# Software Requirements Specification – Learning Management System (LMS)
**Student:** Kirupairednam Mathusha (BAT/IT/2324/P/039) · **Institute:** SLIATE ATI Batticaloa · **Subject:** HNDIT 4052 · **Version:** 1.0

## 1. Introduction
**1.1 Purpose** – Defines requirements of a web-based LMS that centralises course delivery, assessment, communication and progress tracking.
**1.2 Scope** – Admins, teachers and students use one platform to manage courses, materials, assignments, grades and announcements, replacing manual, disconnected processes.
**1.3 Definitions** – LMS: Learning Management System; UAT: User Acceptance Testing; RBAC: Role-Based Access Control.
**1.4 References** – Project proposal (LMS_Final.pdf); Moodle, PHP and MySQL documentation.

## 2. Overall Description
**2.1 Perspective** – Standalone web app: browser → Apache/PHP → MySQL.
**2.2 User classes** – *Administrator* (manages users), *Teacher* (manages own courses and grading), *Student* (enrolls, submits, views results).
**2.3 Environment** – Windows 10/11, XAMPP (Apache, PHP 8.x, MySQL 8.x), Chrome/Firefox.
**2.4 Constraints** – Developed individually, Mar–Jul 2026; open-source tools only.
**2.5 Assumptions** – Users have internet access and a modern browser.

## 3. Functional Requirements
| ID | Requirement | Role |
|----|-------------|------|
| FR1 | Users register with name, email, password and role (student/teacher) | All |
| FR2 | Users log in/out securely | All |
| FR3 | Role-based dashboard | All |
| FR4 | Admin views and deletes users | Admin |
| FR5 | Teacher creates courses | Teacher |
| FR6 | Student browses and enrolls in courses | Student |
| FR7 | Teacher uploads learning materials (links) | Teacher |
| FR8 | Teacher creates assignments with due dates | Teacher |
| FR9 | Student submits/updates assignment answers | Student |
| FR10 | Teacher grades submissions with feedback | Teacher |
| FR11 | Student views grades and feedback | Student |
| FR12 | Teacher posts course announcements | Teacher |
| FR13 | Access to a course restricted to its teacher, enrolled students and admin | All |

## 4. Non-Functional Requirements
- **Security:** hashed passwords (bcrypt), prepared statements (SQL injection), output escaping (XSS), CSRF tokens, RBAC checks on every page.
- **Usability:** responsive UI (desktop to mobile), clear labels, visible keyboard focus.
- **Performance:** pages load in under 3 s on a 10 Mbps connection for ~100 concurrent users.
- **Reliability:** relational integrity with foreign keys; regular database backups.
- **Maintainability:** modular PHP files, Git version control.
- **Portability:** runs on any Apache + PHP 8 + MySQL 8 host.

## 5. Database Design
users(id, name, email, password, role) · courses(id, title, description, teacher_id) · enrollments(course_id, student_id) · materials(id, course_id, title, url) · assignments(id, course_id, title, details, due_date) · submissions(id, assignment_id, student_id, answer, grade, feedback) · announcements(id, course_id, title, body, created_at).
Relationships: teacher 1–N courses; students N–M courses (enrollments); course 1–N materials/assignments/announcements; assignment 1–N submissions.

## 6. External Interfaces
**UI:** web pages – Home, Register, Login, Dashboard, Courses, Course detail. **Hardware:** i3, 4 GB RAM, 128 GB SSD minimum. **Software:** MySQL via PDO.

## 7. Use Cases (summary)
UC1 Register/Login · UC2 Manage users (Admin) · UC3 Create course (Teacher) · UC4 Enroll (Student) · UC5 Publish material/assignment/announcement (Teacher) · UC6 Submit work (Student) · UC7 Grade work (Teacher) · UC8 View results (Student).

## 8. Testing & Acceptance
Developer testing (26 May–14 Jun 2026) then UAT with students, lecturers and admin (17 Jun–7 Jul 2026). Each FR above maps to at least one test case.

## 9. Future Enhancements
Online tests/exams, seminar scheduling, downloadable certificates, file upload, mobile app, AI recommendations, video-conferencing, cloud deployment.
