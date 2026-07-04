<?php
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

$db = new Database();

$name = 'Administrative Officer';
$username = 'ao';
$password = password_hash('password123', PASSWORD_DEFAULT);
$role_id = 4; // AO Role
$department_id = 1;

$db->query('INSERT INTO users (name, username, password, role_id, department_id) VALUES (:name, :username, :password, :role_id, :department_id)');
$db->bind(':name', $name);
$db->bind(':username', $username);
$db->bind(':password', $password);
$db->bind(':role_id', $role_id);
$db->bind(':department_id', $department_id);

if($db->execute()){
    echo "Administrative Officer created successfully! Username: ao, Password: password123";
} else {
    echo "Failed to create Administrative Officer.";
}
