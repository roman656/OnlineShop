<?php

class User {
    private $firstName;
    private $lastName;
    private $passwordHash;
    private $email;
    private $phone;
    private $birthdate;
    private $website;
    private $internetProtocolAddress;
    private $level;

    public function __construct($firstName, $lastName, $passwordHash, $email,
            $phone, $birthdate, $website, $internetProtocolAddress, $level) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->passwordHash = $passwordHash;
        $this->email = $email;
        $this->phone = $phone;
        $this->birthdate = $birthdate;
        $this->website = $website;
        $this->internetProtocolAddress = $internetProtocolAddress;
        $this->level = $level;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getPasswordHash() {
        return $this->passwordHash;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getBirthdate() {
        return $this->birthdate;
    }

    public function getWebsite() {
        return $this->website;
    }

    public function getInternetProtocolAddress() {
        return $this->internetProtocolAddress;
    }

    public function getLevel() {
        return $this->level;
    }

    public function setFirstName($newFirstName) {
        $this->firstName = $newFirstName;
    }

    public function setLastName($newLastName) {
        $this->lastName = $newLastName;
    }

    public function setPasswordHash($newPasswordHash) {
        $this->passwordHash = $newPasswordHash;
    }

    public function setEmail($newEmail) {
        $this->email = $newEmail;
    }

    public function setPhone($newPhone) {
        $this->phone = $newPhone;
    }

    public function setBirthdate($newBirthdate) {
        $this->birthdate = $newBirthdate;
    }

    public function setWebsite($newWebsite) {
        $this->website = $newWebsite;
    }

    public function setInternetProtocolAddress($newInternetProtocolAddress) {
        $this->internetProtocolAddress = $newInternetProtocolAddress;
    }

    public function setLevel($newLevel) {
        $this->level = $newLevel;
    }
}

?>