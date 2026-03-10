<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Student") {
    header("location:../SignIn.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("location:studentBooking.php");
    exit();
}

$propertyId = $_GET['id'];
$userid=$_SESSION["id"];
$sql = "SELECT *,u.Firstname AS fname,u.Lastname AS lname,u.Email AS mail FROM properties p JOIN users u ON u.Userid = p.Owner_id WHERE Id = '$propertyId'";
$result = mysqli_query($conn, $sql);


$sql1 = "SELECT * FROM users WHERE Userid ='$userid'";
$result1 = mysqli_query($conn, $sql1);
$user=mysqli_fetch_assoc($result1);

if (mysqli_num_rows($result) !== 1) {
    die("Property not found!");
}

$property = mysqli_fetch_assoc($result);
$_SESSION["ownermail"] = $property["mail"];
$_SESSION["usermail"] = $user["Email"];
$_SESSION["property"] = $property["Names"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment - U-Rental</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background: #f0f2f5; font-family: Poppins, sans-serif; }
.payment-box {
    max-width: 600px;
    margin: 50px auto;
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.paypal-btn-container {
    margin-top: 20px;
}
.signature-pad {
    border: 2px dashed #ccc;
    border-radius: 10px;
    cursor: crosshair;
    background: #fff;
    touch-action: none; /* Prevent scrolling on touch devices */
}
.signature-container {
    text-align: center;
    margin-bottom: 20px;
}
</style>
</head>
<body>

<div class="payment-box">
    <h2 class="text-center text-primary fw-bold mb-3">Confirm Payment</h2>

    <div class="mb-3">
        <h5><?= $property['Names'] ?></h5>
        <p><strong>Location:</strong> <?= $property['Locations'] ?></p>
        <p><strong>Monthly Rent (Tsh):</strong></p>
<input
    type="number"
    id="custom-price"
    class="form-control w-50"
    value="<?= $property['Price'] ?>"
/>
<small class="text-muted">
    Default price: Tsh <?= number_format($property['Price']) ?>
</small>
    </div>
    
<div class="mb-3">
    <label for="months" class="form-label">Number of Months:</label>
    <input type="number" id="months" class="form-control w-50 text-center" min="1" max="12" value="1">
</div>
<div class="mb-3">
    <label class="form-label">Rental Period:</label>
    <div class="border rounded p-3 bg-light w-75">
        <p class="mb-1">
            <strong>Start Date:</strong> 
            <span id="start-date"></span>
        </p>
        <p class="mb-0">
            <strong>End Date:</strong> 
            <span id="end-date"></span>
        </p>
    </div>
</div>
    <hr>

    <h4 class="text-center text-success">
    Total: Tsh <span id="total-tsh"><?= number_format($property['Price']) ?></span>
</h4>

<div class="signature-container">
    <label class="form-label fw-bold">Sign Here (Required):</label>
    <br>
    <canvas id="signature-pad" class="signature-pad" width="500" height="150"></canvas>
    <br>
    <div class="d-flex justify-content-center gap-2 mt-2">
         <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearSignature()">Clear Signature</button>
         <label class="btn btn-sm btn-outline-primary m-0">
             Upload Signature <input type="file" id="upload-signature" accept="image/*" hidden>
         </label>
    </div>
</div>

    <p class="text-center text-muted">Proceed to secure payment using PayPal</p>
<div id="paypal-button-container" class="paypal-btn-container"></div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    window.jsPDF = window.jspdf.jsPDF;
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

 <!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=AcnuDoViQotdZqEVj48bzFhORWKDZYe4Bnh0jjyOkn-_nPUOVLguVagXXyA5GxK4D_M1yn8DQ1Qv2mEj&currency=USD"></script>

<!-- PayPal Button Container -->

 <div class="my-4 d-flex justify-content-center">
                    <a href="studentBooking.php" class="btn btn-lg btn-dark px-4 d-flex align-items-center">Cancel</a>
                </div>

<script>
const defaultPrice = <?= $property['Price'] ?>;
const priceInput = document.getElementById('custom-price');

const exchangeRate = 2500;
const monthsInput = document.getElementById('months');
const totalTshEl = document.getElementById('total-tsh');

const startDateEl = document.getElementById('start-date');
const endDateEl = document.getElementById('end-date');

function formatDate(date) {
    return date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

function updateTotal() {
    let months = parseInt(monthsInput.value) || 1;
    if (months < 1) months = 1;

    let price = parseInt(priceInput.value) || defaultPrice;
    if (price < 1) price = defaultPrice;


    // Total price
    let totalTsh = price * months;
    totalTshEl.textContent = totalTsh.toLocaleString();

    // USD for PayPal
    window.usdAmount = (totalTsh / exchangeRate).toFixed(2);

    // Dates
    let startDate = new Date();
    let endDate = new Date();
    endDate.setMonth(startDate.getMonth() + months);

    startDateEl.textContent = formatDate(startDate);
    endDateEl.textContent = formatDate(endDate);
}


// Initialize
updateTotal();

// Update on change
monthsInput.addEventListener('input', updateTotal);
priceInput.addEventListener('input', updateTotal);

</script>
  
<script>
// --- Signature Pad Logic ---
const canvas = document.getElementById('signature-pad');
const ctx = canvas.getContext('2d');
let isDrawing = false;
let hasSigned = false;

function getMousePos(canvas, evt) {
    const rect = canvas.getBoundingClientRect();
    return {
        x: (evt.clientX - rect.left) * (canvas.width / rect.width),
        y: (evt.clientY - rect.top) * (canvas.height / rect.height)
    };
}

// Mouse events
canvas.addEventListener('mousedown', (e) => {
    isDrawing = true;
    hasSigned = true;
    const pos = getMousePos(canvas, e);
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
});
canvas.addEventListener('mousemove', (e) => {
    if (isDrawing) {
        const pos = getMousePos(canvas, e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }
});
canvas.addEventListener('mouseup', () => isDrawing = false);
canvas.addEventListener('mouseout', () => isDrawing = false);

// Touch events for mobile
canvas.addEventListener('touchstart', (e) => {
    e.preventDefault();
    isDrawing = true;
    hasSigned = true;
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent("mousedown", {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
}, { passive: false });
canvas.addEventListener('touchmove', (e) => {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent("mousemove", {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
}, { passive: false });
canvas.addEventListener('touchend', () => {
    const mouseEvent = new MouseEvent("mouseup", {});
    canvas.dispatchEvent(mouseEvent);
});

function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    hasSigned = false;
    document.getElementById('upload-signature').value = ""; // Reset file input
}

// Handle Image Upload
document.getElementById('upload-signature').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(event) {
        const img = new Image();
        img.onload = function() {
            clearSignature();
            
            // Calculate scale
            const hRatio = canvas.width / img.width;
            const vRatio = canvas.height / img.height;
            const ratio  = Math.min(hRatio, vRatio);
            
            const centerShift_x = (canvas.width - img.width * ratio) / 2;
            const centerShift_y = (canvas.height - img.height * ratio) / 2;
            
            ctx.drawImage(img, 0, 0, img.width, img.height, 
                         centerShift_x, centerShift_y, img.width*ratio, img.height*ratio);
            
            hasSigned = true;
        }
        img.src = event.target.result;
    }
    reader.readAsDataURL(file);
});

function generateContractPDF() {
    if (!window.jspdf) {
        alert("jsPDF not loaded");
        return;
    }

    // Validate signature
    if (!hasSigned) {
        alert("Please sign or upload a signature before proceeding.");
        return false;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // --- Fetch Data ---
    const tenantName = <?= json_encode(
        ($user['Firstname'] ?? 'Student') . ' ' . ($user['Lastname'] ?? 'Tenant')
    ) ?>;

    const propertyName = <?= json_encode($property['Names'] ?? 'Unknown Property') ?>;
    const ownerName = <?= json_encode(
        ($property['fname'] ?? '') . ' ' . ($property['lname'] ?? '')
    ) ?>;
    const location = <?= json_encode($property['Locations'] ?? 'Unknown Location') ?>;
    const ownerSignature = <?= json_encode($property['Signature'] ?? '') ?>;

    const months = parseInt(document.getElementById('months')?.value) || 1;
    
    // Calculate Total Price
    const basePrice = parseInt(document.getElementById('custom-price')?.value) || <?= (int)($property['Price'] ?? 0) ?>;
    const priceTsh = basePrice * months;

    // Dates
    const startDate = new Date();
    const endDate = new Date();
    endDate.setMonth(startDate.getMonth() + months);
    
    // Format helpers
    const formatDate = d => d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    const agreementTime = new Date().toLocaleString('en-GB');

    // --- Design & Layout (Compact for Single Page) ---
    const primaryColor = [0, 51, 102];

    // 1. Header Section (Compact)
    doc.setFillColor(...primaryColor);
    doc.rect(0, 0, 210, 30, "F"); 
    
    doc.setTextColor(255, 255, 255);
    doc.setFont("helvetica", "bold");
    doc.setFontSize(18);
    doc.text("RENTAL AGREEMENT", 105, 15, null, null, "center");
    
    doc.setFontSize(9);
    doc.setFont("helvetica", "normal");
    doc.text("OFFICIAL SMART CONTRACT", 105, 22, null, null, "center");

    // Reset styles for body
    doc.setTextColor(0, 0, 0);

    let y = 45;
    const leftMargin = 15;

    // 2. Parties Section
    doc.setFontSize(11);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(...primaryColor);
    doc.text("1. PARTIES INVOLVED", leftMargin, y);
    doc.setLineWidth(0.5);
    doc.setDrawColor(...primaryColor);
    doc.line(leftMargin, y + 2, 195, y + 2);
    
    y += 10;
    doc.setFontSize(10);
    doc.setTextColor(0, 0, 0);
    
    doc.text(`Landlord: ${ownerName}`, leftMargin, y);
    doc.text(`Tenant:   ${tenantName}`, leftMargin, y + 6);
    
    y += 15;

    // 3. Property Details Section (Compact AutoTable)
    doc.setFontSize(11);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(...primaryColor);
    doc.text("2. PROPERTY DETAILS", leftMargin, y);
    doc.line(leftMargin, y + 2, 195, y + 2);
    y += 8;

    const tableData = [
        ["Property Name", propertyName],
        ["Location", location],
        ["Duration", months + " Month(s)"],
        ["Start Date", formatDate(startDate)],
        ["End Date", formatDate(endDate)],
        ["Total Amount", "Tsh " + priceTsh.toLocaleString()]
    ];

    doc.autoTable({
        startY: y,
        head: [['Description', 'Details']],
        body: tableData,
        theme: 'grid',
        headStyles: { fillColor: primaryColor, fontSize: 10, cellPadding: 2 },
        styles: { fontSize: 9, cellPadding: 2, overflow: 'ellipsize' },
        columnStyles: { 0: { fontStyle: 'bold', width: 50 } },
        margin: { left: leftMargin, right: 15 }
    });

    // Update y based on table height
    y = doc.lastAutoTable.finalY + 15;

    // 4. Terms Section
    doc.setFontSize(11);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(...primaryColor);
    doc.text("3. TERMS AND CONDITIONS", leftMargin, y);
    doc.line(leftMargin, y + 2, 195, y + 2);

    y += 8;
    doc.setFontSize(9);
    doc.setTextColor(50, 50, 50);
    doc.setFont("helvetica", "normal");

    const terms = [
        "1. PAYMENTS: The Tenant agrees to pay the Total Amount stated above in full prior to occupancy.",
        "2. USE OF PREMISES: The property shall be used for residential purposes only.",
        "3. PROHIBITIONS: No illegal activities are permitted on the premises.",
        "4. DAMAGES: The Tenant is responsible for any damage caused to the property during the stay.",
        "5. TERMINATION: This agreement is binding for the duration specified. No refunds upon early departure."
    ];

    terms.forEach(term => {
        const splitTerm = doc.splitTextToSize(term, 180);
        doc.text(splitTerm, leftMargin, y + 4);
        y += (splitTerm.length * 4) + 2;
    });

    // 5. Footer & Signatures (Ensure Single Page)
    
    y = Math.max(y + 10, 230); // Ensure minimal spacing but push down if empty
    
    doc.setDrawColor(150, 150, 150);
    
    // Owner Line
    doc.line(leftMargin, y, 80, y);
    doc.setFontSize(8);
    doc.text("Signature: Owner/Landlord", leftMargin, y + 4);
    
    // Owner Signature Image
    if (ownerSignature) {
        try {
            doc.addImage(ownerSignature, 'PNG', leftMargin, y - 15, 60, 20);
        } catch (e) {
            console.warn("Could not add owner signature", e);
        }
    }

    // Tenant Signature (Image)
    const signatureImage = canvas.toDataURL("image/png");
    // Place image slightly above the line
    doc.addImage(signatureImage, 'PNG', 120, y - 12, 50, 15); 
    doc.line(120, y, 185, y);
    doc.text("Signature: Tenant", 120, y + 4);

    // Bottom info
    doc.setFontSize(7);
    doc.setTextColor(150, 150, 150);
    doc.text("Generated by U-Rental System", leftMargin, 285);
    doc.text("Date: " + agreementTime, 195, 285, null, null, "right");

    // Save
    doc.save("Smart_Rental_Agreement.pdf");

    // Send to server
    const pdfBase64 = doc.output("datauristring").split(",")[1];
    sendPDFToServer(pdfBase64);
    
    return true; 
}
</script>

<script>
function sendPDFToServer(pdfData) {
    fetch("send_contract.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            pdf: pdfData
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("Contract sent to tenant and owner email successfully.");
        } else {
            alert("Email sending failed.");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Error sending contract.");
    });
}
</script>


<script>
paypal.Buttons({

    onClick: function (data, actions) {
        // Validate signature before starting PayPal logic
        if (!hasSigned) {
            alert("Please sign the agreement before proceeding.");
            return actions.reject();
        }
        // PDF generation happens on approval now or we can do it here if needed,
        // but typically we wait for payment to succeed. However, user wanted to sign *while* paying.
        // The original code called generateContractPDF on click which downloads it immediately.
        // We will keep that behavior but ensure it returns true.
        const success = generateContractPDF(); 
        if (!success) {
            return actions.reject();
        }
    },

    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: window.usdAmount
                }
            }]
        });
    },

    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            const months = document.getElementById('months').value || 1;
            window.location.href =
                "paymentProcess.php?pid=<?= $property['Id'] ?>&status=success&months=" + months;
        });
    }

}).render('#paypal-button-container');
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>
