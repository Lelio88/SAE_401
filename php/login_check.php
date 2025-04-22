<?php
require_once __DIR__ . "/../bootstrap.php";
use Entity\Employees;

    try {
        if (!isset($_GET['email']) || !isset($_GET['password'])) {
            throw new Exception('Invalid parameters!');
        } else {
            $email = $_GET['email'];
            $password = $_GET['password'];
            $resE = $entityManager->getRepository('Entity\Employees')->findOneBy(["employee_email" => $email, "employee_password" => $password, "employee_role" => "employee"]);
            $resC = $entityManager->getRepository('Entity\Employees')->findOneBy(["employee_email" => $email, "employee_password" => $password, "employee_role" => "chief"]);
            $resIT = $entityManager->getRepository('Entity\Employees')->findOneBy(["employee_email" => $email, "employee_password" => $password, "employee_role" => "IT"]);
            if ($resE || $resC || $resIT) {
                session_start();
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
            }
            if ($resE) {
                $_SESSION['nom'] = $resE->getEmployeeName();
                $_SESSION['store'] = $resE->getStoreId();
                $_SESSION['id'] = $resE->getEmployeeId();
                $_SESSION['role'] = $resE->getEmployeeRole();
                setcookie("nom", $resE->getEmployeeName(), time() + (86400 * 30), "/");
                setcookie("store", $resE->getStoreId(), time() + (86400 * 30), "/");
                setcookie("id", $resE->getEmployeeId(), time() + (86400 * 30), "/");
                setcookie("role", $resE->getEmployeeRole(), time() + (86400 * 30), "/");
                setcookie("email", $email, time() + (86400 * 30), "/");
                setcookie("password", $password, time() + (86400 * 30), "/");
                header("Location: ../Employee/HomeEmployee.php");
            } else if ($resC) {
                $_SESSION['nom'] = $resC->getEmployeeName();
                $_SESSION['store'] = $resC->getStoreId();
                $_SESSION['id'] = $resC->getEmployeeId();
                $_SESSION['role'] = $resC->getEmployeeRole();
                setcookie("nom", $resC->getEmployeeName(), time() + (86400 * 30), "/");
                setcookie("store", $resC->getStoreId(), time() + (86400 * 30), "/");
                setcookie("id", $resC->getEmployeeId(), time() + (86400 * 30), "/");
                setcookie("role", $resC->getEmployeeRole(), time() + (86400 * 30), "/");
                setcookie("email", $email, time() + (86400 * 30), "/");
                setcookie("password", $password, time() + (86400 * 30), "/");
                header("Location: ../Chief/HomeChief.php");
            } else if ($resIT) {
                $_SESSION['nom'] = $resIT->getEmployeeName();
                $_SESSION['store'] = $resIT->getStoreId();
                $_SESSION['id'] = $resIT->getEmployeeId();
                $_SESSION['role'] = $resIT->getEmployeeRole();
                setcookie("nom", $resIT->getEmployeeName(), time() + (86400 * 30), "/");
                setcookie("store", $resIT->getStoreId(), time() + (86400 * 30), "/");
                setcookie("id", $resIT->getEmployeeId(), time() + (86400 * 30), "/");
                setcookie("role", $resIT->getEmployeeRole(), time() + (86400 * 30), "/");
                setcookie("email", $email, time() + (86400 * 30), "/");
                setcookie("password", $password, time() + (86400 * 30), "/");
                header("Location: ../IT/HomeIT.php");
            } else {
                $_SESSION['error'] = "Invalid email or password!";
                header("Location: ../index.html?error=1");
            }
    }
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
?>