<?php
include "config.php";

$success = $error = "";


$name = $email = $registration_no = $department = "";
$nameErr = $emailErr = $regErr = $deptErr = "";


function test_input($data)
{
    $data = trim($data);
    return $data;
}


$action  = isset($_GET["action"]) ? $_GET["action"] : "list";
$edit_id = isset($_GET["id"])     ? (int)$_GET["id"] : 0;




if ($action == "delete" && $edit_id > 0) {

    $sql = "DELETE FROM students WHERE id=$edit_id";

    if ($conn->query($sql) === TRUE)
        $success = "Student record deleted successfully";
    else
        $error = "Error: " . $conn->error;

    $action = "list";
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && $action == "add") {


    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z\s'.]+$/", $name))
            $nameErr = "Only letters and spaces allowed";
    }


    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $emailErr = "Invalid email format (must contain @)";
    }


    if (empty($_POST["registration_no"])) {
        $regErr = "Registration number is required";
    } else {
        $registration_no = test_input($_POST["registration_no"]);
        if (!preg_match("/^[a-zA-Z0-9\-]+$/", $registration_no))
            $regErr = "Only letters, digits and hyphens allowed";
    }

    if (empty($_POST["department"])) {
        $deptErr = "Department is required";
    } else {
        $department = test_input($_POST["department"]);
        if (!preg_match("/^[a-zA-Z\s\-&]+$/", $department))
            $deptErr = "Only letters and spaces allowed";
    }

    if (empty($nameErr) && empty($emailErr) && empty($regErr) && empty($deptErr)) {
        $sql = "INSERT INTO students (name, email, registration_no, department)
                VALUES ('$name', '$email', '$registration_no', '$department')";

        if ($conn->query($sql) === TRUE) {
            $success = "Student record added successfully";
            $name = $email = $registration_no = $department = "";
        } else {
            $error = "Error: " . $conn->error;
        }
    }

    $action = "list";
}


if ($action == "edit" && $edit_id > 0 && $_SERVER["REQUEST_METHOD"] == "GET") {

    $result = $conn->query("SELECT * FROM students WHERE id=$edit_id");

    if ($result && $result->num_rows == 1) {
        $row             = $result->fetch_assoc();
        $name            = $row["name"];
        $email           = $row["email"];
        $registration_no = $row["registration_no"];
        $department      = $row["department"];
    } else {
        $error  = "Record not found";
        $action = "list";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && $action == "update") {

    $edit_id = (int)$_POST["edit_id"];


    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z\s'.]+$/", $name))
            $nameErr = "Only letters and spaces allowed";
    }


    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $emailErr = "Invalid email format";
    }


    if (empty($_POST["department"])) {
        $deptErr = "Department is required";
    } else {
        $department = test_input($_POST["department"]);
        if (!preg_match("/^[a-zA-Z\s\-&]+$/", $department))
            $deptErr = "Only letters and spaces allowed";
    }

    if (empty($nameErr) && empty($emailErr) && empty($deptErr)) {
        $sql = "UPDATE students
                SET name='$name', email='$email', department='$department'
                WHERE id=$edit_id";

        if ($conn->query($sql) === TRUE) {
            $success = "Student record updated successfully";
            $action  = "list";
        } else {
            $error  = "Error: " . $conn->error;
            $action = "edit";
        }
    } else {
        $action = "edit";
    }
}


$students = $conn->query("SELECT * FROM students ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }

        .error {
            color: red;
            font-size: 0.9em;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .danger {
            color: red;
            font-weight: bold;
        }

        input {
            margin: 8px 0;
            padding: 8px;
            width: 320px;
        }

        label {
            font-weight: bold;
        }

        table {
            border-collapse: collapse;
            margin-top: 20px;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h2>Student Management System</h2>

    <?php if ($success) echo "<p class='success'>$success</p>"; ?>
    <?php if ($error)   echo "<p class='danger'>$error</p>"; ?>


    <?php if ($action == "edit"): ?>


        <h3>Edit Student Record</h3>

        <form method="post" action="?action=update">
            <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">

            <label>Student Name:</label><br>
            <input type="text" name="name" value="<?php echo $name; ?>">
            <span class="error">* <?php echo $nameErr; ?></span><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo $email; ?>">
            <span class="error">* <?php echo $emailErr; ?></span><br><br>

            <label>Registration Number (cannot be changed):</label><br>
            <input type="text" value="<?php echo $registration_no; ?>" disabled><br><br>

            <label>Department:</label><br>
            <input type="text" name="department" value="<?php echo $department; ?>">
            <span class="error">* <?php echo $deptErr; ?></span><br><br>

            <input type="submit" value="Update Student" style="padding: 12px 25px; font-size: 16px;">
            &nbsp;<a href="?">Cancel</a>
        </form>


    <?php else: ?>


        <h3>Add New Student</h3>

        <form method="post" action="?action=add">

            <label>Student Name:</label><br>
            <input type="text" name="name" value="<?php echo $name; ?>">
            <span class="error">* <?php echo $nameErr; ?></span><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo $email; ?>">
            <span class="error">* <?php echo $emailErr; ?></span><br><br>

            <label>Registration Number:</label><br>
            <input type="text" name="registration_no" value="<?php echo $registration_no; ?>">
            <span class="error">* <?php echo $regErr; ?></span><br><br>

            <label>Department:</label><br>
            <input type="text" name="department" value="<?php echo $department; ?>">
            <span class="error">* <?php echo $deptErr; ?></span><br><br>

            <input type="submit" value="Add Student" style="padding: 12px 25px; font-size: 16px;">

        </form>

        <h3>All Student Records</h3>

        <?php if ($students && $students->num_rows > 0): ?>
            <table>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registration No</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
                <?php $i = 1;
                while ($row = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row["name"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td><?php echo $row["registration_no"]; ?></td>
                        <td><?php echo $row["department"]; ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $row["id"]; ?>">Edit</a>
                            &nbsp;|&nbsp;
                            <a href="?action=delete&id=<?php echo $row["id"]; ?>"
                                onclick="return confirm('Delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No student records found.</p>
        <?php endif; ?>

    <?php endif; ?>

</body>

</html>