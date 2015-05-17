<?php

class Catalog_Mobile_Category_Product_ViewController extends Application_Controller_Mobile_Default {

    public function findAction() {

        if($value_id = $this->getRequest()->getParam('value_id') AND $product_id = $this->getRequest()->getParam('product_id')) {

            $product = new Catalog_Model_Product();
            $product->find($product_id);

            $data = array();
            if($product->getData("type") != "menu") {

                $format = array();
                if($product->getData("type") == "format") {
                    foreach($product->getType()->getOptions() as $option) {
                        $format[] = array(
                            "id" => $option->getId(),
                            "title" => $option->getTitle(),
                            "price" => $option->getFormattedPrice()
                        );
                    }
                }

                $data = array(
                    "name" => $product->getName(),
                    "conditions" => $product->getConditions(),
                    "description" => $product->getDescription(),
                    "price" => $product->getPrice() > 0 ? $product->getFormattedPrice() : null,
                    "picture" => $product->getPictureUrl(),
                    "formats" => $format
                );

            }

            $this->_sendHtml($data);
        }
    }

}