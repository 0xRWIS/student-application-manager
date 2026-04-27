<?php
session_start();
include('./database.php');

// 1. Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Ensure data is sent via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    try {
        // Prepare the SQL query based on your 'applications' table schema
        $sql = "INSERT INTO applications (
            user_id, department_id, applicant_name, nationality, religion, 
            residence_address, home_phone, mobile_phone, guardian_name, 
            guardian_occupation, guardian_address, guardian_mobile, 
            birth_date, age, national_id, passport_number, 
            id_issue_place, id_issue_date, military_service_card_number, 
            previous_certificate, graduation_year, total_score, grade, 
            seat_number, first_foreign_language, second_foreign_language, 
            high_school_name, educational_region, governorate, student_signature,
            status, created_at
        ) VALUES (
            :user_id, :department_id, :applicant_name, :nationality, :religion, 
            :residence_address, :home_phone, :mobile_phone, :guardian_name, 
            :guardian_occupation, :guardian_address, :guardian_mobile, 
            :birth_date, :age, :national_id, :passport_number, 
            :id_issue_place, :id_issue_date, :military_service_card_number, 
            :previous_certificate, :graduation_year, :total_score, :grade, 
            :seat_number, :first_foreign_language, :second_foreign_language, 
            :high_school_name, :educational_region, :governorate, :student_signature,
            'pending', NOW()
        )";

        $stmt = $dbconnect->prepare($sql);

        // 3. Bind the form data to the query parameters
        $params = [
            ':user_id'                    => $_SESSION['user_id'],
            ':department_id'              => $_POST['department_id'],
            ':applicant_name'             => $_POST['applicant_name'],
            ':nationality'                => $_POST['nationality'],
            ':religion'                   => $_POST['religion'],
            ':residence_address'          => $_POST['residence_address'],
            ':home_phone'                 => $_POST['home_phone'],
            ':mobile_phone'               => $_POST['mobile_phone'],
            ':guardian_name'              => $_POST['guardian_name'],
            ':guardian_occupation'        => $_POST['guardian_occupation'],
            ':guardian_address'           => $_POST['guardian_address'] ?? null,
            ':guardian_mobile'            => $_POST['guardian_mobile'],
            ':birth_date'                 => $_POST['birth_date'],
            ':age'                        => $_POST['age'],
            ':national_id'                => $_POST['national_id'],
            ':passport_number'            => $_POST['passport_number'],
            ':id_issue_place'             => $_POST['id_issue_place'],
            ':id_issue_date'              => !empty($_POST['id_issue_date']) ? $_POST['id_issue_date'] : null,
            ':military_service_card_number' => $_POST['military_service_card_number'],
            ':previous_certificate'       => $_POST['previous_certificate'],
            ':graduation_year'            => $_POST['graduation_year'],
            ':total_score'                => $_POST['total_score'],
            ':grade'                      => $_POST['grade'],
            ':seat_number'                => $_POST['seat_number'],
            ':first_foreign_language'     => $_POST['first_foreign_language'],
            ':second_foreign_language'    => $_POST['second_foreign_language'],
            ':high_school_name'           => $_POST['high_school_name'],
            ':educational_region'         => $_POST['educational_region'],
            ':governorate'                => $_POST['governorate'],
            ':student_signature'          => $_POST['student_signature']
        ];

        // 4. Execute the statement
        if ($stmt->execute($params)) {
            // Success: Redirect to 'My Applications' with a success message
            $_SESSION['success_msg'] = "Your application has been submitted successfully!";
            header("Location: my-applications.php");
            exit();
        }

    } catch (PDOException $e) {
        // Database Error Handling
        die("Error saving data: " . $e->getMessage());
    }

} else {
    // If the file is accessed directly without submitting the form
    header("Location: home.php");
    exit();
}