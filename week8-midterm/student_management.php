<?php
// Start a new session or resume the existing one.
// Used to store temporary "flash" messages (like success/error alerts) across page reloads.
session_start();

// ==========================================
// 1. DATABASE CONNECTION CONFIGURATION
// ==========================================
$host = 'localhost';
$dbname = 'student_management'; // Ensure this matches your MySQL database name
$user = 'root'; // Default XAMPP MySQL user
$pass = ''; // Default XAMPP MySQL password is empty

try {
    // Attempt to connect to MySQL using PDO (PHP Data Objects) for security and flexibility.
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    // Set PDO to throw exceptions when SQL errors occur, making debugging easier.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Stop execution and display an error message if the connection fails.
    die("Database connection failed: " . $e->getMessage() . "<br>Please ensure you have created the database and configured the correct credentials in student_management.php.");
}

// ==========================================
// 2. HELPER FUNCTIONS & VARIABLES
// ==========================================
$msg = '';
$msgType = '';

/**
 * Helper function to store a temporary session message (Flash Message).
 * These messages are displayed once and then deleted automatically.
 * 
 * @param string $message The text to display to the user.
 * @param string $type The alert type (e.g., 'dark', 'secondary') mapped to UI classes.
 */
function setFlash($message, $type = 'dark')
{
    $_SESSION['flash_msg'] = $message;
    $_SESSION['flash_type'] = $type;
}

// ==========================================
// 3. FORM SUBMISSION (CRUD OPERATIONS)
// ==========================================

// Check if the page is being accessed via a POST request (user submitted a form).
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Determine which action the user is trying to perform (create, update, or delete).
    $action = $_POST['action'] ?? '';

    // --- CREATE A NEW STUDENT ---
    if ($action == 'create') {
        // Collect input values from the submitted form.
        $class_id = $_POST['class_id'];
        $student_code = $_POST['student_code'];
        $full_name = $_POST['full_name'];
        $date_of_birth = !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null;
        $email = !empty($_POST['email']) ? $_POST['email'] : null;
        $gender = $_POST['gender'];

        // Prevent duplicate students by checking if the student code or email is already registered.
        $checkQuery = "SELECT COUNT(*) FROM students WHERE student_code = ?";
        $params = [$student_code];
        if ($email) {
            $checkQuery .= " OR email = ?";
            $params[] = $email;
        }
        $stmt = $pdo->prepare($checkQuery);
        $stmt->execute($params);

        // If fetchColumn() returns > 0, a duplicate exists.
        if ($stmt->fetchColumn() > 0) {
            setFlash("Error: Student Code or Email already exists!", "secondary");
        } else {
            // No duplicates found, safe to insert the new record into the database.
            $stmt = $pdo->prepare("INSERT INTO students (class_id, student_code, full_name, date_of_birth, email, gender) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$class_id, $student_code, $full_name, $date_of_birth, $email, $gender]);
            setFlash("Student added successfully!");
        }

        // Redirect back to the main page to prevent form resubmission on page refresh (PRG pattern).
        header("Location: student_management.php");
        exit;
    }

    // --- UPDATE AN EXISTING STUDENT ---
    if ($action == 'update') {
        // Collect edited input values, including the hidden primary key ID.
        $id = $_POST['id'];
        $class_id = $_POST['class_id'];
        $student_code = $_POST['student_code'];
        $full_name = $_POST['full_name'];
        $date_of_birth = !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null;
        $email = !empty($_POST['email']) ? $_POST['email'] : null;
        $gender = $_POST['gender'];

        // Prevent updating to a student code/email that belongs to a DIFFERENT student.
        $checkQuery = "SELECT COUNT(*) FROM students WHERE (student_code = ?";
        $params = [$student_code];
        if ($email) {
            $checkQuery .= " OR email = ?";
            $params[] = $email;
        } else {
            // Safety fallback condition if no email is provided to keep SQL syntax structurally valid.
            $checkQuery .= " OR 1=2";
        }

        // Ensure we exclude the CURRENT student's ID from the duplicate check.
        $checkQuery .= ") AND id != ?";
        $params[] = $id;

        $stmt = $pdo->prepare($checkQuery);
        $stmt->execute($params);

        if ($stmt->fetchColumn() > 0) {
            setFlash("Error: Student Code or Email already exists for another student!", "secondary");
        } else {
            // Safe to update the record in the database.
            $stmt = $pdo->prepare("UPDATE students SET class_id=?, student_code=?, full_name=?, date_of_birth=?, email=?, gender=? WHERE id=?");
            $stmt->execute([$class_id, $student_code, $full_name, $date_of_birth, $email, $gender, $id]);
            setFlash("Student updated successfully!");
        }

        header("Location: student_management.php");
        exit;
    }

    // --- DELETE A STUDENT ---
    if ($action == 'delete') {
        $id = $_POST['id'];

        // Delete the matching student record using their ID.
        $stmt = $pdo->prepare("DELETE FROM students WHERE id=?");
        $stmt->execute([$id]);

        setFlash("Student deleted successfully!");
        header("Location: student_management.php");
        exit;
    }
}

