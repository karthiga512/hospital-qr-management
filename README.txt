================================================================================
    SMART QR BASED OP & PRESCRIPTION MANAGEMENT SYSTEM
    Installation & User Guide
================================================================================

PROJECT OVERVIEW
----------------
This is a complete web-based Hospital OP (Outpatient) Management System that 
uses QR codes for patient identification. The system manages patient 
registration, doctor consultations, prescriptions, and pharmacy operations.

TECHNOLOGY STACK
----------------
- Frontend: HTML5, CSS3, JavaScript, Bootstrap 5.3
- Backend: PHP (Core PHP)
- Database: MySQL
- Server: XAMPP/WAMP/LAMP
- QR Library: QRServer API (Online) + html5-qrcode (Scanner)

================================================================================
                        INSTALLATION INSTRUCTIONS
================================================================================

STEP 1: Prerequisites
---------------------
1. Download and install XAMPP from: https://www.apachefriends.org/
2. Make sure Apache and MySQL services are running

STEP 2: Setup Project Files
----------------------------
1. Copy the entire "QR" folder to: C:\xampp\htdocs\
   (Your project should be at: C:\xampp\htdocs\QR\)

2. The folder structure should look like:
   QR/
   ├── index.php
   ├── config.php
   ├── register.php
   ├── generate_qr.php
   ├── database.sql
   ├── assets/
   │   └── style.css
   ├── doctor/
   │   ├── index.php
   │   ├── dashboard.php
   │   ├── save_visit.php
   │   └── logout.php
   ├── pharmacy/
   │   ├── index.php
   │   ├── dashboard.php
   │   ├── update_status.php
   │   └── logout.php
   ├── admin/
   │   ├── index.php
   │   ├── dashboard.php
   │   ├── add_doctor.php
   │   └── logout.php
   └── qrcodes/ (will be auto-created)

STEP 3: Create Database
------------------------
1. Open your browser and go to: http://localhost/phpmyadmin
2. Click on "New" to create a new database
3. Name it: smart_op
4. Click "Import" tab
5. Choose file: database.sql (from the QR folder)
6. Click "Go" to import

   OR manually run the SQL queries from database.sql

STEP 4: Configure Database Connection
--------------------------------------
1. Open config.php in a text editor
2. Update the following if needed (default XAMPP settings):
   - DB_HOST: 'localhost' (usually no change needed)
   - DB_USER: 'root' (default)
   - DB_PASS: '' (empty for XAMPP, change if you have a password)
   - DB_NAME: 'smart_op'

3. Update BASE_URL if needed:
   - Default: http://localhost/QR/
   - Change if your folder name is different

STEP 5: Access the Application
-------------------------------
Open your browser and navigate to: http://localhost/QR/

You should see the main landing page with 4 portals:
- Patient Registration
- Doctor Portal
- Pharmacy Portal
- Admin Portal

================================================================================
                        DEFAULT LOGIN CREDENTIALS
================================================================================

DOCTOR LOGIN
------------
Username: doctor1
Password: doctor123

PHARMACY LOGIN
--------------
Username: pharmacy
Password: pharmacy123

ADMIN LOGIN
-----------
Username: admin
Password: admin123

NOTE: You can add more doctors through the Admin Dashboard!

================================================================================
                            USAGE WORKFLOW
================================================================================

STEP 1: PATIENT REGISTRATION
-----------------------------
1. Go to "Patient Registration" from home page
2. Fill in patient details:
   - Full Name
   - Date of Birth
   - Phone Number (10 digits)
3. Click "Register & Generate QR Code"
4. System will:
   - Create patient record
   - Generate unique QR code
   - Display patient ID and QR code
5. Print or save the QR code for future visits

STEP 2: DOCTOR CONSULTATION
----------------------------
1. Doctor logs in using credentials
2. Two options to fetch patient:
   a) Scan QR code using webcam
   b) Manually enter Patient ID
3. System displays:
   - Patient details (Name, Age, Phone)
   - Complete visit history
   - Total visit count
4. Doctor fills the form:
   - Problem/Complaint
   - Diagnosis
   - Prescription (medicines)
   - Treatment notes (optional)
5. Click "Save Visit"
6. Visit is recorded with timestamp

STEP 3: PHARMACY DISPENSING
----------------------------
1. Pharmacy staff logs in
2. Scan patient's QR code OR enter Patient ID
3. System shows:
   - Patient details
   - Latest prescription
   - Medicine list
   - Current status
4. Pharmacy staff reviews prescription
5. Click "Mark Medicines as Given"
6. Status updates to "Given"

STEP 4: ADMIN MANAGEMENT
------------------------
1. Admin logs in
2. Dashboard shows statistics:
   - Total Patients
   - Total Visits
   - Total Doctors
   - Pending Prescriptions
3. Admin can:
   - Add new doctors
   - Delete doctors
   - Delete patients
   - View all records

================================================================================
                            FEATURES INCLUDED
================================================================================

✅ Patient Registration with Auto-ID Generation
✅ QR Code Generation (Online API)
✅ QR Code Scanner (Webcam-based)
✅ Doctor Authentication System
✅ Patient Visit History
✅ Prescription Management
✅ Pharmacy Medicine Dispensing
✅ Admin Dashboard with Statistics
✅ Doctor Management (Add/Remove)
✅ Responsive Bootstrap UI
✅ SQL Injection Prevention (Sanitization)
✅ Password Hashing (Bcrypt)
✅ Session Management
✅ Clean & Modern UI Design
✅ Print-friendly QR codes

================================================================================
                        SECURITY MEASURES IMPLEMENTED
================================================================================

