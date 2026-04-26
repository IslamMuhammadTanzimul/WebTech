<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Registration - PHP Validation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .form-input {
            margin: 5px 0;
            padding: 8px;
            width: 300px;
        }
        label {
            font-weight: bold;
        }
        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 10px;
        }
        .detail-row {
            margin: 5px 0;
        }
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
    </style>
</head>
<body>

    <h2>Student Registration Form</h2>

        <?php
        
        $fullName = $email = $username = $password = $confirmPassword = $age = $gender = $course = "";
        $fullNameErr = $emailErr = $usernameErr = $passwordErr = $confirmPasswordErr = $ageErr = $genderErr = $courseErr = $termsErr = "";
        $success = "";
        $formValid = false;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (empty($_POST["fullName"])) {
                $fullNameErr = "Full Name is required";
            } else {
                $fullName = test_input($_POST["fullName"]);
                if (!preg_match("/^[a-zA-Z\s'-]*$/", $fullName)) {
                    $fullNameErr = "Full Name must contain only letters and spaces";
                }
            }

            if (empty($_POST["email"])) {
                $emailErr = "Email is required";
            } else {
                $email = test_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                }
            }

            if (empty($_POST["username"])) {
                $usernameErr = "Username is required";
            } else {
                $username = test_input($_POST["username"]);
                if (strlen($username) < 5) {
                    $usernameErr = "Username must be at least 5 characters long";
                }
            }

            if (empty($_POST["password"])) {
                $passwordErr = "Password is required";
            } else {
                $password = $_POST["password"];
                if (strlen($password) < 6) {
                    $passwordErr = "Password must be at least 6 characters long";
                }
            }

            if (empty($_POST["confirmPassword"])) {
                $confirmPasswordErr = "Confirm Password is required";
            } else {
                $confirmPassword = $_POST["confirmPassword"];
                if ($password !== $confirmPassword) {
                    $confirmPasswordErr = "Passwords do not match";
                }
            }

            if (empty($_POST["age"])) {
                $ageErr = "Age is required";
            } else {
                $age = test_input($_POST["age"]);
                if (!is_numeric($age)) {
                    $ageErr = "Age must be a number";
                } elseif ($age < 18) {
                    $ageErr = "Age must be 18 or above";
                }
            }

            if (empty($_POST["gender"])) {
                $genderErr = "Gender must be selected";
            } else {
                $gender = test_input($_POST["gender"]);
            }

            if (empty($_POST["course"])) {
                $courseErr = "Course must be selected";
            } else {
                $course = test_input($_POST["course"]);
            }

            if (empty($_POST["terms"])) {
                $termsErr = "You must agree to the Terms & Conditions";
            }
         

            if (empty($fullNameErr) && empty($emailErr) && empty($usernameErr) && 
                empty($passwordErr) && empty($confirmPasswordErr) && empty($ageErr) && 
                empty($genderErr) && empty($courseErr) && empty($termsErr)) {
                $success = "Registration Successful!";
                $formValid = true;
            }
        }
    
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        ?>

        <?php if ($success && $formValid) { ?>
            <script>
                var successMessage = "Registration Done\n\n";
                successMessage += "Full Name: <?php echo $fullName; ?>\n";
                successMessage += "Email: <?php echo $email; ?>\n";
                successMessage += "Username: <?php echo $username; ?>\n";
                successMessage += "Age: <?php echo $age; ?>\n";
                successMessage += "Gender: <?php echo $gender; ?>\n";
                successMessage += "Course: <?php echo $course; ?>";
                
                alert(successMessage);
                
                document.querySelector('form').reset();
            </script>
        <?php } ?>

        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">

            <label>Full Name:</label><br>
            <input type="text" name="fullName" value="<?php echo $fullName; ?>" class="form-input">
            <span class="error">* <?php echo $fullNameErr; ?></span><br><br>

            <label>Email Address:</label><br>
            <input type="email" name="email" value="<?php echo $email; ?>" class="form-input">
            <span class="error">* <?php echo $emailErr; ?></span><br><br>

            <label>Username:</label><br>
            <input type="text" name="username" value="<?php echo $username; ?>" placeholder="At least 5 characters" class="form-input">
            <span class="error">* <?php echo $usernameErr; ?></span><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" placeholder="At least 6 characters" class="form-input">
            <span class="error">* <?php echo $passwordErr; ?></span><br><br>

            <label>Confirm Password:</label><br>
            <input type="password" name="confirmPassword" placeholder="Confirm your password" class="form-input">
            <span class="error">* <?php echo $confirmPasswordErr; ?></span><br><br>

            <label>Age:</label><br>
            <input type="number" name="age" value="<?php echo $age; ?>" min="18" class="form-input">
            <span class="error">* <?php echo $ageErr; ?></span><br><br>

            <label>Gender:</label><br>
            <input type="radio" name="gender" value="Male" <?php if($gender == "Male") echo "checked"; ?>>
            <label style="font-weight: normal; display: inline;">Male</label>
            <input type="radio" name="gender" value="Female" <?php if($gender == "Female") echo "checked"; ?>>
            <label style="font-weight: normal; display: inline;">Female</label>
            <span class="error">* <?php echo $genderErr; ?></span><br><br>

            <label>Course Selection:</label><br>
            <select name="course" class="form-input">
                <option value="">-- Select a Course --</option>
                <option value="Computer Science" <?php if($course == "CSE") echo "selected"; ?>>Computer Science</option>
                <option value="Engineering" <?php if($course == "EEE") echo "selected"; ?>>Engineering</option>
                <option value="Business" <?php if($course == "Archi") echo "selected"; ?>>Business</option>
                <option value="Medicine" <?php if($course == "Pharma") echo "selected"; ?>>Medicine</option>
                <option value="Arts" <?php if($course == "BBA") echo "selected"; ?>>Arts</option>
            </select>
            <span class="error">* <?php echo $courseErr; ?></span><br><br>

            <input type="checkbox" name="terms" value="agreed" <?php if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["terms"])) echo "checked"; ?>>
            <label style="font-weight: normal; display: inline;">I agree to the Terms & Conditions</label>
            <span class="error">* <?php echo $termsErr; ?></span><br><br>

            <input type="submit" value="Register">

        </form>

</body>
</html>