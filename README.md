# 🏛️ ACES – College Committee Management System  
### Flutter • PHP • MySQL • REST APIs • Admin Module

ACES is a scalable and secure college committee management system developed using Flutter (frontend) and PHP/MySQL (backend).  
It enables admins to manage events, handle student registrations, send notifications, and operate committee workflows in real-time.

---

## 🚀 Features

### 🔐 Admin Module
- Secure login (username/password)  
- Role-based access  
- Session-based authentication  

### 🗓️ Event Management
- Create new events  
- Update existing events  
- Delete events  
- Event poster + details section  

### 📝 Student Registration Tracking
- View registered students  
- Export registration data (CSV/Excel)  
- Attendance/confirmation tracking  

### 🔔 Announcement & Notification System
- Real-time announcements  
- Event reminders  
- Push notifications support (if used with Firebase)  

### 📊 Dashboard
- Total events  
- Active events  
- Registered student count  
- System logs (admin actions)  

---

## 🧱 Tech Stack

| Layer | Technology |
|-------|------------|
| **Frontend** | Flutter (Dart) |
| **Backend** | PHP 7+ |
| **Database** | MySQL |
| **Tools** | Postman, XAMPP, VS Code |
| **Architecture** | REST API + JSON communication |

---


---

## ▶️ How to Run

### 💻 Backend Setup (PHP + MySQL)
1. Install & open **XAMPP**
2. Move the `backend/` folder inside:

C:/xampp/htdocs/ACES/

3. Start **Apache** & **MySQL**
4. Import `database.sql` into MySQL using phpMyAdmin
5. Update `config.php` with:
6. and change the ip address to your pc's current ip address where the api hased called
7. for eg http://10.210.246.254/Aces-flutter-api/admin/events/get_events.php  change "10.210.246.254" with your pc's current ip address

host, username, password, database


---

### 📱 Flutter App Setup
Inside `flutter_app/`:

```bash
flutter pub get
flutter run
```

If backend is hosted locally, update API URLs like:

http://localhost/ACES/api/login.php

## 📸 Screenshots

<p float="left">
  <img src="https://github.com/user-attachments/assets/ba8d5821-221a-40e8-991f-c932f9c03104" width="260"/>
  <img src="https://github.com/user-attachments/assets/a30f8ea1-664d-4480-beae-b6cc0fa44367" width="260"/>
</p>

<p float="left">
  <img src="https://github.com/user-attachments/assets/dfd41c47-3e4e-49ee-87ff-bc5127b420db" width="260"/>
  <img src="https://github.com/user-attachments/assets/7998f701-1e42-4fe2-a706-985908e77191" width="260"/>
</p>

<p float="left">
  <img src="https://github.com/user-attachments/assets/87c2c219-40dd-4fe2-9912-a3307f8109b8" width="260"/>
  <img src="https://github.com/user-attachments/assets/0feebcec-f7ce-442b-ad7c-f36f682fdf7e" width="260"/>
</p>

<p float="left">
  <img src="https://github.com/user-attachments/assets/7f4ed4f3-7b40-441a-9bd8-8870ced17add" width="260"/>
  <img src="https://github.com/user-attachments/assets/4d81fde4-eaf8-4865-b36b-08446b9b0949" width="260"/>
</p>

<p float="left">
  <img src="https://github.com/user-attachments/assets/3c7e87af-4e4f-4cf5-ab92-35b009aee879" width="260"/>
  <img src="https://github.com/user-attachments/assets/231caaba-1a83-405e-955f-70d6f1e6c9dd" width="260"/>
</p>

<p float="left">
  <img src="https://github.com/user-attachments/assets/ce06e606-9018-43de-b54c-e6e3f0f21d96" width="260"/>
  <img src="https://github.com/user-attachments/assets/960f2fd7-3aba-41b5-8240-bd0f11c84b53" width="260"/>
</p>


