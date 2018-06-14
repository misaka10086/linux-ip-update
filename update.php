<?php
// Connect database using your information
$servername = "127.0.0.1";
$username = "ip_report";
$password = "ip_report";
try {
    $conn = new PDO("mysql:host=$servername;dbname=ip_report", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}

// Collect informations
$public_ip= exec("curl ipinfo.io/ip");
$private_ip = shell_exec("ifconfig | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1'");
// Report time in UTC
$time = time();

// Update to database
$sql = <<<SQL
INSERT INTO ip_update(client_name,public_ip,private_ip,time)
VALUE (?,?,?,?)
SQL;
$statement = $conn->prepare($sql);
$statement->execute(array("Raspberry 1",$public_ip,$private_ip,$time));
