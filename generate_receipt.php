<?php
// Include the TCPDF library
require_once './TCPDF-main/tcpdf.php';
include "./config/config.php";

// Fetch payment details (replace this with your actual database query)
$property_id = $_POST['property_id'] ?? '';
$tenant_name = $_POST['tenant_name'] ?? '';
$owners_name = $_POST['owners_name'] ?? '';
$receipt_no = $_POST['receipt_no'] ?? '';

// $conn = new mysqli('localhost','root','','renthouse', 8080, null);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }


// Fetch payment details from the database
$sql = "SELECT * FROM booking WHERE property_id = $property_id";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $payment_id);
// $stmt->execute();
// $result = $stmt->get_result();
// $payment = $result->fetch_assoc();

// if (!$payment) {
//     die("Payment not found.");
// }

$res = mysqli_query($db, $sql);
$payment = mysqli_fetch_assoc($res);

$cur_datetime = $payment['payment_timestamp'];
$datetime_object = new DateTime($cur_datetime); // Convert string to DateTime object
$cur_date = $datetime_object->format('d-m-Y'); 

$next_datetime = $payment['next_payment_date'];
$datetime_object = new DateTime($next_datetime); 
$next_date = $datetime_object->format('d-m-Y');

// Fetch additional details (tenant, owner, property) if needed
// Example: $tenant_id = $payment['tenant_id']; (use JOINs for advanced queries)

// Initialize TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('House Rental System');
$pdf->SetTitle('Payment Receipt');
$pdf->SetSubject('Payment Receipt');
$pdf->SetKeywords('TCPDF, PDF, receipt, payment');

// Set margins
$pdf->SetMargins(15, 20, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add content to the PDF
$html = <<<EOD
<h1 style="text-align: center;">Payment Receipt</h1>
<hr>
<p><strong>Receipt Number:</strong> {$receipt_no}</p>
<p><strong>Booking ID:</strong> {$payment['booking_id']}</p>
<p><strong>Date:</strong> {$cur_date}</p>
<p><strong>Tenant Name:</strong> {$tenant_name}</p>
<p><strong>Owner Name:</strong> {$owners_name}</p>
<p><strong>Property ID:</strong> {$property_id}</p>
<p><strong>Payment Amount:</strong> {$payment['amount_paid']} Rs.</p>
<p><strong>Payment Mode:</strong> Online</p>
<p><strong>Payment valid till:</strong> {$next_date}</p>
<hr>
<p style="text-align: center;">Thank you for your payment!</p>
EOD;

$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF (inline or download)
$pdf->Output('Payment_Receipt_' . $payment['booking_id'] . '.pdf', 'I');
?>
