<?php

/**
 * BikeShop API
 * 
 * This file provides a RESTful API for managing bike shop inventory and staff
 * including stores, products, categories, brands, stock levels, and employees.
 * 
 * @package     BikeShop
 * @author      Your Name
 * @version     1.0
 * @copyright   Copyright (c) 2025
 */

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//echo "Début API<br>";
//var_dump($_REQUEST);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allowed-Methods: GET, POST, PUT, DELETE");

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity\Stores;
use Entity\Products;
use Entity\Categories;
use Entity\Brands;
use Entity\Stocks;
use Entity\Employees;
use LDAP\Result;

require __DIR__ . "/bootstrap.php";
require_once("vendor/autoload.php");

/**
 * API Request Handler
 * 
 * Handles incoming API requests and routes them to appropriate handlers based on
 * HTTP method (GET, POST, PUT, DELETE) and action parameters.
 */
$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (isset($_REQUEST['action'])) {
            /**
             * Get All Stores
             * 
             * Retrieves a list of all stores in the database.
             * 
             * @param string $_REQUEST['action'] Must be 'stores'
             * @return json JSON-encoded array of store objects
             */
            if ($_REQUEST['action'] == 'stores' && !isset($_REQUEST['id'])) {
                $stores = $entityManager->getRepository(Stores::class)->findAll();
                echo json_encode($stores);
            }
            /**
             * Get Store by ID
             * 
             * Retrieves a specific store by its ID.
             * 
             * @param string $_REQUEST['action'] Must be 'store'
             * @param int $_REQUEST['id'] The store ID to retrieve
             * @return json JSON-encoded store object
             */
            else if ($_REQUEST['action'] == 'store' && isset($_REQUEST['id'])) {
                $store = $entityManager->getRepository(Stores::class)->find($_REQUEST['id']);
                echo json_encode($store);
            }
            /**
             * Get All Products
             * 
             * Retrieves a list of all products in the database.
             * 
             * @param string $_REQUEST['action'] Must be 'products'
             * @return json JSON-encoded array of product objects
             */
            else if ($_REQUEST['action'] == 'products' && !isset($_REQUEST['id'])) {
                $products = $entityManager->getRepository(Products::class)->findAll();
                echo json_encode($products);
            }
            /**
             * Get Distinct Model Years
             * 
             * Retrieves a list of all distinct model years from products.
             * 
             * @param string $_REQUEST['action'] Must be 'years'
             * @return json JSON-encoded array of years
             */
            else if ($_REQUEST['action'] == 'years') {
                $years = $entityManager->getRepository(Products::class)->createQueryBuilder('P')
                    ->select('DISTINCT(P.model_year)')
                    ->getQuery()
                    ->getResult();
                $newyears = array();
                foreach ($years as $key => $value) {
                    $newyears[$key] = $value[1];
                }
                echo json_encode($newyears);
            }
            /**
             * Get Product by ID
             * 
             * Retrieves a specific product by its ID.
             * 
             * @param string $_REQUEST['action'] Must be 'product'
             * @param int $_REQUEST['id'] The product ID to retrieve
             * @return json JSON-encoded product object
             */
            else if ($_REQUEST['action'] == 'product' && isset($_REQUEST['id'])) {
                $product = $entityManager->getRepository(Products::class)->find($_REQUEST['id']);
                echo json_encode($product);
            }
            /**
             * Get All Categories
             * 
             * Retrieves a list of all product categories.
             * 
             * @param string $_REQUEST['action'] Must be 'categories'
             * @return json JSON-encoded array of category objects
             */
            else if ($_REQUEST['action'] == 'categories' && !isset($_REQUEST['id'])) {
                $categories = $entityManager->getRepository(Categories::class)->findAll();
                echo json_encode($categories);
            }
            /**
             * Get Category by ID
             * 
             * Retrieves a specific category by its ID.
             * 
             * @param string $_REQUEST['action'] Must be 'category'
             * @param int $_REQUEST['id'] The category ID to retrieve
             * @return json JSON-encoded category object
             */
            else if ($_REQUEST['action'] == 'category' && isset($_REQUEST['id'])) {
                $category = $entityManager->getRepository(Categories::class)->find($_REQUEST['id']);
                echo json_encode($category);
            }
            /**
             * Get All Brands
             * 
             * Retrieves a list of all product brands.
             * 
             * @param string $_REQUEST['action'] Must be 'brands'
             * @return json JSON-encoded array of brand objects
             */
            else if ($_REQUEST['action'] == 'brands' && !isset($_REQUEST['id'])) {
                $brands = $entityManager->getRepository(Brands::class)->findAll();
                echo json_encode($brands);
            }
            /**
             * Get Brand by ID
             * 
             * Retrieves a specific brand by its ID.
             * 
             * @param string $_REQUEST['action'] Must be 'brand'
             * @param int $_REQUEST['id'] The brand ID to retrieve
             * @return json JSON-encoded brand object
             */
            else if ($_REQUEST['action'] == 'brand' && isset($_REQUEST['id'])) {
                $brand = $entityManager->getRepository(Brands::class)->find($_REQUEST['id']);
                echo json_encode($brand);
            }
            /**
             * Get All Stocks
             * 
             * Retrieves a list of all inventory stock records.
             * 
             * @param string $_REQUEST['action'] Must be 'stocks'
             * @return json JSON-encoded array of stock objects
             */
            else if ($_REQUEST['action'] == 'stocks' && !isset($_REQUEST['id'])) {
                $stocks = $entityManager->getRepository(Stocks::class)->findAll();
                echo json_encode($stocks);
            }
            /**
             * Get Stock by ID
             * 
             * Retrieves a specific stock record by its ID.
             * 
             * @param string $_REQUEST['action'] Must be 'stock'
             * @param int $_REQUEST['id'] The stock ID to retrieve
             * @return json JSON-encoded stock object
             */
            else if ($_REQUEST['action'] == 'stock' && isset($_REQUEST['id'])) {
                $stock = $entityManager->getRepository(Stocks::class)->find($_REQUEST['id']);
                echo json_encode($stock);
            }
            /**
             * Get All Employees
             * 
             * Retrieves a list of all employees.
             * 
             * @param string $_REQUEST['action'] Must be 'employees'
             * @return json JSON-encoded array of employee objects
             */
            else if ($_REQUEST['action'] == 'employees' && !isset($_REQUEST['id'])) {
                $employees = $entityManager->getRepository(Employees::class)->findAll();
                echo json_encode($employees);
            }
            /**
             * Get Employee by ID
             * 
             * Retrieves a specific employee by its ID.
             * 
             * @param string $_REQUEST['action'] Must be 'employee'
             * @param int $_REQUEST['id'] The employee ID to retrieve
             * @return json JSON-encoded employee object
             */
            else if ($_REQUEST['action'] == 'employee' && isset($_REQUEST['id'])) {
                $employee = $entityManager->getRepository(Employees::class)->find($_REQUEST['id']);
                echo json_encode($employee);
            }
            /**
             * Get Stock by Product and Store
             * 
             * Retrieves the stock quantity for a specific product in a specific store.
             * 
             * @param string $_REQUEST['action'] Must be 'get_stock'
             * @param int $_REQUEST['product_id'] The product ID
             * @param int $_REQUEST['store_id'] The store ID
             * @return json JSON-encoded object containing the stock quantity or 0 if not found
             */
            else if ($_REQUEST['action'] == 'get_stock' && isset($_REQUEST['product_id']) && isset($_REQUEST['store_id'])) {
                $product_id = $_REQUEST['product_id'];
                $store_id = $_REQUEST['store_id'];

                $stock = $entityManager->getRepository(Stocks::class)->findOneBy([
                    'product' => $product_id,
                    'store' => $store_id
                ]);

                if ($stock) {
                    echo json_encode(['quantity' => $stock->getQuantity()]);
                } else {
                    echo json_encode(['quantity' => 0]); // No stock found for this product in this store
                }
            }
            /**
             * Get Store Name by ID
             * 
             * Retrieves the name of a store by its ID.
             * 
             * @param string $_REQUEST['action'] Must be 'getStoreName'
             * @param int $_REQUEST['store_id'] The store ID
             * @return json JSON-encoded object containing the store name or error message
             */
            else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getStoreName' && isset($_REQUEST['store_id'])) {
                $store_id = $_REQUEST['store_id'];
                $store = $entityManager->getRepository(Stores::class)->find($store_id);
                if ($store) {
                    echo json_encode(['store_name' => $store->getStoreName()]);
                } else {
                    echo json_encode(['error' => 'Store not found']);
                }
            }
            /**
             * Get Employees by Store
             * 
             * Retrieves a list of employees for a specific store.
             * 
             * @param string $_REQUEST['action'] Must be 'getEmployeesByStore'
             * @param int $_REQUEST['store_id'] The store ID
             * @return json JSON-encoded array of employee objects
             */
            else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getEmployeesByStore' && isset($_REQUEST['store_id'])) {
                $store_id = $_REQUEST['store_id'];
                $employees = $entityManager->getRepository(Employees::class)
                    ->createQueryBuilder('e')
                    ->where('e.store = :store_id')
                    ->setParameter('store_id', $store_id)
                    ->getQuery()
                    ->getResult();
                $data = [];
                foreach ($employees as $e) {
                    $data[] = [
                        'id' => $e->getEmployeeId(),
                        'name' => $e->getEmployeeName(),
                        'role' => $e->getEmployeeRole(),
                    ];
                }
                echo json_encode($data);
            }
        }
        /**
         * Filter Products
         * 
         * Retrieves a filtered list of products based on brand, category, year, and price.
         * 
         * @param string $_REQUEST['brand'] Optional brand ID to filter by
         * @param string $_REQUEST['category'] Optional category ID to filter by
         * @param string $_REQUEST['year'] Optional model year to filter by
         * @param string $_REQUEST['price'] Optional maximum price to filter by
         * @param int $_REQUEST['limit'] Maximum number of products to return
         * @return json JSON-encoded array of product objects
         */
        else if (isset($_REQUEST['brand']) && isset($_REQUEST['category']) && isset($_REQUEST['year']) && isset($_REQUEST['price']) && isset($_REQUEST['limit'])) {
            /* DQL */
            $products = $entityManager->getRepository(Products::class)->createQueryBuilder('p')
                ->select('p')
                ->join('p.brand', 'b')
                ->join('p.category', 'c');
            if ($_REQUEST['brand'] !== "") {
                $products->andWhere('p.brand = :brand')
                    ->setParameter('brand', $_REQUEST['brand']);
            }
            if ($_REQUEST['category'] !== "") {
                $products->andWhere('p.category = :category')
                    ->setParameter('category', $_REQUEST['category']);
            }
            if ($_REQUEST['year'] !== "") {
                $products->andWhere('p.model_year = :year')
                    ->setParameter('year', $_REQUEST['year']);
            }
            if ($_REQUEST['price'] !== "") {
                $products->andWhere('p.list_price BETWEEN 0 AND :price')
                    ->setParameter('price', $_REQUEST['price']);
            }
            $products->orderBy('p.product_name')->setMaxResults($_REQUEST['limit']);
            $products = $products->getQuery()->getResult();
            echo json_encode($products);
        }
        break;
    case 'POST':
        /**
         * Add New Store
         * 
         * Creates a new store record in the database.
         * 
         * @param string $_REQUEST['action'] Must be 'addStore'
         * @param string $_REQUEST['store_name'] Name of the store
         * @param string $_REQUEST['phone'] Phone number
         * @param string $_REQUEST['email'] Email address
         * @param string $_REQUEST['street'] Street address
         * @param string $_REQUEST['city'] City
         * @param string $_REQUEST['state'] State/province
         * @param string $_REQUEST['zip_code'] ZIP/postal code
         * @return json JSON-encoded status response
         */
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addStore') {
            $store = new Stores();
            $store->setStoreName($_REQUEST['store_name']);
            $store->setPhone($_REQUEST['phone']);
            $store->setEmail($_REQUEST['email']);
            $store->setStreet($_REQUEST['street']);
            $store->setCity($_REQUEST['city']);
            $store->setState($_REQUEST['state']);
            $store->setZipCode($_REQUEST['zip_code']);
            $entityManager->persist($store);
            $entityManager->flush();
            echo json_encode(['status' => 'success', 'message' => 'Store added successfully']);
        }
        /**
         * Add New Product
         * 
         * Creates a new product record in the database.
         * 
         * @param string $_REQUEST['action'] Must be 'addProduct'
         * @param string $_REQUEST['product_name'] Name of the product
         * @param int $_REQUEST['brand_id'] ID of the brand
         * @param int $_REQUEST['category_id'] ID of the category
         * @param int $_REQUEST['model_year'] Model year
         * @param float $_REQUEST['list_price'] List price
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addProduct') {
            $product = new Products();
            $product->setProductName($_REQUEST['product_name']);
            $product->setBrandId($entityManager->getRepository(Brands::class)->find($_REQUEST['brand_id']));
            $product->setCategoryId($entityManager->getRepository(Categories::class)->find($_REQUEST['category_id']));
            $product->setModelYear($_REQUEST['model_year']);
            $product->setListPrice($_REQUEST['list_price']);
            $entityManager->persist($product);
            $entityManager->flush();
            echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
        }
        /**
         * Add New Category
         * 
         * Creates a new category record in the database.
         * 
         * @param string $_REQUEST['action'] Must be 'addCategory'
         * @param string $_REQUEST['category_name'] Name of the category
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addCategory') {
            $category = new Categories();
            $category->setCategoryName($_REQUEST['category_name']);
            $entityManager->persist($category);
            $entityManager->flush();
            echo json_encode(['status' => 'success', 'message' => 'Category added successfully']);
        }
        /**
         * Add New Brand
         * 
         * Creates a new brand record in the database.
         * 
         * @param string $_REQUEST['action'] Must be 'addBrand'
         * @param string $_REQUEST['brand_name'] Name of the brand
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addBrand') {
            $brand = new Brands();
            $brand->setBrandName($_REQUEST['brand_name']);
            $entityManager->persist($brand);
            $entityManager->flush();
            echo json_encode(['status' => 'success', 'message' => 'Brand added successfully']);
        }
        /**
         * Add New Stock
         * 
         * Creates a new stock record in the database.
         * 
         * @param string $_REQUEST['action'] Must be 'addStock'
         * @param int $_POST['store_id'] ID of the store
         * @param int $_POST['product_id'] ID of the product
         * @param int $_REQUEST['quantity'] Stock quantity
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addStock') {
            $stock = new Stocks();
            $stock->setStoreId($entityManager->getRepository(Stores::class)->find($_POST['store_id']));
            $stock->setProductId($entityManager->getRepository(Products::class)->find($_POST['product_id']));
            $stock->setQuantityId($_REQUEST['quantity']);
            $entityManager->persist($stock);
            $entityManager->flush();
            echo json_encode(['status' => 'success', 'message' => 'Stock added successfully']);
        }
        /**
         * Add New Employee
         * 
         * Creates a new employee record in the database.
         * 
         * @param string $_REQUEST['action'] Must be 'addEmployee'
         * @param int $_POST['store_id'] ID of the store
         * @param string $_REQUEST['employee_name'] Name of the employee
         * @param string $_REQUEST['employee_email'] Email of the employee
         * @param string $_REQUEST['employee_password'] Password of the employee
         * @param string $_REQUEST['employee_role'] Role of the employee
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addEmployee') {
            $employee = new Employees();
            $store = $entityManager->getRepository(Stores::class)->find($_POST['store_id']);
            $employee->setStoreId($store);
            $employee->setEmployeeName($_REQUEST['employee_name']);
            $employee->setEmployeeEmail($_REQUEST['employee_email']);
            $employee->setEmployeePassword($_REQUEST['employee_password']);
            $employee->setEmployeeRole($_REQUEST['employee_role']);
            $entityManager->persist($employee);
            $entityManager->flush();
            echo json_encode(['status' => 'success', 'message' => 'Employee added successfully']);
        }
        break;
    case 'PUT':
        /**
         * Update Store
         * 
         * Updates an existing store record in the database.
         * 
         * @param string $_REQUEST['action'] Must be 'updateStore'
         * @param int $_REQUEST['id'] ID of the store to update
         * @param array $putData JSON data containing updated store information:
         *              - int store_id
         *              - string store_name
         *              - string phone
         *              - string email
         *              - string street
         *              - string city
         *              - string state
         *              - string zip_code
         * @return json JSON-encoded status response
         */
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateStore' && isset($_REQUEST['id'])) {
            // Récupérer les données JSON du corps de la requête PUT
            $putData = json_decode(file_get_contents("php://input"), true);
            $store = $entityManager->getRepository(Stores::class)->find($_REQUEST['id']);
            if ($store) {
                // Utiliser les données du corps JSON
                $store->setStoreId($putData['store_id']);
                $store->setStoreName($putData['store_name']);
                $store->setPhone($putData['phone']);
                $store->setEmail($putData['email']);
                $store->setStreet($putData['street']);
                $store->setCity($putData['city']);
                $store->setState($putData['state']);
                $store->setZipCode($putData['zip_code']);
                $entityManager->flush();
                echo json_encode(['status' => 'success', 'message' => 'Store updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Store not found']);
            }
        }
        /**
         * Update Product
         * 
         * Updates an existing product record in the database.
         * 
         * @param string $_REQUEST['action'] Must be 'updateProduct'
         * @param int $_REQUEST['id'] ID of the product to update
         * @param array $putData JSON data containing updated product information:
         *              - string product_name
         *              - int brand_id (optional)
         *              - int category_id (optional)
         *              - int model_year
         *              - float list_price
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateProduct' && isset($_REQUEST['id'])) {
            // Récupérer les données JSON du corps de la requête PUT
            $putData = json_decode(file_get_contents("php://input"), true);
            $product = $entityManager->getRepository(Products::class)->find($_REQUEST['id']);
            if ($product) {
                $product->setProductName($putData['product_name']);

                // Vérifiez si brand_id et category_id sont présents
                if (isset($putData['brand_id'])) {
                    $brand = $entityManager->getRepository(Brands::class)->find($putData['brand_id']);
                    if ($brand) {
                        $product->setBrand($brand);
                    }
                }

                if (isset($putData['category_id'])) {
                    $category = $entityManager->getRepository(Categories::class)->find($putData['category_id']);
                    if ($category) {
                        $product->setCategory($category);
                    }
                }

                $product->setModelYear($putData['model_year']);
                $product->setListPrice($putData['list_price']);

                $entityManager->flush();
                echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Product not found']);
            }
        }
        /**
         * Update Category
         * 
         * Updates an existing category record in the database.
         * 
         * @param string $_REQUEST['action'] Must be 'updateCategory'
         * @param int $_REQUEST['id'] ID of the category to update
         * @param array $putData JSON data containing updated category information:
         *              - string category_name
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateCategory' && isset($_REQUEST['id'])) {
            // Récupérer les données JSON du corps de la requête PUT
            $putData = json_decode(file_get_contents("php://input"), true);

            $category = $entityManager->getRepository(Categories::class)->find($_REQUEST['id']);
            if ($category) {
                // Utiliser les données du corps JSON
                $category->setCategoryName($putData['category_name']);
                $entityManager->flush();
                echo json_encode(['status' => 'success', 'message' => 'Category updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Category not found']);
            }
        }
        /**
         * Update Brand
         * 
         * Updates an existing brand record in the database.
         * 
         * @param string $_REQUEST['action'] Must be 'updateBrand'
         * @param int $_REQUEST['id'] ID of the brand to update
         * @param array $putData JSON data containing updated brand information:
         *              - string brand_name
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateBrand' && isset($_REQUEST['id'])) {
            // Récupérer les données JSON du corps de la requête PUT
            $putData = json_decode(file_get_contents("php://input"), true);

            $brand = $entityManager->getRepository(Brands::class)->find($_REQUEST['id']);
            if ($brand) {
                // Utiliser les données du corps JSON
                $brand->setBrandName($putData['brand_name']);
                $entityManager->flush();
                echo json_encode(['status' => 'success', 'message' => 'Brand updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Brand not found']);
            }
        }
        /**
         * Update Stock
         * 
         * Updates or creates a stock record for a product in a store.
         * 
         * @param string $_REQUEST['action'] Must be 'updateStock'
         * @param array $putData JSON data containing stock information:
         *              - int product_id
         *              - int store_id
         *              - int quantity
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateStock') {
            // Récupérer les données JSON du corps de la requête PUT
            $putData = json_decode(file_get_contents("php://input"), true);

            if (isset($putData['product_id']) && isset($putData['store_id']) && isset($putData['quantity'])) {
                $product = $entityManager->getRepository(Products::class)->find($putData['product_id']);
                $store = $entityManager->getRepository(Stores::class)->find($putData['store_id']);

                if ($product && $store) {
                    // Vérifier si un stock existe déjà pour ce produit dans ce magasin
                    $stock = $entityManager->getRepository(Stocks::class)->findOneBy([
                        'product' => $product,
                        'store' => $store
                    ]);

                    if ($stock) {
                        // Mettre à jour le stock existant
                        $stock->setQuantityId($putData['quantity']);
                    } else {
                        // Créer un nouveau stock
                        $stock = new Stocks();
                        $stock->setProductId($product);
                        $stock->setStoreId($store);
                        $stock->setQuantityId($putData['quantity']);
                        $entityManager->persist($stock);
                    }

                    $entityManager->flush();
                    echo json_encode(['status' => 'success', 'message' => 'Stock updated successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Product or store not found']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Missing required data']);
            }
        }
        /**
         * Update Employee
         * 
         * Updates an existing employee record in the database.
         * 
         * @param string $_REQUEST['action'] Must be 'updateEmployee'
         * @param array $data JSON data containing updated employee information:
         *              - int id
         *              - int store_id
         *              - string employee_name
         *              - string employee_email
         *              - string employee_password
         *              - string employee_role
         * @return json JSON-encoded status response
         */
        else if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_REQUEST['action'] == 'updateEmployee') {
            $data = json_decode(file_get_contents("php://input"), true);

            if (isset($data['id'])) {
                $employee = $entityManager->getRepository(Employees::class)->find($data['id']);
                if ($employee) {
                    $employee->setStoreId($entityManager->getRepository(Stores::class)->find($data['store_id']));
                    $employee->setEmployeeName($data['employee_name']);
                    $employee->setEmployeeEmail($data['employee_email']);
                    $employee->setEmployeePassword($data['employee_password']);
                    $employee->setEmployeeRole($data['employee_role']);
                    $entityManager->flush();

                    echo json_encode(['status' => 'success', 'message' => 'Employé mis à jour']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Employé non trouvé']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID manquant']);
            }
        }
        break;
    case 'DELETE':
        /**
         * Delete Store
         * 
         * Deletes a store record from the database.
         * 
         * @param string $_REQUEST['action'] Must be 'deleteStore'
         * @param int $_REQUEST['id'] The store ID to delete
         * @return json JSON-encoded status response
         */
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'deleteStore' && isset($_REQUEST['id'])) {
            $store = $entityManager->getRepository(Stores::class)->find($_REQUEST['id']);
            if ($store) {
                $entityManager->remove($store);
                $entityManager->flush();
                echo json_encode(['status' => 'success', 'message' => 'Store deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Store not found']);
            }
        }
        /**
         * Delete Product
         * 
         * Deletes a product record from the database.
         * 
         * @param string $_REQUEST['action'] Must be 'deleteProduct'
         * @param int $_REQUEST['id'] The product ID to delete
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'deleteProduct' && isset($_REQUEST['id'])) {
            $product = $entityManager->getRepository(Products::class)->find($_REQUEST['id']);
            if ($product) {
                $entityManager->remove($product);
                $entityManager->flush();
                echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Product not found']);
            }
        }
        /**
         * Delete Category
         * 
         * Deletes a category record from the database if it is not associated with any products.
         * 
         * @param string $_REQUEST['action'] Must be 'deleteCategory'
         * @param int $_REQUEST['id'] The category ID to delete
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'deleteCategory' && isset($_REQUEST['id'])) {
            $category = $entityManager->getRepository(Categories::class)->find($_REQUEST['id']);
            if ($category) {
                // Check if there are products linked to this category
                $products = $entityManager->getRepository(Products::class)->findBy(['category' => $category]);
                if (count($products) > 0) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Cannot delete category: it is still used by some products'
                    ]);
                } else {
                    $entityManager->remove($category);
                    $entityManager->flush();
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Category deleted successfully'
                    ]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Category not found']);
            }
        }
        /**
         * Delete Brand
         * 
         * Deletes a brand record from the database if it is not associated with any products.
         * 
         * @param string $_REQUEST['action'] Must be 'deleteBrand'
         * @param int $_REQUEST['id'] The brand ID to delete
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'deleteBrand' && isset($_REQUEST['id'])) {
            $brand = $entityManager->getRepository(Brands::class)->find($_REQUEST['id']);
            if ($brand) {
                // Check if there are products linked to this brand
                $products = $entityManager->getRepository(Products::class)->findBy(['brand' => $brand]);
                if (count($products) > 0) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Cannot delete brand: it is still used by some products'
                    ]);
                } else {
                    $entityManager->remove($brand);
                    $entityManager->flush();
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Brand deleted successfully'
                    ]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Brand not found']);
            }
        }
        /**
         * Delete Stock
         * 
         * Deletes a stock record from the database.
         * 
         * @param string $_REQUEST['action'] Must be 'deleteStock'
         * @param int $_REQUEST['id'] The stock ID to delete
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'deleteStock' && isset($_REQUEST['id'])) {
            $stock = $entityManager->getRepository(Stocks::class)->find($_REQUEST['id']);
            if ($stock) {
                $entityManager->remove($stock);
                $entityManager->flush();
                echo json_encode(['status' => 'success', 'message' => 'Stock deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Stock not found']);
            }
        }
        /**
         * Delete Employee
         * 
         * Deletes an employee record from the database.
         * 
         * @param string $_REQUEST['action'] Must be 'deleteEmployee'
         * @param int $_REQUEST['id'] The employee ID to delete
         * @return json JSON-encoded status response
         */
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'deleteEmployee' && isset($_REQUEST['id'])) {
            $employee = $entityManager->getRepository(Employees::class)->find($_REQUEST['id']);
            if ($employee) {
                $entityManager->remove($employee);
                $entityManager->flush();
                echo json_encode(['status' => 'success', 'message' => 'Employee deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Employee not found']);
            }
        }
        break;
}