<?php

class Product {
    private $id;
    private $name;
    private $price;
    private $discount;
    private $amount;
    private $pathToImage;
    private $description;

    public function __construct($id, $name, $price, $discount, $amount, $pathToImage, $description) {
        $this->id = (($id > 0) ? $id : 1);
        $this->name = $name;
        $this->price = (($price >= 0) ? $price : 0);
        $this->discount = ((($discount >= 0) && ($discount <= 100)) ? $discount : 0);
        $this->amount = (($amount >= 0) ? $amount : 0);
        $this->pathToImage = $pathToImage;
        $this->description = $description;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function getDiscountPrice() {
        return ($this->price - ($this->price / 100 * $this->discount));
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getPathToImage() {
        return $this->pathToImage;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setId($newId) {
        $this->id = (($newId > 0) ? $newId : 1);
    }

    public function setName($newName) {
        $this->name = $newName;
    }

    public function setPrice($newPrice) {
        $this->price = (($newPrice >= 0) ? $newPrice : 0);
    }

    public function setDiscount($newDiscount) {
        $this->discount = ((($newDiscount >= 0) && ($newDiscount <= 100)) ? $newDiscount : 0);
    }

    public function setAmount($newAmount) {
        $this->amount = (($newAmount >= 0) ? $newAmount : 0);
    }

    public function setPathToImage($newPathToImage) {
        $this->pathToImage = $newPathToImage;
    }

    public function setDescription($newDescription) {
        $this->description = $newDescription;
    }
}

?>