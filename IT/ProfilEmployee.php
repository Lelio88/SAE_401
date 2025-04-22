<?php
require_once __DIR__ . "/../bootstrap.php";
?>
<?php require_once '../www/headerIT.inc.php'; ?>
<title>Profile</title>
<style>
    input {
        width: 300px;
    }
</style>

<body>
    <section>
        <div class="name">
            <?php
            if (isset($_COOKIE['nom'])) {
                $nom = $_COOKIE['nom'];
            } else {
                echo "Cookie 'nom' not defined.";
            }
            echo "<h1> Welcome to your profile " . htmlspecialchars($nom) . "</h1>";
            ?>
        </div>
        <div class="infosCo">
            <form>
                <h2>Your login information</h2>
                <?php
                if (isset($_COOKIE["email"])) {
                    $email = $_COOKIE["email"];
                } else {
                    echo "Cookie 'email' not defined.";
                }
                echo "
                <h3>Your email</h3>
                <input type='text' name='email' disabled placeholder='email' value='" . htmlspecialchars($email) . "'>";
                if (isset($_COOKIE["password"])) {
                    $password = $_COOKIE["password"];
                } else {
                    echo "Cookie 'password' not defined.";
                }
                echo "
                <h3>Your password</h3>
                <input type='password' id='passwordInput' name='password' disabled placeholder='password' value='$password'>
                <button type='button' onclick='togglePassword()'>👁️</button>";
                ?>
            </form>
        </div>
    </section>
    <section>
        <div class="editProfile">
            <h2>Edit your information</h2>
            <form id="updateForm">
                <input type="hidden" id="employee_id" name="employee_id">

                <label for="employee_name">Full name:</label><br>
                <input type="text" id="employee_name" name="employee_name"><br><br>

                <label for="employee_email">E-mail :</label><br>
                <input type="email" id="employee_email" name="employee_email"><br><br>

                <label for="employee_password">Password :</label><br>
                <input type="password" id="employee_password" name="employee_password"><br><br>

                <label for="employee_role">Role :</label><br>
                <input type="text" id="employee_role" name="employee_role" readonly><br><br>

                <label for="store_id">Store ID :</label><br>
                <input type="text" id="store_id" name="store_id" readonly><br><br>

                <button type="button" onclick="updateEmployee()">Update</button>
            </form>
        </div>
    </section>
    <script>
        function togglePassword() {
            const input = document.getElementById("passwordInput");
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
    <script>
        // Récupération de l'ID depuis le cookie ou session (à adapter)
        const employeeId = <?php echo isset($_COOKIE['id']) ? intval($_COOKIE['id']) : 'null'; ?>;

        // Remplir le formulaire avec les données actuelles
        window.onload = () => {
            if (employeeId) {
                fetch(`/MMI2/SAE_401/api.php?action=employee&id=${employeeId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('employee_id').value = data.employee_id;
                        document.getElementById('employee_name').value = data.employee_name;
                        document.getElementById('employee_email').value = data.employee_email;
                        document.getElementById('employee_password').value = data.employee_password;
                        document.getElementById('employee_role').value = data.employee_role;
                        document.getElementById('store_id').value = data.store.store_id;
                    })
                    .catch(error => console.error('Erreur lors du chargement du profil:', error));
            } else {
                alert("ID non trouvé dans les cookies.");
            }
        };

        // Envoi de la requête PUT
        function updateEmployee() {
            const updatedData = {
                action: "updateEmployee",
                id: document.getElementById('employee_id').value,
                employee_name: document.getElementById('employee_name').value,
                employee_email: document.getElementById('employee_email').value,
                employee_password: document.getElementById('employee_password').value,
                // On les renvoie, même si on ne les modifie pas
                employee_role: document.getElementById('employee_role').value,
                store_id: document.getElementById('store_id').value
            };
            fetch('/MMI2/SAE_401/api.php?action=updateEmployee', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(updatedData)
                })
                .then(response => response.json())
                .then(result => {
                    alert(result.message || "Mise à jour effectuée !");
                    console.log(result);
                })
                .catch(error => {
                    alert("Erreur lors de la mise à jour");
                    console.error(error);
                });
        }
    </script>
    <?php require_once '../www/footerEmployee.inc.php'; ?>