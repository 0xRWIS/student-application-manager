<?php
// بدء الجلسة إذا لم تكن قد بدأت بعد
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('./database.php');

// 1. حماية الصفحة: التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. التأكد من أن المستخدم الحالي هو مسؤول (Admin)
if ($_SESSION['role'] !== 'admin') {
    header("Location: home.php");
    exit();
}

// 3. التحقق من وجود معرف السجل (ID) في الرابط
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']); // تحويل المدخل إلى رقم صحيح للحماية

    try {
        // تجهيز وتنفيذ أمر الحذف للسجل المحدد
        $stmt = $dbconnect->prepare("DELETE FROM military_applications WHERE id = ?");
        $stmt->execute([$id]);

        // إعادة التوجيه إلى صفحة إدارة التربية العسكرية مع إشعار بالنجاح
        header("Location: dashboard-military.php");
        exit();
        
    } catch (PDOException $e) {
        // في حال حدوث خطأ في قاعدة البيانات، يتم طباعته
        die("Error deleting record: " . $e->getMessage());
    }
} else {
    // إذا تم الدخول للملف مباشرة بدون معرف، يتم إرجاعه للوحة التحكم
    header("Location: military-manager.php");
    exit();
}
?>