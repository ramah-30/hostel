<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "House Owner") {
    header("location:../SignIn.php");
    exit();
}

$ownerId = $_SESSION["id"];
?>
<!DOCTYPE html>
<html>
<head>
<title>Owner Messages</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.chat-list {
    height: 90vh; 
    overflow-y: auto;
    border-right: 2px solid #ddd;
}
.chat-window {
    height: 90vh;
    display: flex;
    flex-direction: column;
}
#chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    background: #f7f7f7;
}
.chat-msg {
    padding: 8px 12px;
    border-radius: 10px;
    margin-bottom: 6px;
    max-width: 20%;
}
.me-msg { background: #0d6efd; color: white; margin-left: auto; }
.them-msg { background: #e5e5e5; }
</style>
</head>

<body>

<div class="container-fluid">
<div class="row">

    <!-- Conversations -->
    <div class="col-12 col-md-4 chat-list p-3">
        <h4 class="fw-bold">Messages</h4>

        <?php
      $sql = "
SELECT DISTINCT
    p.Id AS property_id,
    p.Names AS property_name,
    s.Userid AS student_id,
    s.Firstname,
    s.Lastname,
    s.Roles
FROM messages m
JOIN properties p ON p.Id = m.property_id
JOIN users s ON s.Userid = 
    CASE
        WHEN m.sender_id != '$ownerId' THEN m.sender_id
        ELSE m.receiver_id
    END
WHERE p.Owner_id = '$ownerId'
AND s.Roles = 'Student'
ORDER BY m.sent_at DESC
";

        $chats = mysqli_query($conn, $sql);

        while($c = mysqli_fetch_assoc($chats)) {
            if($c['Roles'] === "Student"){
                echo "
            <div class='p-2 border rounded mb-2 chat-item' 
                 onclick='openChat({$c['student_id']}, {$c['property_id']})'>
                
                <strong>{$c['Firstname']} {$c['Lastname']}</strong><br>
                <small class='text-muted'>Property: {$c['property_name']}</small>
            </div>
            ";
            }
            
        }
        ?>
    </div>

    <!-- Chat Window -->
    <div class="col-12 col-md-8 chat-window">
        <div id="chat-body"><span class="fs-2">Select a conversation</span></div>

        <div class="p-2 d-flex gap-2">
            <input id="msgInput" class="form-control" placeholder="Type a message...">
            <button onclick="sendMessage()" class="btn btn-primary">Send</button>
        </div>
    </div>
    <div class="text-center mt-4">
      <a href="owner.php" class="btn btn-dark px-4">Back</a>
    </div>
</div>
</div>

<script>
let CURRENT_STUDENT = 0;
let CURRENT_PROPERTY = 0;

// Load messages
function openChat(studentId, propertyId) {
    CURRENT_STUDENT = studentId;
    CURRENT_PROPERTY = propertyId;

    document.getElementById("dbgStudent").innerText = studentId;
    document.getElementById("dbgProperty").innerText = propertyId;

    loadMessages();
}


// Send message
function sendMessage() {
    let msg = document.getElementById("msgInput").value.trim();
    if (msg === "" || CURRENT_STUDENT === 0) return;

    let form = new FormData();
    form.append("message", msg);
    form.append("student_id", CURRENT_STUDENT);
    form.append("property_id", CURRENT_PROPERTY);

    fetch("ownerSendMessage.php", { method: "POST", body: form })
        .then(r => r.text())
        .then(data => {
            document.getElementById("msgInput").value = "";
            loadMessages();
        });
}

// Auto refresh chat
function loadMessages() {
    fetch(`ownerGetMessages.php?student_id=${CURRENT_STUDENT}&property_id=${CURRENT_PROPERTY}`)
    .then(r => r.text())
    .then(html => document.getElementById("chat-body").innerHTML = html);
}

setInterval(() => {
    if (CURRENT_STUDENT !== 0) loadMessages();
}, 1000);
</script>

</body>
</html>
