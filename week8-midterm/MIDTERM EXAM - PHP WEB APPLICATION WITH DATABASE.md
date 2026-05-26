
# MIDTERM EXAM - PHP WEB APPLICATION WITH DATABASE - IS SCHOOL - INS3064

**Duration: 60 minutes | Total: 100 points**

***

## **EXAM 1: UNIVERSITY COMPUTER LAB MANAGEMENT SYSTEM**

### **REAL-WORLD PROBLEM STATEMENT**

The university IT department needs a **Computer Lab Management System** to track **computer inventory** and **reported issues**. The system must demonstrate **CRUD operations** for the Dean's office demo.

**Business Requirements:**
✅ Manage **computer inventory** in lab rooms
✅ Track **issues** per computer (one-to-many relationship)
✅ Dashboard with **Add/Edit/Delete/View** functionality

***

### **PART 1: DATABASE DESIGN (20 points)**

**Create 2 tables with proper constraints:**

```
1. TABLE: computers (COMPUTER INVENTORY)
├── id (INT, PRIMARY KEY, AUTO_INCREMENT)
├── computer_name (VARCHAR(50), NOT NULL) - e.g., "Lab1-PC05"  
├── model (VARCHAR(100), NOT NULL) - e.g., "Dell Optiplex 7090"
├── operating_system (VARCHAR(50)) - e.g., "Windows 10 Pro"
├── processor (VARCHAR(50)) - e.g., "Intel Core i5-11400"
├── memory (INT) - e.g., 16 (GB)
├── available (BOOLEAN, DEFAULT 1) - 1=available, 0=under repair
└── created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)

2. TABLE: issues (ISSUE TRACKING)  
├── id (INT, PRIMARY KEY, AUTO_INCREMENT)
├── computer_id (INT, FOREIGN KEY → computers.id, NOT NULL)
├── reported_by (VARCHAR(50)) - e.g., "John Doe"
├── reported_date (DATETIME, DEFAULT CURRENT_TIMESTAMP)
├── description (TEXT, NOT NULL) - Issue description
├── urgency (ENUM('Low','Medium','High'), DEFAULT 'Medium')
├── status (ENUM('Open','In Progress','Resolved'), DEFAULT 'Open')
└── created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
```

**Insert 10 sample records into `computers` table** (5 Lab1 computers, 5 Lab2 computers).

***

### **PART 2: PHP + PDO APPLICATION (30 points)**

**Create `dashboard.php` with complete CRUD for `computers` table:**

```
1. DATABASE CONNECTION (PDO)
├── Host: localhost
├── Database: computer_management  
├── Username/Password: root/"" (XAMPP default)
└── Error handling

2. DASHBOARD INTERFACE
├── Display all computers in HTML table
├── Columns: ID | Name | Model | OS | CPU | RAM | Status | Actions
└── "Add New Computer" button + search functionality

3. CRUD OPERATIONS (computers table ONLY)
   ✅ CREATE: Add new computer form (modal/popup)
   ✅ READ: List all computers + search by computer_name
   ✅ UPDATE: Edit computer details (click "Edit")
   ✅ DELETE: Confirm delete (click "Delete")  
```

**Technical Requirements:**

```
- Use PREPARED STATEMENTS (PDO)
- Input validation (computer_name, model required)
- SQL error handling with user-friendly messages
- Responsive design (mobile-friendly)
```


***

### **PART 3: VIVA QUESTIONS (50 points)**



***

## **EXAM 2: CLASS-STUDENT MANAGEMENT SYSTEM**

### **REAL-WORLD PROBLEM STATEMENT**

Academic Affairs needs a **Student Management System** to track **classes** and **students** for semester reporting. System must demo **CRUD operations** for department heads.

***

### **PART 1: DATABASE DESIGN (20 points)**

```
1. TABLE: classes (CLASS MANAGEMENT)
├── id (INT, PRIMARY KEY, AUTO_INCREMENT)
├── class_name (VARCHAR(50), NOT NULL) - e.g., "SE0601"  
├── subject_name (VARCHAR(100), NOT NULL) - e.g., "Web Programming"
├── semester (VARCHAR(20)) - e.g., "2025-1"
├── academic_year (VARCHAR(20)) - e.g., "2024-2025"
├── room (VARCHAR(20)) - e.g., "A101"
└── created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)

2. TABLE: students (STUDENT MANAGEMENT)
├── id (INT, PRIMARY KEY, AUTO_INCREMENT)
├── class_id (INT, FOREIGN KEY → classes.id, NOT NULL)
├── student_code (VARCHAR(20), UNIQUE, NOT NULL) - e.g., "20128573"
├── full_name (VARCHAR(100), NOT NULL) - e.g., "Nguyen Van An"
├── date_of_birth (DATE) - e.g., "2003-05-15"
├── email (VARCHAR(100), UNIQUE) - e.g., "20128573@school.edu.vn"
├── gender (ENUM('Male','Female','Other'))
└── created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
```

**Insert 10 sample records into `students` table** (across 3-4 different classes).

***

### **PART 2: PHP + PDO APPLICATION (30 points)**

**Create `student_management.php` with CRUD for `students` table:**

```
1. DASHBOARD FEATURES:
├── Display student list + class name (JOIN query)
├── Search by student_code or full_name
├── Filter by class_id (dropdown)

2. CRUD OPERATIONS (students table):
   ✅ CREATE: Add student (select class from dropdown)
   ✅ READ: Student list + details  
   ✅ UPDATE: Edit student info (validate unique student_code)
   ✅ DELETE: Confirm student deletion

3. INTERFACE:
├── Responsive table with column sorting
├── Modal forms for Add/Edit
├── Success/error notifications
```


***

### **PART 3: VIVA QUESTIONS (40 points)**



***

## **SUBMISSION REQUIREMENTS**

```
✅ Single PHP file (dashboard.php OR student_management.php)
✅ SQL dump file creating tables + sample data
✅ Screenshot of working dashboard
✅ Code runs immediately in XAMPP
```


***

## **GRADING CRITERIA**

| **Criteria** | **Points** | **Evaluation Standards** |
| :-- | :-- | :-- |
| **Source Code** | 15 pts | Follows standards, clear, readable with comments |
| **Functionality** | 15 pts | Complete and accurate CRUD operations |
| **User Interface** | 15 pts | Clean, basic design, responsive/mobile-friendly |
| **Data Validation** | 5 pts | Ensures data integrity on Add/Edit operations |
| **Database Design** | 5 pts | Correct table structure + 10 sample records |
| **Viva Questions** | 40 pts | Clear technical explanations |

**Total: 100 points**

***

## **TECHNICAL CONSTRAINTS**

```
❌ NO mysqli - PDO only
❌ NO frameworks - Pure PHP
❌ NO external libraries - Vanilla CSS/JS
✅ Prepared statements required
✅ Error handling mandatory
✅ Single file deployment
```

**Database Setup:** Create `computer_management` or `student_management` database first.

***

*This exam tests practical skills for **Information Systems students** to build production-ready PHP applications with proper database design and CRUD functionality.*