// ==========================================
// 4. PREPARE VIEW DATA (DASHBOARD LOAD)
// ==========================================

// Retrieve any flash messages from the session to display in HTML alerts.
if (isset($_SESSION['flash_msg'])) {
    $msg = $_SESSION['flash_msg'];
    $msgType = $_SESSION['flash_type'];
    // Clear them immediately so they don't persist on page reload.
    unset($_SESSION['flash_msg'], $_SESSION['flash_type']);
}

// Check GET parameters for search filters and sorting options applied by the user.
$search = $_GET['search'] ?? '';
$filter_class = $_GET['class_id'] ?? '';
$sort_by = $_GET['sort'] ?? 's.created_at';
$order = $_GET['order'] ?? 'DESC';

// Toggle sorting order for when users click the table headers.
$next_order = ($order === 'ASC') ? 'DESC' : 'ASC';

// Allowed sorts array maps literal database columns to avoid SQL Injection via URL parameters.
$allowed_sorts = ['s.student_code', 's.full_name', 's.email', 'c.class_name', 's.created_at'];
if (!in_array($sort_by, $allowed_sorts)) {
    $sort_by = 's.created_at'; // Default fallback sort
}

// Base SQL query fetching student details and joining with classes table to get accurate readable class names.
$query = "SELECT s.*, c.class_name FROM students s 
          JOIN classes c ON s.class_id = c.id 
          WHERE 1=1";
$params = [];

