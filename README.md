
# 🏥 DengueCare – System Design Course Project (CS307)

This repository contains the complete codebase and documentation for **DengueCare**, a healthcare web application developed as part of the **System Design (CS307)** course under the supervision of **Dr. Mahady Hasan**, Department of Computer Science and Engineering.

---

## 📚 Project Overview

**DengueCare** is a responsive, feature-rich healthcare system focused on streamlining dengue-related services. It connects patients, physicians, and healthcare infrastructure to provide critical care, information, and accessibility. The platform offers personalized dashboards, secure login/registration, and 12 essential medical services for both patients and physicians.

---

## 👨‍💻 Tech Stack

| Layer        | Technology              |
|--------------|--------------------------|
| Front-End    | HTML, CSS, SCSS, Bootstrap |
| Back-End     | PHP                      |
| Database     | MySQL (via phpMyAdmin)   |
| Hosting/Server | XAMPP or compatible LAMP stack |

---

## 🔐 Authentication System

The system includes a secure **Login and Registration** module with role-based access for:
- **Patients**
- **Physicians**

Each role has access to dedicated dashboards and relevant functionalities.

---

## 📋 Core Features (12 Services)

| #  | Feature Name                 | Description                                                                 |
|----|------------------------------|-----------------------------------------------------------------------------|
| 1  | **Find Hospital**            | Locate nearby hospitals based on user location or search criteria.          |
| 2  | **Find Doctor**              | Browse or search for available physicians by specialty or proximity.        |
| 3  | **Find Ambulance**          | Request emergency ambulance services with live location tracking.           |
| 4  | **Find Heatmap**             | View dengue outbreak zones using a real-time heatmap interface.             |
| 5  | **Dengue Demographic Data**  | Access region-wise statistics and trends on dengue cases.                   |
| 6  | **Log Symptoms**             | Patients can submit symptoms to track progress and assist physician review. |
| 7  | **Book Physician Appointment** | Schedule appointments based on availability and specialty.                  |
| 8  | **Medical Records**          | View and update personal and diagnosis history securely.                    |
| 9  | **Book Hospital Seat**       | Real-time hospital bed booking with availability tracking.                  |
| 10 | **Educational Content**      | Articles, videos, and resources about dengue prevention and treatment.      |
| 11 | **Emergency Locate Service** | Pinpoint nearest help centers and notify emergency contacts.                |
| 12 | **Blood Availability**       | Check local blood bank stocks and request donors by blood group.            |
| 13 | **Physician–Patient Management** | Enables doctors to manage patient profiles, reports, and appointments.   |

---

## 🖥️ Dashboard System

- **Patient Dashboard**: View health data, appointments, educational content, emergency services, and more.
- **Physician Dashboard**: Manage appointments, patient records, provide feedback, and monitor cases.

---

## 📁 Project Structure

\`\`\`
📦 denguecare/
├── 📁 assets/
├── 📁 css/
├── 📁 scss/
├── 📁 js/
├── 📁 includes/
├── 📁 php/
├── 📁 dashboard/
│   ├── patient/
│   └── physician/
├── 📁 sql/
│   └── database_schema.sql
├── 📄 index.html
├── 📄 login.php
├── 📄 register.php
└── 📄 README.md
\`\`\`

---

## ⚙️ How to Run Locally

1. **Install XAMPP** or any compatible LAMP server.
2. Clone this repository to your `htdocs` folder:
   \`\`\`bash
   git clone https://github.com/your-username/denguecare.git
   \`\`\`
3. Import the SQL file located at `sql/database_schema.sql` into phpMyAdmin.
4. Start Apache and MySQL services.
5. Navigate to `http://localhost/denguecare/` in your browser.

---

## 🧪 Testing & Validation

- All major modules tested across latest Chrome, Firefox, and Edge.
- Basic form validations (HTML5 + PHP).
- SQL injection and session management considered in backend scripts.

---

## 📌 Future Improvements

- API integration for live hospital data.
- Role-based admin panel for system configuration.
- Real-time chat between patients and physicians.
- Mobile app integration using RESTful APIs.

---

## 👨‍🏫 Course Details

- **Course:** CS307 – System Design  
- **Instructor:** Dr. Mahady Hasan  
- **Institution:** Department of CSE, Independent University , Bangladesh  
- **Term:** Spring 2025

---

## 📜 License

This project is for academic and educational purposes only.

---



## 👨‍💻 Developer

- **Name:** Feroz Mahmud  
- **Student ID:** 2030036  
- **Email:** [2030036@iub.edu.bd](mailto:2030036@iub.edu.bd)

---
