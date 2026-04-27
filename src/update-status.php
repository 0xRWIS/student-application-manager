<?php
include('./database.php');
session_start();

// التحقق من صلاحيات المسؤول
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$app_id = isset($_GET['id']) ? $_GET['id'] : null;
// تأكد من أن الروابط ترسل action=approved أو action=rejected
$action = isset($_GET['action']) ? $_GET['action'] : null;

if ($app_id && $action) {
    try {
        $dbconnect->beginTransaction();

        // 1. جلب بيانات الطلب (معرف المستخدم والقسم)
        $stmt = $dbconnect->prepare("SELECT user_id, department_id FROM applications WHERE id = ?");
        $stmt->execute([$app_id]);
        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($application) {
            $student_id = $application['user_id'];
            $selected_dept = $application['department_id'];

            if ($action === 'approved') {
                // تحديث حالة الطلب إلى مقبول
                $updateApp = $dbconnect->prepare("UPDATE applications SET status = 'approved' WHERE id = ?");
                $updateApp->execute([$app_id]);

                // ربط المستخدم بالقسم في جدول users
                $updateUser = $dbconnect->prepare("UPDATE users SET department_id = ? WHERE id = ?");
                $updateUser->execute([$selected_dept, $student_id]);
            } 
            elseif ($action === 'rejected') {
                // تحديث حالة الطلب إلى مرفوض في جدول applications
                $updateApp = $dbconnect->prepare("UPDATE applications SET status = 'rejected' WHERE id = ?");
                $updateApp->execute([$app_id]);

                // التأكد من أن المستخدم ليس له قسم في جدول users (يبقى NULL)
                $updateUser = $dbconnect->prepare("UPDATE users SET department_id = NULL WHERE id = ?");
                $updateUser->execute([$student_id]);
            }
        }

        $dbconnect->commit();
        header("Location: dashboard.php?status=success");
        exit();

    } catch (PDOException $e) {
        $dbconnect->rollBack();
        die("خطأ في تحديث البيانات: " . $e->getMessage());
    }
} else {
    header("Location: dashboard.php");
    exit();
}