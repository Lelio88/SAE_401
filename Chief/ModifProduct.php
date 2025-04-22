<?php require_once '../www/headerChief.inc.php'; ?>
<title>Modification of product information</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
    const employeeId = <?php echo json_encode($_COOKIE['id']); ?>;
    const API_URL = "https://dev-buton231.users.info.unicaen.fr/MMI2/SAE_401/api.php";

    $(document).ready(function() {
        // Sélecteur principal
        $("#Select").change(function() {
            const choice = $(this).val();
            $("#GestionMarques, #GestionCategories, #GestionStocks, #GestionMagasin, #productSelectSection, #productEditForm").hide();

            switch (choice) {
                case "brands":
                    $("#GestionMarques").show();
                    loadAllBrands();
                    break;
                case "categories":
                    $("#GestionCategories").show();
                    loadAllCategories();
                    break;
                case "products":
                    $("#productSelectSection").show();
                    loadAllProducts();
                    break;
                case "stocks":
                    $("#GestionStocks").show();
                    loadAllProductsForStock(); // Appel de la fonction ici
                    break;
                case "stores":
                    $("#GestionMagasin").show();
                    loadStoreInfo();
                    break;
            }
        });

        // ---------- PRODUITS ----------
        function loadAllProducts() {
            $.ajax({
                url: API_URL,
                data: {
                    action: "products"
                },
                dataType: "json",
                success: function(data) {
                    let options = "<option value=''>-- Choose a product --</option>";
                    data.forEach(prod => {
                        options += `<option value="${prod.product_id}">${prod.product_name}</option>`;
                    });
                    $("#ProductSelect").html(options);
                }
            });
        }

        $("#ProductSelect").change(function() {
            const id = $(this).val();
            if (!id) return $("#productEditForm").hide();

            $.ajax({
                url: API_URL,
                data: {
                    action: "product",
                    id: id
                },
                dataType: "json",
                success: function(product) {
                    $("#productName").val(product.product_name);
                    $("#productYear").val(product.model_year);
                    $("#productPrice").val(product.list_price);
                    $("#productEditForm").show().data("productId", product.product_id);
                }
            });
        });

        $("#productEditForm").submit(function(e) {
            e.preventDefault();

            const productId = $(this).data("productId");

            const formData = {
                product_name: $("#productName").val(),
                model_year: $("#productYear").val(),
                list_price: $("#productPrice").val(),
                brand_id: $("#BrandSelect").val(),
                category_id: $("#CategorySelect").val()
            };

            $.ajax({
                url: API_URL + '?action=updateProduct&id=' + productId,
                method: 'PUT',
                data: JSON.stringify(formData), // Convertir les données en JSON
                contentType: 'application/json', // Spécifier le type de contenu
                success: function(data) {
                    console.log(data);
                    alert("Mise à jour réussie !");
                    loadAllProducts();
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors de la mise à jour du produit : ", error);
                    alert("Échec de la mise à jour.");
                }
            });
        });

        // ---------- MARQUES ----------
        function loadAllBrands() {
            $.ajax({
                url: API_URL,
                data: {
                    action: "brands"
                },
                dataType: "json",
                success: function(data) {
                    let options = "<option value=''>-- Choose a brand --</option>";
                    data.forEach(brand => {
                        options += "<option value='" + brand.brand_id + "'>" + brand.brand_name + "</option>";
                    });
                    $("#marqueSelect").html(options); // Remplir le select avec les marques
                    $("#marqueSelect2").html(options); // Pour supprimer une marque aussi
                }
            });
        }

        let selectedBrandId = null;

        $(document).ready(function() {
            loadAllBrands();

            $("#marqueSelect").change(function() {
                selectedBrandId = $(this).val();
                if (!selectedBrandId) return $("#marqueEditForm").hide();

                $.ajax({
                    url: API_URL,
                    data: {
                        action: "brand",
                        id: selectedBrandId
                    },
                    dataType: "json",
                    success: function(brand) {
                        $("#brandName").val(brand.brand_name);
                        $("#marqueEditForm").show();
                    }
                });
            });

            $("#marqueEditForm").submit(function(e) {
                e.preventDefault();

                if (!selectedBrandId) {
                    alert("Aucune marque sélectionnée.");
                    return;
                }

                const formData = {
                    brand_name: $("#brandName").val()
                };

                $.ajax({
                    url: API_URL + '?action=updateBrand&id=' + selectedBrandId,
                    method: 'PUT',
                    data: JSON.stringify(formData), // Convertir les données en chaîne JSON
                    contentType: 'application/json', // Spécifier le type de contenu
                    success: function(data) {
                        console.log(data);
                        alert("Mise à jour réussie !");
                        loadAllBrands();
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur lors de la mise à jour de la marque : ", error);
                        alert("Échec de la mise à jour.");
                    }
                });
            });

            $("#AddMarqueForm").submit(function(e) {
                e.preventDefault(); // Empêche le rechargement de la page

                const newMarqueName = $("#newMarqueName").val().trim(); // Récupère le nom de la nouvelle marque

                // Vérifie que le nom de la marque n'est pas vide
                if (newMarqueName === "") {
                    alert("The brand name cannot be empty.");
                    return;
                }

                const formData = {
                    brand_name: newMarqueName,
                };

                $.ajax({
                    url: API_URL + '?action=addBrand',
                    method: 'POST',
                    data: formData,
                    success: function(data) {
                        console.log(data);
                        alert("Marque ajoutée avec succès !");
                        loadAllBrands();
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur lors de l'ajout de la marque : ", xhr.responseText);
                        alert("Échec de l'ajout de la marque.");
                    }
                });
            });
            $("#DeleteMarqueForm").submit(function(e) {
                e.preventDefault();

                const brandId = $("#marqueSelect2").val();
                if (!brandId) {
                    alert("Please select a brand to delete.");
                    return;
                }

                if (!confirm("Are you sure you want to delete this brand?")) return;

                $.ajax({
                    url: API_URL + '?action=deleteBrand&id=' + brandId,
                    method: 'DELETE',
                    success: function(data) {
                        console.log(data);
                        alert("Suppression réussie !");
                        loadAllBrands();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error deleting the brand: ", error);
                        alert("Deletion failed.");
                    }
                });
            });
        });

        // ---------- CATÉGORIES ----------
        function loadAllCategories() {
            $.ajax({
                url: API_URL,
                data: {
                    action: "categories"
                }, // Action pour charger les catégories
                dataType: "json",
                success: function(data) {
                    let options = "<option value=''>-- Choose a category --</option>";
                    data.forEach(category => {
                        options += `<option value="${category.category_id}">${category.category_name}</option>`;
                    });
                    $("#CategorySelect").html(options); // Remplir le select pour modifier une catégorie
                    $("#CategorySelect2").html(options); // Remplir le select pour supprimer une catégorie
                }
            });
        }

        let selectedCategoryId = null;

        $(document).ready(function() {
            loadAllCategories();

            // Changement de catégorie sélectionnée pour modifier
            $("#CategorySelect").change(function() {
                selectedCategoryId = $(this).val();
                if (!selectedCategoryId) return $("#CategoryEditForm").hide();

                $.ajax({
                    url: API_URL,
                    data: {
                        action: "category",
                        id: selectedCategoryId
                    }, // Action pour charger une catégorie spécifique
                    dataType: "json",
                    success: function(category) {
                        $("#categoryName").val(category.category_name);
                        $("#CategoryEditForm").show(); // Afficher le formulaire de modification
                    }
                });
            });

            // Formulaire de modification de catégorie
            $("#CategoryEditForm").submit(function(e) {
                e.preventDefault();

                if (!selectedCategoryId) {
                    alert("Aucune catégorie sélectionnée.");
                    return;
                }

                const formData = {
                    category_name: $("#categoryName").val()
                };

                $.ajax({
                    url: API_URL + '?action=updateCategory&id=' + selectedCategoryId,
                    method: 'PUT',
                    data: JSON.stringify(formData), // Convertir les données en JSON
                    contentType: 'application/json', // Spécifier le type de contenu
                    success: function(data) {
                        console.log(data);
                        alert("Successful update!");
                        loadAllCategories();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error updating category: ", error);
                        alert("Update failed.");
                    }
                });
            });

            // Formulaire d'ajout de catégorie
            $("#AddCategoryForm").submit(function(e) {
                e.preventDefault(); // Empêcher le rechargement de la page

                const newCategoryName = $("#newCategoryName").val().trim(); // Récupérer le nom de la nouvelle catégorie

                // Vérifie que le nom de la catégorie n'est pas vide
                if (newCategoryName === "") {
                    alert("Category name cannot be empty.");
                    return;
                }

                const formData = {
                    category_name: newCategoryName
                };

                $.ajax({
                    url: API_URL + '?action=addCategory', // Action pour ajouter une nouvelle catégorie
                    method: 'POST',
                    data: formData,
                    success: function(data) {
                        console.log(data);
                        alert("Category added successfully!");
                        loadAllCategories();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error adding category:", xhr.responseText);
                        alert("Failed to add category.");
                    }
                });
            });

            // Formulaire de suppression de catégorie
            $("#DeleteCategoryForm").submit(function(e) {
                e.preventDefault();

                const categoryId = $("#CategorySelect2").val();
                if (!categoryId) {
                    alert("Please select a category to delete.");
                    return;
                }

                if (!confirm("Are you sure you want to delete this category?")) return;

                $.ajax({
                    url: API_URL + '?action=deleteCategory&id=' + categoryId, // Action pour supprimer une catégorie
                    method: 'DELETE',
                    success: function(data) {
                        console.log(data);
                        alert("Suppression réussie !");
                        loadAllCategories();
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur lors de la suppression de la catégorie : ", error);
                        alert("Échec de la suppression.");
                    }
                });
            });
        });

        // ---------- STOCK ----------
        // réccupération de l'ID du magasin dans le cookie "store"
        let storeId = <?php echo json_encode($_COOKIE['store']); ?>;
        // Charger tous les produits dans le select pour la gestion du stock
        function loadAllProductsForStock() {
            $.ajax({
                url: API_URL,
                data: {
                    action: "products"
                },
                dataType: "json",
                success: function(data) {
                    let options = "<option value=''>-- Choose a product --</option>";
                    data.forEach(prod => {
                        options += `<option value="${prod.product_id}">${prod.product_name}</option>`;
                    });
                    $("#ProductSelect2").html(options); // Remplir le select avec les produits
                }
            });
        }

        $("#ProductSelect2").change(function() {
            const productId = $(this).val();

            // Si l'ID du magasin n'est pas encore récupéré, le récupérer
            if (!storeId) {
                $.get(API_URL, {
                    action: "employee",
                    id: employeeId
                }, function(data) {
                    storeId = data.store_id.store_id; // Enregistrer le store_id dans la variable globale
                    if (!productId || !storeId) return $("#StockManagementForm").hide(); // Masquer le formulaire si aucun produit ou magasin sélectionné

                    // Appeler l'API pour obtenir le stock du produit sélectionné pour ce magasin
                    $.ajax({
                        url: API_URL,
                        data: {
                            action: "get_stock",
                            product_id: productId,
                            store_id: storeId // Passer uniquement l'ID du magasin (valeur numérique)
                        },
                        dataType: "json",
                        success: function(stocks) {
                            if (stocks.quantity !== undefined) {
                                $("#quantity").val(stocks.quantity); // Afficher la quantité actuelle en stock
                                $("#StockManagementForm").show(); // Afficher le formulaire de gestion du stock
                            } else {
                                alert("Stock non disponible pour ce produit dans ce magasin.");
                            }
                        },
                        error: function() {
                            alert("Erreur lors de la récupération du stock.");
                        }
                    });
                }, "json");
            } else {
                // Si le storeId a déjà été récupéré, on utilise directement l'ID pour la requête
                if (!productId || !storeId) return $("#StockManagementForm").hide(); // Masquer le formulaire si aucun produit ou magasin sélectionné

                // Appeler l'API pour obtenir le stock du produit sélectionné pour ce magasin
                $.ajax({
                    url: API_URL,
                    data: {
                        action: "get_stock",
                        product_id: productId,
                        store_id: storeId // Passer uniquement l'ID du magasin (valeur numérique)
                    },
                    dataType: "json",
                    success: function(stocks) {
                        if (stocks.quantity !== undefined) {
                            $("#quantity").val(stocks.quantity); // Afficher la quantité actuelle en stock
                            $("#StockManagementForm").show(); // Afficher le formulaire de gestion du stock
                        } else {
                            alert("Stock non disponible pour ce produit dans ce magasin.");
                        }
                    },
                    error: function() {
                        alert("Erreur lors de la récupération du stock.");
                    }
                });
            }
        });

        // Quand l'utilisateur soumet une mise à jour du stock pour un magasin spécifique
        $("#UpdateStockButton").click(function(e) {
            e.preventDefault();

            const productId = $("#ProductSelect2").val();
            const updatedQuantity = $("#quantity").val();

            // Vérifier si un produit est sélectionné et si une quantité est entrée
            if (!productId) {
                alert("Veuillez sélectionner un produit.");
                return;
            }
            if (updatedQuantity === "") {
                alert("Veuillez entrer une quantité.");
                return;
            }
            if (!storeId) {
                alert("Store ID manquant.");
                return;
            }

            $.ajax({
                url: API_URL + '?action=updateStock',
                method: 'PUT',
                contentType: 'application/json', // Indiquer que le corps est en JSON
                data: JSON.stringify({ // Convertir l'objet en JSON
                    product_id: productId,
                    quantity: updatedQuantity,
                    store_id: storeId
                }),
                success: function(data) {
                    console.log("Données envoyées à l'API : ", {
                        product_id: productId,
                        quantity: updatedQuantity,
                        store_id: storeId
                    });
                    alert("Stock mis à jour avec succès !");
                    loadAllProductsForStock();
                    $("#StockManagementForm").hide();
                },
                error: function(xhr, status, error) {
                    alert("Erreur lors de la mise à jour du stock.");
                }
            });

            // Charger les produits au démarrage de la page
            loadAllProductsForStock();
        });
        // ---------- INFOS MAGASIN ----------
        function loadStoreInfo() {
            $.get(API_URL, {
                action: "employee",
                id: employeeId
            }, function(data) {
                $("#storeId").val(data.store.store_id);
                $("#storeName").val(data.store.store_name);
                $("#street").val(data.store.street);
                $("#city").val(data.store.city);
                $("#state").val(data.store.state);
                $("#zip_code").val(data.store.zip_code);
                $("#storePhone").val(data.store.phone);
                $("#storeEmail").val(data.store.email);
            }, "json");
        }

        $("#magasinForm").submit(function(e) {
            e.preventDefault();

            const formData = {
                store_id: $("#storeId").val(),
                store_name: $("#storeName").val(),
                street: $("#street").val(),
                city: $("#city").val(),
                state: $("#state").val(),
                zip_code: $("#zip_code").val(),
                phone: $("#storePhone").val(),
                email: $("#storeEmail").val()
            };

            $.ajax({
                url: API_URL + '?action=updateStore&id=' + employeeId,
                method: 'PUT',
                contentType: 'application/json', // Indiquer que le corps est en JSON
                data: JSON.stringify({
                    store_id: formData.store_id,
                    store_name: formData.store_name,
                    street: formData.street,
                    city: formData.city,
                    state: formData.state,
                    zip_code: formData.zip_code,
                    phone: formData.phone,
                    email: formData.email
                }),
                success: function(data) {
                    console.log(data);
                    alert("Mise à jour réussie !");
                },
                error: function() {
                    alert("Échec de la mise à jour.");
                }
            });
        });
    });
