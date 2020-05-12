<?php
    class Product {
        private $name;
        private $price;
        private $amount;
        private $pathToImage;
        private $description;

        public function __construct($name, $price, $amount, $pathToImage, $description) {
            $this->name = $name;
            $this->price = (($price >= 0) ? $price : 0);
            $this->amount = (($amount >= 0) ? $amount : 0);
            $this->pathToImage = $pathToImage;
            $this->description = $description;
        }

        public function getName() {
            return $this->name;
        }

        public function getPrice() {
            return $this->price;
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

        public function setName($newName) {
            $this->name = $newName;
        }

        public function setPrice($newPrice) {
            $this->price = (($newPrice >= 0) ? $newPrice : 0);
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

    class OnlineShop {
        private $name;
        private $owner;
        private $version;
        private $products;

        public function __construct($name, $owner, $version, $products) {
            $this->name = $name;
            $this->owner = $owner;
            $this->version = '0.0.0.1';
            $this->products = $products;

            if (preg_match("/[^\d]{0}\d+\.{1}\d+\.{1}\d+\.{1}\d+[^\d]{0}/", $version, $result)) {
                $this->version = $result[0];
            }
        }

        public function getName() {
            return $this->name;
        }

        public function getOwner() {
            return $this->owner;
        }

        public function getVersion() {
            return $this->version;
        }

        public function getProducts() {
            return $this->products;
        }

        public function setName($newName) {
            $this->name = $newName;
        }

        public function setOwner($newOwner) {
            $this->owner = $newOwner;
        }

        public function setVersion($newVersion) {
            if (preg_match("/[^\d]{0}\d+\.{1}\d+\.{1}\d+\.{1}\d+[^\d]{0}/", $newVersion, $result)) {
                $this->version = $result[0];
            }
        }

        public function setProducts($newProducts) {
            $this->products = $newProducts;
        }

        public function printHead() {
            echo '
            <!DOCTYPE html>
            <html lang = "ru">
                <head>
                    <meta charset = "utf-8">
                    <title>'.$this->name.'</title>
                    <link rel="stylesheet" href="reset.css">
                    <link rel="stylesheet" href="style.css">
                    <link rel="icon" href="icon.ico">
                </head>
                <body>
            ';
            
        }

        public function printHeader() {
            echo '
                    <header>
                        <div class = "OwnerAndVersion">
                            <p>Владелец магазина: '.$this->owner.'</p>
                            <p>Версия магазина: '.$this->version.'</p>
                        </div>
                        <div class = "ShopName">
                            <p>'.$this->name.'</p>
                        </div>
                    </header>
            ';
        }

        public function printMainPanel($username) {
            echo '
                    <div class = "MainPanel">
                        <div class = "CartAndGoods">
                            <form method = "POST">
                                <p><input type = "submit" name = "showGoodsList" value = "Список товаров"></p>
                                <p><input type = "submit" name = "showShoppingCart" value = "Моя корзина"></p>
                            </form>
                        </div>
                        <div class = "ShopInfo">
                            <p>Добро пожаловать в наш магазин!</p>
                        </div>
                        <div class = "Registration">
            ';
            if ($username == "") {
                echo '
                            <form method = "POST">
                                <p><input type = "submit" name = "registration" value = "Регистрация"></p>
                                <p><input type = "submit" name = "login" value = "Вход"></p>
                            </form>
                ';
            }
            else {
                echo '
                            <p class = "UserInPanel">Вы вошли как:<br>'.$username.'</p>
                            <form method = "POST">
                                <p><input type = "submit" name = "exit" value = "Выйти"></p>
                            </form>
                ';
            }
            echo '
                        </div>
                    </div>
            ';
        }

        public function showGoodsList() {
            foreach($this->products as $key => $value) {
                if ($value->getAmount() > 0) {
                    echo '
                    <div class = "Product">
                        <p class = "ProductName">'.$value->getName().'</p>
                        <img class = "ProductImage" src = "'.$value->getPathToImage().'" alt = "'.$value->getPathToImage().' (фото)">
                        <p class = "ProductPrice">Цена: '.number_format($value->getPrice(), 0, '', ' ').' &#8381;</p>
                        <form method = "POST">
                            <input type = "hidden" name = "id" value = '.$key.'>
                            <p class = "ProductAmount">Количество: <input type = "number" size = "3" name = "amount" min = "1" max = "'.$value->getAmount().'" value = "1"></p>
                            <p class = "AddToCartButton"><input type = "submit" name = "addToCart" value = "Добавить в корзину"></p>
                        </form>
                    </div>
                    ';
                }
            }
        }

        public function showShoppingCart() {
            $isEmpty = true;
            $totalPrice = 0;
            $currentAmount = 0;

            foreach($this->products as $key => $value) {
                if (isset($_COOKIE[$key]) && ($value->getAmount() > 0)) { 
                    $isEmpty = false;
                    if ($_COOKIE[$key] > $value->getAmount()) {
                        $currentAmount = $value->getAmount();
                    }
                    else {
                        $currentAmount = $_COOKIE[$key];
                    }
                    $totalPrice += $value->getPrice() * $currentAmount;
                    echo '
                        <div class = "ProductInCart">
                            <p class = "ProductName">'.$value->getName().'</p>
                            <img class = "ProductImage" src = "'.$value->getPathToImage().'" alt = "'.$value->getPathToImage().' (фото)">
                            <p class = "ProductAmountInCart">Количество: '.number_format(($_COOKIE[$key]), 0, '', ' ').'</p>
                            <p class = "ProductPriceInCart">Стоимость покупки:<br> '.number_format(($value->getPrice() * $currentAmount), 0, '', ' ').' &#8381;</p>
                            <form method = "POST">
                                <input type = "hidden" name = "id" value = '.$key.'>
                                <p class = "UpdateProductAmountInCartButton"><input type = "submit" name = "updateProductAmountInCart" value = "Изменить количество:"><input type = "number" size = "3" name = "amount" min = "1" max = "'.$value->getAmount().'" value = '.$currentAmount.'></p>
                                <input class = "BuyButton" type = "submit" name = "buy" value = "Купить">
                                <input class = "RemoveProductFromCartButton" type = "submit" name = "removeProductFromCart" value = "Убрать из корзины">
                            </form>
                        </div>
                    ';
                }
            }

            if ($isEmpty) {
                echo '
                    <div class = "EmptyCart">
                        <p>Здесь пусто!</p>
                    </div>
                ';
            }
            else {
                echo '
                    <div class = "TotalPriceInCart">
                        <p>Итого: '.number_format($totalPrice, 0, '', ' ').' &#8381;</p>
                        <form method = "POST">
                            <input type = "submit" name = "buyAllInCart" value = "Купить все">
                            <input type = "submit" name = "cleanCart" value = "Очистить корзину">
                        </form>
                    </div>
                ';
            }
        }

        public function printFooter() {
            echo '
                    <footer>
                        <p class="Copyright">&copy; Р. Д. Страхов, 
            ';

            $currentYear = date('Y');
            if ($currentYear == '2020') { 
                echo $currentYear; 
            } 
            else {
                echo '2020 &#8212; '.$currentYear;
            }

            echo '
                        </p>
                        <table class="Contacts"> 
                            <caption>Друзья сайта</caption>
                            <tr>
                                <td>
                                    <ul>
                                        <li><a href="http://momimu.mati.su/">Сайт Степана</a></li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li><a href="http://faizovr.mati.su/">Сайт Рустама</a></li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li><a href="http://madeira.mati.su/">Сайт Димы</a></li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li><a href="http://brainout.mati.su/">Сайт Санька</a></li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li><a href="http://urban-kharty.mati.su/">Сайт Артема</a></li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </footer>
                </body>
            </html>
            ';
        }

        public function showLogin() {
            echo '
                    <div class = "Login">
                        <p>Вход</p>
                        <form method = "POST">
                            <p class = "Username">Имя пользователя</p>
                            <input type = "text" maxlength = "20" size = "20" name = "username" placeholder = "Логин">
                            <p class = "Password">Пароль</p>
                            <input type = "password" name = "password" placeholder = "Пароль">
                            <input type = "submit" name = "toMain" value = "На главную">
                            <input type = "reset" value = "Очистить форму">
                            <input type = "submit" name = "loginButtonPressed" value = "Войти">
                        </form>
                    </div>
            ';
        }

        public function addProductToCart($productId, $amount, $time) {
            if (($this->products[$productId])->getAmount() > 0) {
                if (isset($_COOKIE[$productId])) {
                    $previousAmount = $_COOKIE[$productId];
                    $newAmount = $previousAmount + $amount;
                    if ($newAmount > ($this->products[$productId])->getAmount()) {
                        $newAmount = ($this->products[$productId])->getAmount();
                    }
                    setcookie($productId, $newAmount, time() + $time);
                }
                else {
                    setcookie($productId, $amount, time() + $time);
                }
            }
        }

        public function removeProductFromCart($productId, $mustDeleteAll, $amount, $newTime) {
            if (isset($_COOKIE[$productId])) {
                if (($mustDeleteAll) || ($amount >= $_COOKIE[$productId]) || ($amount >= ($this->products[$productId])->getAmount()) || (($this->products[$productId])->getAmount() < 1)) {
                    setcookie($productId, 0, time() - 10);
                }
                else {
                    setcookie($productId, $_COOKIE[$productId] - $amount, time() + $newTime);
                }
            }
        }

        public function updateProductInCart($productId, $newAmount, $time) {
            if (isset($_COOKIE[$productId])) {
                if ($_COOKIE[$productId] >= $newAmount) {
                    $this->removeProductFromCart($productId, false, $_COOKIE[$productId] - $newAmount, $time);
                }
                else {
                    $this->addProductToCart($productId, $newAmount - $_COOKIE[$productId], $time);
                }
            }
            else {
                $this->addProductToCart($productId, $newAmount, $time);
            }
        }

        public function cleanCart() {
            foreach($this->products as $key => $value) {
                $this->removeProductFromCart($key, true, -1, -1);
            }
        }
    }

    $products = array(
        1 => new Product('Основной танк Т-90СМ', 120000000, 220, 'Images/Т-90СМ.jpg', 'Экспортный вариант танка Т-90М. В отличие от Т-90М на танк устанавливается пушка 2А46М-5.'),
        2 => new Product('Основной танк Т-14', 250000000, 120, 'Images/Т-14.jpg', 'Перспективный российский основной боевой танк с необитаемой башней на базе универсальной гусеничной платформы «Армата».'),
        3 => new Product('Объект 279', 1400000000, 1, 'Images/Obj_279.jpg', 'Единственный экземпляр уникального танка Объект 279 сегодня экспонируется в Бронетанковом музее в Кубинке.')
    );

    /* Пароль: 1234567890Ab */
    $users = array(1 => array('login' => 'admin', 'password' => 'c0d7659e35f9e7b793c4257d565e7390'));

    $magazin4ik = new OnlineShop('Magazin4ik', 'Admin', '0.0.0.2', $products);

    session_start();

    $magazin4ik->printHead();
    $magazin4ik->printHeader();

    if (isset($_POST['login'])) {
        $magazin4ik->showLogin();
    }
    elseif (isset($_POST['exit'])){
        unset($_SESSION['username']);
        session_destroy();
        $magazin4ik->printMainPanel("");
        $magazin4ik->showGoodsList();
    }
    elseif (isset($_POST['loginButtonPressed'])) {
        $hasCorrectLogin = false;
        foreach($users as $key => $value) {
            if ($value['login'] == $_POST['username']) {
                $hasCorrectLogin = true;
                if ($value['password'] == md5($_POST['password'])) {
                    $_SESSION['username'] = htmlspecialchars($_POST['username']);
                    $magazin4ik->printMainPanel($_SESSION['username']);
                    $magazin4ik->showGoodsList();
                }
                else {
                    echo '
                        <p class = "loginError">Неверный пароль!</p>
                    ';
                    $magazin4ik->showLogin();
                    break;
                }    
            }
        }
        if (!isset($_SESSION['username']) && !$hasCorrectLogin) {
            echo '
                <p class = "loginError">Неверное имя пользователя!</p>
            ';
            $magazin4ik->showLogin();
        }
    }
    else {
        if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
            $magazin4ik->printMainPanel($_SESSION['username']);
        }
        else {
            $magazin4ik->printMainPanel("");
        }

        if (isset($_POST['addToCart'])) {
            $magazin4ik->addProductToCart($_POST['id'], $_POST['amount'], 3600);
            $magazin4ik->showGoodsList();
        }
        elseif (isset($_POST['removeProductFromCart'])) {
            $magazin4ik->removeProductFromCart($_POST['id'], true, -1, -1);
            setcookie("removeRedirect", 1, time() + 60);
            header('Location: '.$_SERVER["HTTP_REFERER"]);
        }
        elseif (isset($_POST['updateProductAmountInCart'])) {
            $magazin4ik->updateProductInCart($_POST['id'], $_POST['amount'], 3600);
            setcookie("removeRedirect", 1, time() + 60);
            header('Location: '.$_SERVER["HTTP_REFERER"]);
        }
        elseif (isset($_POST['showShoppingCart'])) {
            $magazin4ik->showShoppingCart();
        }
        elseif (isset($_POST['showGoodsList'])) {
            $magazin4ik->showGoodsList();
        }
        elseif (isset($_POST['buyAllInCart'])) {
            $magazin4ik->cleanCart();
            setcookie("buyRedirect", 1, time() + 60);
            header('Location: '.$_SERVER["HTTP_REFERER"]);
        }
        elseif (isset($_POST['buy'])) {
            $magazin4ik->removeProductFromCart($_POST['id'], true, -1, -1);
            setcookie("buyRedirect", 1, time() + 60);
            header('Location: '.$_SERVER["HTTP_REFERER"]);
        }
        elseif (isset($_POST['cleanCart'])) {
            $magazin4ik->cleanCart();
            setcookie("removeRedirect", 1, time() + 60);
            header('Location: '.$_SERVER["HTTP_REFERER"]);
        }
        else {
            if (isset($_COOKIE["removeRedirect"])) {
                setcookie("removeRedirect", 0, time() - 60);
                $magazin4ik->showShoppingCart();
            }
            elseif (isset($_COOKIE["buyRedirect"])) {
                setcookie("buyRedirect", 0, time() - 60);
                echo '
                        <p class = "buyThank">Спасибо!</p>
                ';
            }
            else {
                $magazin4ik->showGoodsList();
            }
        }
    }

    $magazin4ik->printFooter();
?> 