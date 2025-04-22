<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map {
            height: 700px;
            width: 700px;
            margin: 0 auto;
            justify-content: center;
            border: 2px black solid;
            border-radius: 50%;
            position: relative;
            z-index: 1;
        }

        #header {
            display: flex;
            background-color: #5DA35F;
            justify-content: space-around;
            padding: 10px;
            align-items: center;
        }

        footer {
            position: relative;
            z-index: 2;
        }

        body {
            margin: 0;
            height: 200vh;
            background-image: url("../images/fond.jpg");
            background-size: cover;
            background-repeat: no-repeat;
        }

        #content {
            background-color: #76C586;
            height: 200vh;
            width: 80%;
            margin: 0 auto;
            justify-content: center;
        }

        #prod {
            text-decoration: none;
            color: #4A5F3A;
            display: flex;
            padding: 10px;
            border: 2px #4A5F3A solid;
        }

        #prod:hover {
            background-color: #4A5F3A;
            color: #76C586;
        }

        .leaflet-control-container {
            visibility: hidden;
        }

        h1 {
            margin-left: 20px;
            color: #4A5F3A;
        }

        h2 {
            margin-left: 20px;
            color: #5C733F;
            font-size: 1.8rem;
            font-weight: 600;
            border-bottom: 1px solid #C2D5B3;
            padding-bottom: 4px;
            margin-top: 1.5rem;
        }

        h3 {
            margin-left: 20px;
            color: #6B8E23;
            font-size: 1.4rem;
            font-weight: 500;
            margin-top: 1rem;
        }

        .infosCo {
            background-color: #E8F5E9;
            border-radius: 10px;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .editProfile {
            background-color: #E8F5E9;
            border-radius: 10px;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<div id="content">
    <nav id="header">
        <a href='HomeIT.php'><img src="../images/velo.png" alt="logo" style="width: 50px; height: 50px; "></a>
        <a href="ProductList.php" id="prod">Our products</a>
        <a href="ListEmployee.php" id="prod">List of employees</a>
        <a href="ModifProduct.php" id="prod">Edit product data</a>
        <a href="ProfilEmployee.php" id="prod">Profile</a>
        <a href="../index.html"><img src="../images/exit.png" alt="exitlogo" style="width: 50px; height: 50px; "></a>
    </nav>