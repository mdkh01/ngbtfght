<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notes = $_POST['note'];
    $totalValues = $_POST['totalValue'];
    $prices = $_POST['price'];
    $quantities = $_POST['quantity'];
    $itemNames = $_POST['itemName'];
    $returnValue = $_POST['returnValue'] ?? 0;  // قيمة المرتج إذا كانت موجودة

    // إنشاء ملف Excel جديد
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // إضافة اسم الفاتورة و الوصف
    $sheet->setCellValue('A1', 'محامص الابرش');
    $sheet->setCellValue('A2', 'لتحميص كافة أنواع الموالح والمكسرات');
    $sheet->setCellValue('A3', '----------------------------------');

    // إضافة رؤوس الأعمدة
    $sheet->setCellValue('A4', 'ملاحظة');
    $sheet->setCellValue('B4', 'القيمة الاجمالية');
    $sheet->setCellValue('C4', 'السعر');
    $sheet->setCellValue('D4', 'العدد');
    $sheet->setCellValue('E4', 'نوع البضاعة');

    $totalAmount = 0;  // لحساب المجموع الإجمالي

    // كتابة البيانات في الصفوف
    for ($i = 0; $i < count($notes); $i++) {
        $sheet->setCellValue('A' . ($i + 5), $notes[$i]);
        $sheet->setCellValue('B' . ($i + 5), $totalValues[$i]);
        $sheet->setCellValue('C' . ($i + 5), $prices[$i]);
        $sheet->setCellValue('D' . ($i + 5), $quantities[$i]);
        $sheet->setCellValue('E' . ($i + 5), $itemNames[$i]);

        // إضافة القيمة الإجمالية للمجموع
        $totalAmount += $totalValues[$i];
    }

    // إضافة سطر "مرتج" إذا كان موجودًا
    if ($returnValue > 0) {
        $sheet->setCellValue('A' . (count($notes) + 6), 'مرتج');
        $sheet->setCellValue('B' . (count($notes) + 6), '-' . $returnValue);
        $totalAmount -= $returnValue;  // خصم المرتج من المجموع الإجمالي
    }

    // إضافة المجموع الإجمالي في الأسفل
    $sheet->setCellValue('A' . (count($notes) + 7), 'المجموع الكلي');
    $sheet->setCellValue('B' . (count($notes) + 7), $totalAmount);

    // حفظ الملف
    $writer = new Xlsx($spreadsheet);
    $fileName = 'invoice.xlsx';
    $writer->save($fileName);

    echo "تم حفظ البيانات بنجاح في ملف Excel.";
}
?>
