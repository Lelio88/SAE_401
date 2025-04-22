<?php require_once '../www/headerIT.inc.php'; ?>
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
// Récupère le store_id actuel : celui dans l'URL s'il existe, sinon celui du cookie
$activeStoreId = isset($_GET['store_id']) ? $_GET['store_id'] : $store_id;
// Récupère les infos du magasin sélectionné
$storeData = file_get_contents($apiUrl . '?action=getStoreName&store_id=' . urlencode($activeStoreId));
$storeJson = json_decode($storeData, true);
$storeName = isset($storeJson['store_name']) ? $storeJson['store_name'] : "Magasin inconnu";
// Récupère les employés du magasin sélectionné
$employeesData = file_get_contents($apiUrl . '?action=getEmployeesByStore&store_id=' . urlencode($activeStoreId));
$employees = json_decode($employeesData, true);
// Appelle l'API pour récupérer la liste des magasins
$storesData = file_get_contents($apiUrl . '?action=stores');
$stores = json_decode($storesData, true);
// ajouter un employé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addEmployee'])) {
    $postData = [
        'action' => 'addEmployee',
        'employee_name' => $_POST['employee_name'],
        'employee_email' => $_POST['employee_email'],
        'employee_password' => $_POST['employee_password'],
        'employee_role' => $_POST['employee_role'],
        'store_id' => $_POST['store_id']
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
    <form method="GET" style="text-align: center; margin-bottom: 2rem;">
        <label for="store_id">See the store employees:</label>
        <select name="store_id" id="store_id" onchange="this.form.submit()">
            <?php foreach ($stores as $store): ?>
                <option value="<?= htmlspecialchars($store['store_id']) ?>"
                    <?= ($store['store_id'] == $activeStoreId) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($store['store_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
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
                    <label for="employee_email">E-mail:</label>
                    <input type="email" name="employee_email" id="employee_email" required><br><br>
                    <label for="employee_password">Password:</label>
                    <input type="password" name="employee_password" id="employee_password" required><br><br>
                    <label for="employee_role">Role:</label>
                    <select name="employee_role" id="employee_role" required>
                        <option value="employee">Employee</option>
                        <option value="chief">Chief</option>
                    </select><br><br>
                    <label for="store_id">Store:</label>
                    <select name="store_id" id="store_id" required>
                        <option value="">-- Choose a store --</option>
                        <?php if (!empty($stores) && is_array($stores)): ?>
                            <?php foreach ($stores as $store): ?>
                                <option value="<?= htmlspecialchars($store['store_id']) ?>">
                                    <?= htmlspecialchars($store['store_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select><br><br>
                    <button type="submit" name="addEmployee">Add employee</button>
                </div>
            </form>
        </section>
    </main>
    <?php require_once '../www/footerChief.inc.php'; ?>