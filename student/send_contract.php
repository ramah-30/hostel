<?php
session_start();
ob_start();
header('Content-Type: application/json');

include_once 'sendContractMail.php';

$owner = $_SESSION["ownermail"] ?? null;
$user  = $_SESSION["usermail"] ?? null;
$propertyName = $_SESSION["property"] ?? "Property";

if (!$owner || !$user) {
    ob_clean();
    echo json_encode(["success" => false, "error" => "Session emails missing"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['pdf'])) {
    ob_clean();
    echo json_encode(["success" => false, "error" => "PDF data missing"]);
    exit;
}

/* Ensure contracts directory exists */
if (!is_dir("contracts")) {
    mkdir("contracts", 0777, true);
}

/* Decode PDF */
$pdfBinary = base64_decode($data['pdf']);
$pdfPath = "contracts/contract_" . time() . ".pdf";
file_put_contents($pdfPath, $pdfBinary);

/* Email messages */
$tenantMessage = "
    <h3>Rental Agreement</h3>
    <p>Dear Tenant,</p>
    <p>Attached is your rental agreement for <b>$propertyName</b>.</p>
    <p>Please keep it for your records.</p>
";

$ownerMessage = "
    <h3>New Booking Alert</h3>
    <p>A Tenant has booked your property: <b>$propertyName</b>.</p>
    <p>The rental agreement is attached.</p>
";

try {
    sendBookingEmail($user, $tenantMessage, $pdfPath);
    sendBookingEmail($owner, $ownerMessage, $pdfPath);

    if (file_exists($pdfPath)) {
        unlink($pdfPath);
    }

    ob_clean();
    echo json_encode(["success" => true]);

} catch (Exception $e) {

    if (file_exists($pdfPath)) {
        unlink($pdfPath);
    }

    ob_clean();
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