1. SQL INJECTION PREVENTION
   - All user inputs are sanitized using mysqli_real_escape_string()
   - Prepared statements approach with sanitize() function
   - HTML special characters encoding

2. PASSWORD SECURITY
   - Passwords hashed using password_hash() (Bcrypt)
   - No plain text password storage
   - Secure password verification

3. SESSION MANAGEMENT
   - Secure session handling
   - Role-based access control (Doctor/Admin/Pharmacy)
   - Auto-redirect if not logged in
   - Proper logout functionality

4. INPUT VALIDATION
   - Frontend validation (HTML5 patterns)
   - Backend validation (PHP)
   - Phone number format checking
   - Date validation

5. XSS PREVENTION
   - htmlspecialchars() used on outputs
   - User inputs properly escaped

================================================================================
                            TROUBLESHOOTING
================================================================================

ISSUE: "Connection failed" error
SOLUTION: 
- Check if MySQL is running in XAMPP
- Verify database credentials in config.php
- Make sure database "smart_op" exists

ISSUE: QR codes not showing
SOLUTION:
- Check internet connection (using online API)
- Verify "qrcodes" folder has write permissions
- Check if qrcodes/ folder exists (create manually if needed)

ISSUE: Camera not working for scanner
SOLUTION:
- Grant browser permission to access camera
- Use HTTPS or localhost (camera requires secure context)
- Try different browser (Chrome recommended)

ISSUE: Login not working
SOLUTION:
- Verify database import was successful
- Check if doctors/admin tables have data
- Use exact credentials (case-sensitive for password)

ISSUE: Blank pages or PHP errors
SOLUTION:
- Enable error reporting in php.ini
- Check Apache error logs
- Verify PHP version (7.0+ recommended)

================================================================================
                        ADVANTAGES OF THIS SYSTEM
================================================================================

1. PAPERLESS OPERATION
   - No physical prescription forms needed
   - Digital record keeping
   - Easy retrieval of patient history

2. FASTER PROCESSING
   - Quick patient identification using QR
   - Instant access to medical history
   - Reduced waiting time

3. ACCURACY
   - No manual data entry errors
   - Unique patient identification
   - Complete audit trail

4. COST-EFFECTIVE
   - No special hardware required (uses webcam)
   - Open-source technologies
   - Easy to maintain

5. SCALABILITY
   - Can handle unlimited patients
   - Easy to add more doctors
   - Expandable modules

6. SECURITY
   - Role-based access
   - Encrypted passwords
   - Session management

7. USER-FRIENDLY
   - Clean, modern interface
   - Responsive design (mobile-friendly)
   - Intuitive navigation

================================================================================
                        PROJECT STRUCTURE EXPLANATION
================================================================================

config.php
----------
- Database connection setup
- Helper functions (sanitize, login checks)
- Global constants

register.php
------------
- Patient registration form
- Input validation (frontend)

generate_qr.php
---------------
- Process registration data
- Generate QR code using API
- Store patient in database
- Display success with QR code

doctor/
-------
- index.php: Login page
- dashboard.php: Main doctor interface with QR scanner
- save_visit.php: Save visit records
- logout.php: Session cleanup

pharmacy/
---------
- index.php: Login page
- dashboard.php: View prescriptions
- update_status.php: Mark medicines as dispensed
- logout.php: Session cleanup

admin/
------
- index.php: Login page
- dashboard.php: Statistics and management
- add_doctor.php: Add new doctors
- logout.php: Session cleanup

assets/
-------
- style.css: Custom premium styles

qrcodes/
--------
- Auto-generated folder for QR images

================================================================================
                        ER DIAGRAM EXPLANATION
================================================================================

PATIENTS TABLE (Main entity)
----------------------------
- id (PK) → Auto-increment patient ID
- name → Patient's full name
- dob → Date of birth
- phone → Contact number (unique identifier)
- qr_code → Path to QR image
- created_at → Registration timestamp
- last_visit → Last consultation date

DOCTORS TABLE
-------------
- id (PK) → Doctor ID
- name → Doctor's name
- username → Login username (unique)
- password → Hashed password
- created_at → Account creation date

VISITS TABLE (Transaction entity)
---------------------------------
- visit_id (PK) → Unique visit identifier
- patient_id (FK) → References patients.id
- doctor_id (FK) → References doctors.id
- visit_date → Consultation timestamp
- problem → Patient complaint
- diagnosis → Doctor's diagnosis
- prescription → Medicine details
- treatment → Additional notes
- medicine_status → 'pending' or 'given'

ADMIN TABLE
-----------
- id (PK) → Admin ID
- username → Login username
- password → Hashed password
- created_at → Account creation date

RELATIONSHIPS:
- One Patient → Many Visits (1:N)
- One Doctor → Many Visits (1:N)
- Visits table acts as junction for Patient-Doctor relationship

================================================================================
                        FUTURE ENHANCEMENTS (Optional)
================================================================================

1. Add SMS notifications for appointments
2. Email prescription to patients
3. Generate PDF reports
4. Add appointment scheduling
5. Medicine inventory management
6. Payment/billing module
7. Doctor availability calendar
8. Patient self-service portal
9. Mobile app integration
10. Analytics and reports dashboard

================================================================================
                            SUPPORT & CREDITS
================================================================================

Developed for: BCA Final Year Project
Technology: Core PHP, MySQL, Bootstrap, JavaScript
QR Generation: QRServer.com API
QR Scanner: html5-qrcode library
Design: Custom CSS with modern aesthetics

For issues or queries, check the troubleshooting section above.

================================================================================
                                END OF README
================================================================================