// Apply search filter (checks if text somewhat matches student_code OR full_name).
if ($search) {
    $query .= " AND (s.student_code LIKE ? OR s.full_name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Apply class dropdown filter.
if ($filter_class) {
    $query .= " AND s.class_id = ?";
    $params[] = $filter_class;
}

// Append the safe sorting clause to the final query.
$query .= " ORDER BY $sort_by $order";

// Execute the final built query to get the dashboard presentation data.
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all classes for the Add/Edit form dropdown menus and the dashboard filter dropdown.
$classes = $pdo->query("SELECT id, class_name FROM classes ORDER BY class_name ASC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management Dashboard</title>
    <!-- Bootstrap 5 CSS Framework for layout and styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for UI Icons (trash cans, edit pens, plus signs) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS Overrides -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #212529;
        }

        .card {
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
            border-radius: 4px;
            border: 1px solid #e9ecef;
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* Styling for sortable table headers */
        th a {
            color: inherit;
            text-decoration: none;
        }

        th a:hover {
            color: #6c757d;
        }

        /* Sharpen up borders for the sleek monochrome look */
        .btn,
        .form-control,
        .form-select,
        .modal-content,
        .alert {
            border-radius: 4px;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f3f5;
        }

        /* Modify default blue focus outlines to monochrome gray for inputs */
        .form-control:focus,
        .form-select:focus {
            border-color: #212529;
            box-shadow: 0 0 0 .25rem rgba(33, 37, 41, .15);
        }
    </style>
</head>

<body>

    <div class="container-fluid py-4 px-md-5">

        <!-- Header Section -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="mb-0 text-dark fw-bold"><i class="fas fa-user-graduate me-2"></i>Student Management</h2>
            </div>
            <div class="col-md-6 text-md-end text-start mt-3 mt-md-0">
                <button class="btn btn-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#studentModal"
                    onclick="openAddModal()">
                    <i class="fas fa-plus me-1"></i> Add New Student
                </button>
            </div>
        </div>

        <!-- Render Flash Success/Error Message Box -->
        <?php if ($msg): ?>
            <div class="alert alert-<?= $msgType ?> alert-dismissible fade show shadow-sm text-bg-<?= $msgType ?>"
                role="alert">
                <?= htmlspecialchars($msg) ?>
                <button type="button" class="btn-close <?= $msgType == 'dark' ? 'btn-close-white' : '' ?>"
                    data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Search and Filter Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="" class="row g-3">
                    <!-- Text Search Input -->
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 border-dark-subtle"><i
                                    class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 border-dark-subtle" name="search"
                                placeholder="Search by Student Code or Name..."
                                value="<?= htmlspecialchars($search) ?>">
                        </div>
                    </div>
                    <!-- Class Filter Dropdown -->
                    <div class="col-md-4">
                        <select name="class_id" class="form-select border-dark-subtle">
                            <option value="">-- View All Classes --</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>" <?= $filter_class == $class['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($class['class_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Submit Search Button -->
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-dark w-100"><i class="fas fa-filter me-1"></i>
                            Filter / Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <!-- Sortable Headers which pass search/filter params via URL to persist state -->
                                <th><a
                                        href="?sort=s.student_code&order=<?= $next_order ?>&search=<?= urlencode($search) ?>&class_id=<?= urlencode($filter_class) ?>">Code
                                        <i class="fas fa-sort"></i></a></th>
                                <th><a
                                        href="?sort=s.full_name&order=<?= $next_order ?>&search=<?= urlencode($search) ?>&class_id=<?= urlencode($filter_class) ?>">Full
                                        Name <i class="fas fa-sort"></i></a></th>
                                <th><a
                                        href="?sort=c.class_name&order=<?= $next_order ?>&search=<?= urlencode($search) ?>&class_id=<?= urlencode($filter_class) ?>">Class
                                        <i class="fas fa-sort"></i></a></th>
                                <th>DOB</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Iterate over fetched $students array -->
                            <?php if (count($students) > 0): ?>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><span
                                                class="fw-bold text-dark"><?= htmlspecialchars($student['student_code']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($student['full_name']) ?></td>
                                        <td><span
                                                class="badge bg-secondary text-white border shadow-sm"><?= htmlspecialchars($student['class_name']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($student['date_of_birth'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($student['gender'] ?? 'N/A') ?></td>
                                        <td><a href="mailto:<?= htmlspecialchars($student['email']) ?>"
                                                class="text-decoration-none text-dark fw-medium"><?= htmlspecialchars($student['email'] ?? 'N/A') ?></a>
                                        </td>

                                        <!-- Edit and Delete Action Buttons -->
                                        <td class="text-center">

                                            <!-- Edit triggers Bootstrap Modal and populates data via JS -->
                                            <button class="btn btn-sm btn-outline-secondary me-1"
                                                onclick="openEditModal(<?= htmlspecialchars(json_encode($student)) ?>)"
                                                data-bs-toggle="modal" data-bs-target="#studentModal" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Delete uses a tiny direct POST form to avoid unsafe GET deletions -->
                                            <form method="POST" action="" class="d-inline"
                                                onsubmit="return confirm('WARNING: Are you sure you want to delete \n<?= htmlspecialchars(addslashes($student['full_name'])) ?> (<?= htmlspecialchars(addslashes($student['student_code'])) ?>)?\nThis action cannot be undone.');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $student['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-dark" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Display empty state message if query returns 0 students -->
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i><br>
                                        No students found matching the current criteria.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 
      Add/Edit Student Modal Component
      This single modal is reused for both creating new students and updating existing ones.
    -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="studentModalLabel"><i class="fas fa-user-plus me-2"></i>Add New
                        Student</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <!-- Data maps here to interact with the $_POST logic above -->
                <form method="POST" id="studentForm">
                    <div class="modal-body bg-body-tertiary">

                        <!-- Hidden meta-fields used by PHP to know what DB action to run -->
                        <input type="hidden" name="action" id="formAction" value="create">
                        <input type="hidden" name="id" id="studentId" value="">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="student_code" class="form-label fw-semibold text-dark">Student Code <span
                                        class="text-body-secondary">*</span></label>
                                <input type="text" class="form-control" id="student_code" name="student_code" required
                                    maxlength="20" placeholder="e.g. 20128573">
                            </div>
                            <div class="col-md-6">
                                <label for="full_name" class="form-label fw-semibold text-dark">Full Name <span
                                        class="text-body-secondary">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required
                                    maxlength="100" placeholder="e.g. Nguyen Van An">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="class_id" class="form-label fw-semibold text-dark">Class <span
                                        class="text-body-secondary">*</span></label>
                                <select class="form-select" id="class_id" name="class_id" required>
                                    <option value="">-- Choose Class --</option>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['class_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label fw-semibold text-dark">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label fw-semibold text-dark">Date of
                                    Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold text-dark">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" maxlength="100"
                                    placeholder="student@example.com">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-top text-bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i
                                class="fas fa-times me-1"></i> Cancel</button>
                        <button type="submit" class="btn btn-dark" id="saveBtn"><i class="fas fa-save me-1"></i> Save
                            Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -> required for Modals and Dismissible Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Interactivity Scripts -->
    <script>
        /**
         * Triggered when the "Add New Student" button is clicked.
         * Resets the form to a blank state ready for new data entry.
         */
        function openAddModal() {
            document.getElementById('studentModalLabel').innerHTML = '<i class="fas fa-user-plus me-2"></i>Add New Student';
            document.getElementById('formAction').value = 'create'; // Tell backend to run INSERT logic
            document.getElementById('studentId').value = '';
            document.getElementById('studentForm').reset();
            document.getElementById('saveBtn').innerHTML = '<i class="fas fa-save me-1"></i> Save Student';
        }

        /**
         * Triggered when the yellow "Edit" pencil icon is clicked next to a student record.
         * Takes the row's JSON encoded PHP data and injects it into the HTML inputs.
         * 
         * @param {Object} student A JSON payload mapped from the backend SQL result
         */
        function openEditModal(student) {
            document.getElementById('studentModalLabel').innerHTML = '<i class="fas fa-user-edit me-2"></i>Edit Student Profile';
            document.getElementById('formAction').value = 'update'; // Tell backend to run UPDATE logic
            document.getElementById('studentId').value = student.id; // Tell backend WHICH row to update

            // Inject the data values into inputs
            document.getElementById('student_code').value = student.student_code;
            document.getElementById('full_name').value = student.full_name;
            document.getElementById('class_id').value = student.class_id;
            document.getElementById('gender').value = student.gender || 'Male'; // Fallback mapping
            document.getElementById('date_of_birth').value = student.date_of_birth || '';
            document.getElementById('email').value = student.email || '';

            document.getElementById('saveBtn').innerHTML = '<i class="fas fa-check me-1"></i> Update Student';
        }
    </script>
</body>

</html>