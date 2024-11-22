<?php
class Product {
    public $image;
    public $name;
    public $price;
    public $type;
    public $quantity;
    public $date;

    public function __construct($image, $name, $price, $type, $quantity, $date) {
        $this->image = $image;
        $this->name = $name;
        $this->price = $price;
        $this->setType($type);
        $this->quantity = $quantity;
        $this->date = $date;
    }

    private function setType($type) {
        $validTypes = ['preimera', 'segunda', 'tresera', 'kwadra', 'punla'];
        if (in_array($type, $validTypes)) {
            $this->type = $type;
        } else {
            throw new Exception("Invalid product type");
        }
    }
}
?>