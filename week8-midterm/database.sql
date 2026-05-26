-- 1. TABLE: classes (CLASS MANAGEMENT)
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(50) NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    semester VARCHAR(20),
    academic_year VARCHAR(20),
    room VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. TABLE: students (STUDENT MANAGEMENT)
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    student_code VARCHAR(20) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    date_of_birth DATE,
    email VARCHAR(100) UNIQUE,
    gender ENUM('Male', 'Female', 'Other'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- SAMPLE DATA: Insert exactly 2 classes before students (to provide valid class_id references)
INSERT INTO classes (class_name, subject_name, semester, academic_year, room) VALUES
('SE0601', 'Web Programming', '2025-1', '2024-2025', 'A101'),
('SE0602', 'Database Systems', '2025-1', '2024-2025', 'A102');

-- SAMPLE DATA: Insert 10 students
INSERT INTO students (class_id, student_code, full_name, date_of_birth, email, gender) VALUES
(1, '20128573', 'Nguyen Van An', '2003-05-15', '20128573@school.edu.vn', 'Male'),
(1, '20128574', 'Tran Thi Binh', '2003-08-20', '20128574@school.edu.vn', 'Female'),
(1, '20128575', 'Le Hoang Cuong', '2002-11-10', '20128575@school.edu.vn', 'Male'),
(1, '20128576', 'Pham My Dung', '2003-01-25', '20128576@school.edu.vn', 'Female'),
(1, '20128577', 'Hoang Minh E', '2003-04-12', '20128577@school.edu.vn', 'Male'),
(2, '20128578', 'Vu Thi Phuong', '2003-07-08', '20128578@school.edu.vn', 'Female'),
(2, '20128579', 'Nguyen Trong Giang', '2003-09-30', '20128579@school.edu.vn', 'Male'),
(2, '20128580', 'Doan Khac Hieu', '2002-12-05', '20128580@school.edu.vn', 'Male'),
(2, '20128581', 'Trinh Mai Kieu', '2003-03-18', '20128581@school.edu.vn', 'Female'),
(2, '20128582', 'Ly Van Lam', '2003-06-22', '20128582@school.edu.vn', 'Male');