</script>
</head>

<body>
    <div id="content">
        <h1>Editing data</h1>

        <section>
            <h2>Choose a data type to edit</h2>
            <form>
                <select id="Select">
                    <option value="" disabled selected>Selection</option>
                    <option value="brands">Brands</option>
                    <option value="categories">Categories</option>
                    <option value="products">Products</option>
                    <option value="stocks">Stocks</option>
                    <option value="stores">Store Info</option>
                </select>
            </form>

            <!-- Produits -->
            <div id="productSelectSection" style="display:none; margin-top: 1em;">
                <label for="ProductSelect">Product:</label>
                <select id="ProductSelect"></select>
            </div>

            <form id="productEditForm" style="display:none; margin-top: 1em;">
                <h3>Edit product</h3>
                <label>Name: <input type="text" id="productName" required /></label><br />
                <label>Year: <input type="number" id="productYear" required /></label><br />
                <label>Price ($): <input type="number" id="productPrice" step="0.01" required /></label><br />
                <button type="submit">Save changes</button>
            </form>

            <!-- Marques -->
            <div id="GestionMarques" style="display:none; width: 40%;">
                <div>
                    <h3>Edit a brand</h3>
                    <select name="marque" id="marqueSelect"></select>

                    <form id="marqueEditForm" style="display:none; margin-top: 1em;">
                        <input type="text" id="brandName" required /><br><br>
                        <button type="submit">Save changes</button>
                    </form>
                </div>

                <div>
                    <h3>Delete a brand</h3>
                    <form action="" id="DeleteMarqueForm">
                        <select name="marque" id="marqueSelect2"></select>
                        <button type="submit">Delete</button>
                    </form>
                </div>

                <div>
                    <h3>Add a brand</h3>
                    <form action="" id="AddMarqueForm">
                        <input type="text" id="newMarqueName" placeholder="Brand name" required>
                        <button type="submit">Add brand</button>
                    </form>
                </div>
            </div>

            <!-- Catégories -->
            <div id="GestionCategories" style="display:none;">
                <div>
                    <h3>Edit a category</h3>
                    <select name="categorie" id="CategorySelect"></select>

                    <form id="CategoryEditForm" style="display:none; margin-top: 1em;">
                        <input type="text" id="categoryName" required /><br><br>
                        <button type="submit">Save changes</button>
                    </form>
                </div>

                <div>
                    <h3>Delete a category</h3>
                    <form action="" id="DeleteCategoryForm">
                        <select name="categorie" id="CategorySelect2"></select>
                        <button type="submit">Delete</button>
                    </form>
                </div>

                <div>
                    <h3>Add a category</h3>
                    <form action="" id="AddCategoryForm">
                        <input type="text" id="newCategoryName" placeholder="Name of a category" required>
                        <button type="submit">Add a category</button>
                    </form>
                </div>
            </div>

            <!-- Stocks  -->
            <div id="GestionStocks" style="display:none;">
                <div>
                    <h3>Manage stock</h3>
                    <!-- Sélectionner un produit -->
                    <select name="produit" id="ProductSelect2">
                        <!-- Les produits seront chargés via AJAX -->
                    </select>
                    <br><br>

                    <!-- Formulaire pour modifier la quantité -->
                    <div id="StockManagementForm" style="display:none;">
                        <label for="quantity">Quantité</label>
                        <input type="number" id="quantity" min="0" required />
                        <button type="submit" id="UpdateStockButton">Saves changes</button>
                    </div>
                </div>
            </div>

            <!-- Infos du Magasin -->
            <div id="GestionMagasin" style="display:none; margin-top: 1em;">
                <h3>Store information</h3>
                <form id="magasinForm">
                    <label>Store ID: <input type="text" id="storeId" disabled /></label><br />
                    <label>Store Name: <input type="text" id="storeName" required /></label><br />
                    <label>Street: <input type="text" id="street" /></label><br />
                    <label>City: <input type="text" id="city" /></label><br />
                    <label>State: <input type="text" id="state" /></label><br />
                    <label>Postcode: <input type="text" id="zip_code" /></label><br />
                    <label>Phone: <input type="text" id="storePhone" /></label><br />
                    <label>Email: <input type="email" id="storeEmail" /></label><br />
                    <button type="submit">Save</button>
                </form>
            </div>
        </section>
    </div>
    <?php require_once("../www/footerChief.inc.php"); ?>
</body>

</html>