<?php require_once '../www/headerChief.inc.php'; ?>
<title>List of employees</title>
<style>
    body {
        font-family: Arial, sans-serif;
    }

    header {
        padding: 1rem;
        margin-bottom: 2rem;
    }

    table {
        width: 80%;
        margin: auto;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 0.75rem;
        border: 1px solid #ddd;
        text-align: left;
    }

    th {
        background-color: lightgreen;
    }
</style>
<?php
// Vérifie que les cookies existent
if (!isset($_COOKIE['nom']) || !isset($_COOKIE['store'])) {
    echo "Erreur : informations manquantes.";
    exit;
}
$employeeName = $_COOKIE['nom'];
$store_id = $_COOKIE['store'];
$apiUrl = 'https://dev-buton231.users.info.unicaen.fr/MMI2/SAE_401/api.php';

// Récupère les infos du magasin
$storeData = file_get_contents($apiUrl . '?action=getStoreName&store_id=' . urlencode($store_id));
$storeJson = json_decode($storeData, true);

$storeName = isset($storeJson['store_name']) ? $storeJson['store_name'] : "Magasin inconnu";

// Récupère les employés du magasin
$employeesData = file_get_contents($apiUrl . '?action=getEmployeesByStore&store_id=' . urlencode($store_id));
$employees = json_decode($employeesData, true);

// ajouter un employé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addEmployee'])) {
    $postData = [
        'action' => 'addEmployee',
        'employee_name' => $_POST['employee_name'],
        'employee_email' => $_POST['employee_email'],
        'employee_password' => $_POST['employee_password'],
        'employee_role' => 'employee',
        'store_id' => $store_id
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($postData),
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($apiUrl, false, $context);
    if ($result === FALSE) {
        echo "<p style='color: red; text-align: center;'>Erreur lors de l'ajout de l'employé.</p>";
    } else {
        $response = json_decode($result, true);
        if ($response['status'] === 'success') {
            echo "<p style='color: green; text-align: center;'>Employé ajouté avec succès.</p>";
            // Recharge la liste
            $employeesData = file_get_contents($apiUrl . '?action=getEmployeesByStore&store_id=' . urlencode($store_id));
            $employees = json_decode($employeesData, true);
        } else {
            echo "<p style='color: red; text-align: center;'>Erreur : " . htmlspecialchars($response['message']) . "</p>";
        }
    }
}
?>
<body>

    <header>
        <h1>Welcome <?= htmlspecialchars($employeeName) ?> !</h1>
        <h2>You work in the store: <?= htmlspecialchars($storeName) ?></h2>
    </header>

    <main>
        <section>
            <h3 style="text-align: center;">List of employees of this store</h3>
            <table>
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($employees) && is_array($employees)): ?>
                        <?php foreach ($employees as $emp): ?>
                            <tr>
                                <td><?= htmlspecialchars($emp['name']) ?></td>
                                <td><?= htmlspecialchars($emp['role']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">No employees found for this store.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <section>
            <h3 style="text-align: center;">Add an employee to your store</h3>
            <form method="POST" action="">
                <div style="width: 80%; margin: 1rem auto;">
                    <label for="employee_name">Employee Name:</label>
                    <input type="text" name="employee_name" id="employee_name" required><br><br>
                    <label for="employee_email">Email:</label>
                    <input type="email" name="employee_email" id="employee_email" required><br><br>
                    <label for="employee_password">Password:</label>
                    <input type="password" name="employee_password" id="employee_password" required><br><br>
                    <input type="hidden" name="employee_role" value="employee">
                    <input type="hidden" name="store_id" value="<?= htmlspecialchars($store_id) ?>">
                    <button type="submit" name="addEmployee">Add employee</button>
                </div>
            </form>
        </section>
    </main>
    <?php require_once '../www/footerChief.inc.php'; ?>