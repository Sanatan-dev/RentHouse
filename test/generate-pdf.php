<?php
require_once '../TCPDF-main/tcpdf.php';
include "../config/config.php";

// Get filter parameters
$selectedMonth = $_POST['month'] ?? '';
$selectedYear = $_POST['year'] ?? '';

$conditions = [];
if ($selectedMonth) $conditions[] = "MONTH(payment_timestamp) = '$selectedMonth'";
if ($selectedYear) $conditions[] = "YEAR(payment_timestamp) = '$selectedYear'";
$query = "SELECT booking_id, property_id, tenant_id, payment_mode, payment_timestamp, next_payment_date, amount_paid FROM booking";
if (!empty($conditions)) $query .= " WHERE " . implode(" AND ", $conditions);

$result = mysqli_query($db, $query);

// Create PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Payment Report', 0, 1, 'C');
$pdf->Ln();

$html = '<table border="1" cellspacing="3" cellpadding="4">
    <thead>
        <tr>
            <th>Booking ID</th>
            <th>Property ID</th>
            <th>Tenant ID</th>
            <th>Payment Mode</th>
            <th>Payment Date</th>
            <th>Due Date</th>
            <th>Amount Paid</th>
        </tr>
    </thead>
    <tbody>';
$totalAmount = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>';
    $html .= '<td>' . $row['booking_id'] . '</td>';
    $html .= '<td>' . $row['property_id'] . '</td>';
    $html .= '<td>' . $row['tenant_id'] . '</td>';
    $html .= '<td>' . $row['payment_mode'] . '</td>';
    $html .= '<td>' . $row['payment_timestamp'] . '</td>';
    $html .= '<td>' . $row['next_payment_date'] . '</td>';
    $html .= '<td>' . $row['amount_paid'] . '</td>';
    $html .= '</tr>';
    $totalAmount += $row['amount_paid'];
}

$html .= '</tbody></table>';
$html .= '<p><strong>Total Amount:</strong> ' . $totalAmount . '</p>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Payment_Report.pdf', 'D');
