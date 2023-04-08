<?php

namespace Controller;

use Model\Product;
use Model\ProductType;
use storage\ProductStorage;
use Storage\ProductTypeStorage;

class ProductController extends BaseController
{
    private $viewDir = DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR;

    public function index()
    {
        $products = ProductStorage::findAll();

        echo $this->view->render('base', [
            'content' => $this->view->render($this->viewDir . 'index', [
                'products' => $products
            ])
        ]);
    }

    public function availableProducts()
    {
        $products = ProductStorage::findOneByProductTypeName($_GET['id']);
        echo $this->view->render('base', [
            'content' => $this->view->render($this->viewDir . '/available_products', [
                'products' => $products
            ])
        ]);
    }

    public function create()
    {
        $productTypes = ProductTypeStorage::findAll();
        echo $this->view->render('base', [
            'content' => $this->view->render($this->viewDir . 'create', [
                'productTypes' => $productTypes
            ])
        ]);
    }

    public function createSubmit()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['submit_product'])) {

                $productType = ProductTypeStorage::findOneByName($_POST['product_type']);

                $productTypeObject = new ProductType();
                $productTypeObject->setId($productType->id);
                $productTypeObject->setName($productType->name);

                $product = new Product();
                $product->setName($_POST['name']);
                $product->setDescription($_POST['description']);
                $product->setPrice($_POST['price']);
                $product->setType($productTypeObject);

                ProductStorage::insert($product);
                header('Location: /product/');
            }
        }
    }

    public function edit()
    {
        $product = ProductStorage::findOneById($_GET['id']);
        $productTypes = ProductTypeStorage::findAll();
        echo $this->view->render('base', [
            'content' => $this->view->render($this->viewDir . '/edit', [
                'product' => $product,
                'productTypes' => $productTypes
            ])
        ]);
    }

    public function editSubmit()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['submit_edit_product'])) {
                $productType = ProductTypeStorage::findOneByName($_POST['product_type']);

                $productTypeObject = new ProductType();
                $productTypeObject->setId($productType->id);
                $productTypeObject->setName($productType->name);

                $product = new Product();
                $product->setId($_POST['id']);
                $product->setName($_POST['name']);
                $product->setDescription($_POST['description']);
                $product->setPrice($_POST['price']);
                $product->setType($productTypeObject);

                ProductStorage::update($product);
                header('Location: /product/');
            }
        }
    }

    public function delete()
    {
        ProductStorage::delete($_GET['id']);
        header('Location: /product/');
    }
}   