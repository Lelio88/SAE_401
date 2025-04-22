<!DOCTYPE html>
<html lang="fr">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product List</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        loadBrands(); // Charger les marques dès que la page est prête
        loadCategories(); // Charger les catégories dès que la page est prête
        loadYears(); // Charger les années dès que la page est prête
        loadProducts(8); // Chargement initial des 8 premiers produits

        // Mettre à jour les produits lors de la sélection des filtres
        $("#BrandSelect, #CategorySelect, #YearSelect, #PriceSelect").change(function() {
            loadProducts(15); // Chargement des 10 produits quand un filtre est appliqué
        });
    });

    // Fonction pour récupérer et charger les marques dans le select
    function loadBrands() {
        $.ajax({
            url: "https://dev-buton231.users.info.unicaen.fr/MMI2/SAE_401/api.php",
            data: {
                action: "brands"
            }, // Action pour obtenir les marques
            dataType: "json",
            success: function(data) {
                let html = "<option value=''>All</option>";
                for (let i = 0; i < data.length; i++) {
                    html += "<option value='" + data[i].brand_id + "'>" + data[i].brand_name + "</option>";
                }
                $("#BrandSelect").html(html); // Remplir le select avec les marques
            }
        });
    }
    // Fonction pour récupérer et charger les catégories dans le select
    function loadCategories() {
        $.ajax({
            url: "https://dev-buton231.users.info.unicaen.fr/MMI2/SAE_401/api.php",
            data: {
                action: "categories"
            }, // Action pour obtenir les catégories
            dataType: "json",
            success: function(data) {
                let html = "<option value=''>All</option>";
                for (let i = 0; i < data.length; i++) {
                    html += "<option value='" + data[i].category_id + "'>" + data[i].category_name + "</option>";
                }
                $("#CategorySelect").html(html); // Remplir le select avec les catégories
            }
        });
    }
    // Fonction pour récupérer et charger les années dans le select
    function loadYears() {
        $.ajax({
            url: "https://dev-buton231.users.info.unicaen.fr/MMI2/SAE_401/api.php",
            data: {
                action: "years"
            }, // Action pour obtenir les années
            dataType: "json",
            success: function(data) {
                let html = "<option value=''>All</option>";
                for (let i = 0; i < data.length; i++) {
                    html += "<option value='" + data[i] + "'>" + data[i] + "</option>";
                }
                $("#YearSelect").html(html); // Remplir le select avec les années
            }
        });
    }

    // Fonction pour charger les produits en fonction des filtres
    function loadProducts(limit) {
        let brand = $("#BrandSelect").val();
        let category = $("#CategorySelect").val();
        let year = $("#YearSelect").val();
        let price = $("#PriceSelect").val();
        $.ajax({
            url: "https://dev-buton231.users.info.unicaen.fr/MMI2/SAE_401/api.php",
            data: {
                brand: brand,
                category: category,
                year: year,
                price: price,
                limit: limit
            },
            dataType: "json",
            success: function(data) {
                console.log(data);
                let html = "";
                for (let i = 0; i < data.length; i++) {
                    html += "<div><h3>" + data[i].product_name + "</h3><p>" + data[i].list_price + "</p></div>";
                }
                $("#ListeProd").html(html);
                console.log("test");
            }

        });
    }
</script>
<style>
    #header {
        display: flex;
        background-color: #5DA35F;
        justify-content: space-around;
        padding: 10px;
        align-items: center;
    }

    body {
        margin: 0;
        height: 150vh;
        background-image: url("../images/fond.jpg");
        background-size: cover;
        background-repeat: no-repeat;
    }

    #content {
        background-color: #76C586;
        height: 110vh;
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

    h1 {
        margin-left: 20px;
        color: #4A5F3A;
    }

    h2 {
        margin-left: 20px;
        color: darkslategray;
    }

    form {
        margin: 20px;

    }

    #ListeProd {
        display: grid;
        /* Utilisation de la grille pour une gestion simple des colonnes */
        grid-template-columns: repeat(3, 1fr);
        /* Crée 3 colonnes égales */
        gap: 20px;
        /* Espacement entre les produits */
        padding: 20px;
    }

    #ListeProd div {
        border: 2px solid #4A5F3A;
        /* Bordure autour de chaque produit */
        padding: 15px;
        /* Espacement à l'intérieur de chaque div produit */
        background-color: white;
        /* Fond blanc pour chaque produit */
        border-radius: 8px;
        /* Coins arrondis pour un effet plus doux */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Ombre subtile autour du produit */
        box-sizing: border-box;
        /* Assure que padding et bordure sont inclus dans la largeur */
    }

    body section {
        background-color: #76C586;
    }
</style>
</head>
<div id="content">
    <nav id="header">
        <a href='HomeClient.php'><img src="../images/velo.png" alt="logo" style="width: 50px; height: 50px; "></a>
        <a href="ProductList.php" id="prod">Our products</a>
        <a href="../index.html"><img src="../images/exit.png" alt="exitlogo" style="width: 50px; height: 50px; "></a>
    </nav>

    <body>
        <section>
            <h1>Product List</h1>
            <h2>Filter your search</h2>
            <form>
                <p>
                    <label for="BrandSelect">Choose a brand:</label>
                    <select id="BrandSelect">
                        <option value="">All</option>
                    </select>
                </p>
                <br />
                <p>
                    <label for="CategorySelect">Choose a category:</label>
                    <select id="CategorySelect">
                        <option value="">All</option>
                    </select>
                </p>
                <br />
                <p>
                    <label for="YearSelect">Choose a year:</label>
                    <select id="YearSelect">
                        <option value="">All</option>
                    </select>
                </p>
                <br />
                <p>
                    <label for="PriceSelect">Choose a price:</label>
                    <input type="range" id="PriceSelect" min="0" max="12000" value="500">
                </p>
            </form>
            <div id="ListeProd"></div>
        </section>
        <?php require_once '../www/footerClient.inc.php'; ?>