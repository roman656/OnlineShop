<?php

include_once 'Product.php';
include_once 'User.php';

class OnlineShop {
    private $name;
    private $owner;
    private $version;
    private $products;
    private $users;

    public function __construct($name, $owner, $version, $products, $users) {
        $this->name = $name;
        $this->owner = $owner;
        $this->version = '0.0.0.1';
        $this->products = $products;
        $this->users = $users;

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

    public function getUsers() {
        return $this->users;
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

    public function setUsers($newUsers) {
        $this->users = $newUsers;
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

    public function printMainPanel() {
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
        if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
            echo '
                        <p class = "UserInPanel">Вы вошли как:<br>'.($this->users[$_SESSION['email']])->getFirstName().'</p>
                        <form method = "POST">
                            <p><input type = "submit" name = "selfPage" value = "Личный кабинет"></p>
            ';
            if ($_SESSION['level'] == 0) {
                echo '
                            <p><input type = "submit" name = "adminHere" value = "Творить"></p>
                ';
            }
            echo '
                            <p><input type = "submit" name = "exit" value = "Выйти"></p>
                        </form>
            ';
        }
        else {
            echo '
                        <form method = "POST">
                            <p><input type = "submit" name = "registration" value = "Регистрация"></p>
                            <p><input type = "submit" name = "login" value = "Вход"></p>
                        </form>
            ';
        }
        echo '
                    </div>
                </div>
        ';
    }

    public function createProduct() {
        if($_SESSION['level'] != 0) {
            echo '
                <div class = "ControlButtons">
                    <form class = "ToMain" method = "POST">
                        <input type = "submit" name = "toMain" value = "На главную">
                    </form>
                </div>
            ';
            return;
        }
        echo '
                <div class = "ControlButtons">
                    <form class = "ToMain" method = "POST">
                        <input type = "submit" name = "toMain" value = "На главную">
                    </form>
                </div>
                <div class = "ProductInfo">
                    <form method = "POST">
                        <p>Название товара (обязательное поле)</p>
                        <input type = "text" name = "name" maxlength = "50" placeholder = "Название" required>
                        <p>Полная стоимость товара</p>
                        <input type = "number" name = "price" value = "0" size = "20" min = "0">
                        <p>Скидка на товар (%)</p>
                        <input type = "number" name = "discount" value = "0" size = "3" min = "0" max = "100">
                        <p>Количество товара</p>
                        <input type = "number" name = "amount"  value = "0" size = "10" min = "0">
                        <p>Путь к файлу изображения товара</p>
                        <input type = "text" name = "pathToImage" value = "" maxlength = "255">
                        <p>Описание товара</p>
                        <input type = "text" name = "description" value = "">
                        <input type = "reset" value = "Очистить форму">
                        <input type = "submit" name = "productCreated"  value = "Создать">
                    </form>
                </div>
        ';
    }

    public function updateProduct($product) {
        if($_SESSION['level'] != 0) {
            echo '
                <div class = "ControlButtons">
                    <form class = "ToMain" method = "POST">
                        <input type = "submit" name = "toMain" value = "На главную">
                    </form>
                </div>
            ';
            return;
        }
        echo '
                <div class = "ControlButtons">
                    <form class = "ToMain" method = "POST">
                        <input type = "submit" name = "toMain" value = "На главную">
                    </form>
                </div>
                <div class = "ProductInfo">
                    <form method = "POST">
                        <p>Название товара (обязательное поле)</p>
                        <input type = "text" name = "name" value = "'.$product->getName().'" maxlength = "50" placeholder = "Название" required>
                        <p>Полная стоимость товара</p>
                        <input type = "number" name = "price" value = "'.$product->getPrice().'" size = "20" min = "0">
                        <p>Скидка на товар (%)</p>
                        <input type = "number" name = "discount" value = "'.$product->getDiscount().'" size = "3" min = "0" max = "100">
                        <p>Количество товара</p>
                        <input type = "number" name = "amount"  value = "'.$product->getAmount().'" size = "10" min = "0">
                        <p>Путь к файлу изображения товара</p>
                        <input type = "text" name = "pathToImage" value = "'.$product->getPathToImage().'" maxlength = "255">
                        <p>Описание товара</p>
                        <input type = "text" name = "description" value = "'.$product->getDescription().'">
                        <input type = "hidden" name = "id" value = '.$product->getId().'>
                        <input type = "reset" value = "Очистить форму">
                        <input type = "submit" name = "productUpdated"  value = "Применить">
                    </form>
                </div>
        ';
    }

    public function showAdminPanel($dsn, $user, $password, $options) {
        if($_SESSION['level'] != 0) {
            echo '
                <div class = "ControlButtons">
                    <form class = "ToMain" method = "POST">
                        <input type = "submit" name = "toMain" value = "На главную">
                    </form>
                </div>
            ';
            return;
        }
        echo '
                <div class = "ControlButtons">
                    <form class = "ToMain" method = "POST">
                        <input type = "submit" name = "toMain" value = "На главную">
                    </form>
                </div>
        ';

        try {
            $dataBaseConn = new PDO($dsn, $user, $password, $options);
        }
        catch (PDOException $exception) {
            die('Подключение не удалось: '.$exception->getMessage());
        }

        $query = $dataBaseConn->query('SELECT * FROM products', PDO::FETCH_ASSOC);

        $currentProducts = array();

        while ($row = $query->fetch()) {
            $currentProducts[$row['id']] = new Product(
                $row['id'], 
                $row['name'], 
                $row['price'], 
                $row['discount'], 
                $row['amount'], 
                (isset($row['pathToImage']) ? $row['pathToImage'] : ""), 
                (isset($row['description']) ? $row['description'] : "")
            );
        }

        $isEmpty = true;

        echo '
            <form class = "ToMain" method = "POST">
                <input type = "submit" name = "createProduct" value = "Добавить товар">
            </form>
            <ul class = "ProductsList">
        ';

        foreach ($currentProducts as $key => $value) {
            $isEmpty = false;
            echo '
                <li>Id = '.$value->getId().'; Name = '.$value->getName().'; Price = '.number_format($value->getPrice(), 0, '', ' ').' &#8381;<br>Discount = '.$value->getDiscount().'; Amount = '.$value->getAmount().'; PathToImage = '.$value->getPathToImage().';<br>Description = '.$value->getDescription().'
                    <form method = "POST">
                        <input type = "hidden" name = "id" value = '.$value->getId().'>
                        <input type = "submit" name = "dropProduct" value = "Удалить">
                        <input type = "submit" name = "updateProduct" value = "Изменить">
                    </form>
                </li>
            ';
        }

        echo '
            </ul>
        ';

        if ($isEmpty) {
            echo '
                <div class = "EmptyCart">
                    <p>Товаров нет.</p>
                </div>
            ';
        }
    }

    public function showSelfPage() {
        echo '
                <div class = "SelfPage">
                    <form class = "ToMain" method = "POST">
                        <input type = "submit" name = "toMain" value = "На главную">
                    </form>
                    <p>Имя: '.($this->users[$_SESSION['email']])->getFirstName().'</p>
                    <p>Фамилия: '.($this->users[$_SESSION['email']])->getLastName().'</p>
                    <p>Email: '.($this->users[$_SESSION['email']])->getEmail().'</p>
                    <p>Телефон: '.($this->users[$_SESSION['email']])->getPhone().'</p>
                    <p>Дата рождения: '.str_replace('.', '-', ($this->users[$_SESSION['email']])->getBirthdate()).'</p>
                    <p>IP-адрес: '.($this->users[$_SESSION['email']])->getInternetProtocolAddress().'</p>
                    <p>Личный сайт: '.($this->users[$_SESSION['email']])->getWebsite().'</p>
                    <p>Статус: '.(($this->users[$_SESSION['email']])->getLevel() == 0 ? 'Администратор' : 'Расход').'</p>
        ';
        if (($this->users[$_SESSION['email']])->getWebsite() != "") {
            preg_match("/(?<scheme>http[s]?|ftp|file|ldap|telnet):\/\/(?<domain>[\w\.-]+)\.(?<zone>[\w]+)\/?(?<path>[^?$]+)?(?<query>[^#$]+)?[#]?(?<fragment>[^$]+)?/", ($this->users[$_SESSION['email']])->getWebsite(), $match);
            if (isset($match['scheme'])) {
                echo '
                    <p>Протокол: '.$match['scheme'].'</p>
                ';
            }
            if (isset($match['domain'])) {
                echo '
                    <p>Домен: '.$match['domain'].'</p>
                ';
            }
            if (isset($match['zone'])) {
                echo '
                    <p>Зона: '.$match['zone'].'</p>
                ';
            }
            if (isset($match['path'])) {
                echo '
                    <p>Текущая страница/скрипт: '.str_replace('/', '', strchr($match['path'], '/', false)).'</p>
                ';
            }
            if (isset($match['query'])) {
                echo '
                    <p>GET-запрос: '.str_replace('?', '', $match['query']).'</p>
                ';
            }
        }
        echo '
                    <p>Загрузка изображений:</p>
                    <form class="Upload" method="POST" enctype="multipart/form-data">
                        <input type="file" name="pictures[]" accept=".jpg, .jpeg, .png" multiple required>
                        <input type="submit" name = "uploadPressed" value="Загрузить">
                    </form>
                    <p>Изображения на сервере:</p>
                    <form class="ShowFiles" method="POST">
        ';
        $dir = "uploads/";
        $hasFilesToShow = false;
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while ($file = readdir($dh)) {
                    if (filetype($dir . $file) !== "dir") {
                        echo '
                        <p><input type="checkbox" name="file[]" value="'.$file.'">'.$file.'</p>
                        ';
                        $hasFilesToShow = true;
                    }
                }
                closedir($dh);
            }
        }
        if ($hasFilesToShow) {
            echo '
                        <input type="submit" name = "removeFilesPressed" value="Удалить">
            ';
        }

        $values = [4, 2, 3, 5, 1, 3, 4, 5, 6, 2, 3, 5];

        $colors = ["silver", "cyan", "purple",
                   "red", "olive","lime",
                   "yellow", "green", "blue",
                   "orange", "teal", "navy"];

        $months = ["Янв", "Фев", "Мар",
                   "Апр", "Май", "Июн",
                   "Июл", "Авг", "Сен",
                   "Окт", "Ноя", "Дек"];

        $width = 550;
        $height = 400;
        $column_width = 20;
        $default_indent = 20;
        $column_horizontal_indent = $default_indent + $column_width;
        $text_indent = 5;

        $point_values = [3,2,1,0];

        $j = 0;
        for ($i = count($point_values) - 1; $i >= 0; $i--) {
            $point_values[$j] = round(max($values) / (count($point_values) - 1) * $i, 2);
            $j++;
        }

        echo '
                    </form>
                    <p>Диаграмма:</p>
                    <svg width='.$width.'px height='.$height.'px>
                        <line x1="'.$default_indent.'" y1="'.($default_indent * 2).'" x2="'.$default_indent.'" y2="'.($height - $default_indent).'" stroke-width="1" stroke="rgb(0,0,0)"/>
        ';

        for ($i = 0; $i < count($point_values); $i++) {
            echo '
                        <text style="fill: white; font-size: 16px;" x="0" y="'.(($height - $default_indent) / count($point_values) * ($i + 1)).'">'.$point_values[$i].'</text>
                        <line x1="'.$default_indent.'" y1="'.(($height - $default_indent) / count($point_values) * ($i + 1)).'" x2="'.$width.'" y2="'.(($height - $default_indent) / count($point_values) * ($i + 1)).'" stroke-width="1" stroke="rgb(0,0,0)"/>
            ';
        }

        for ($i = 0; $i < count($values); $i++) {
            echo '
                        <text style="fill: white; font-size: 16px;" x="'.($text_indent + $column_horizontal_indent + $i * ($column_width + (($i > 0) ? $column_width : 0))).'" y="'.(-$text_indent + ($height - $default_indent) - ($height - $default_indent - (($height - $default_indent) / count($point_values))) / max($values) * $values[$i]).'">'.$values[$i].'</text>
                        <rect x="'.($column_horizontal_indent + $i * ($column_width + (($i > 0) ? $column_width : 0))).'" y="'.(($height - $default_indent) - ($height - $default_indent - (($height - $default_indent) / count($point_values))) / max($values) * $values[$i]).'" width="'.$column_width.'" height="'.(($height - $default_indent - (($height - $default_indent) / count($point_values))) / max($values) * $values[$i]).'" fill="'.$colors[$i].'"/>
            ';
        }
        
        echo '
                    </svg>
                        <div class="Legend">
        ';

        for ($i = 0; $i < count($months); $i++) {
            echo '
                            <p class="DiagramText" x="'.($column_horizontal_indent + $i * ($column_width + (($i > 0) ? $column_width : 0))).'" y="'.$height.'">'.$months[$i].'</p>
            ';
        }

        echo '
                    </div>
                </div>
        ';
    }

    public function showRegistration() {
        echo '
                <div class = "NewUser">
                    <form class = "ToMain" method = "POST">
                        <input type = "submit" name = "toMain" value = "На главную">
                    </form>
                    <p>Новый пользователь</p>
                    <form method = "POST">
                        <p class = "FormInfo">Обязательные поля:</p>
                        <p>Имя</p>
                        <input type = "text" pattern="[ЁА-Я]{1}[ёа-я]{2,}" maxlength = "50" size = "20" name = "firstName" placeholder = "Имя" required>
                        <p>Фамилия</p>
                        <input type = "text" pattern="[ЁА-Я]{1}[ёа-я]{2,}" maxlength = "50" size = "20" name = "lastName" placeholder = "Фамилия" required>
                        <p>Email</p>
                        <input type = "text" name = "email" pattern="^([A-Za-z0-9]+([-]{1}[A-Za-z0-9]+)*\.)*([A-Za-z0-9]+[-]{1}[A-Za-z0-9]+)*[A-Za-z0-9]+@[A-Za-z0-9]+(\.[A-Za-z0-9]+)*\.[a-z]{2,6}$" placeholder = "example@domain.zone.ru" required>
                        <p>Пароль</p>
                        <input type = "password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}" name = "password" placeholder = "Пароль" required>
                        <p class = "FormInfo">Не обязательные поля:</p>
                        <p>Личный сайт</p>
                        <input type = "text" name = "website" pattern="(http[s]?|ftp|file|ldap|telnet):\/\/([\w\.-]+)\.([\w]+)\/?([^?$]+)?([^#$]+)?[#]?([^$]+)?" placeholder = "URI">
                        <p>Телефон</p>
                        <input type = "tel" pattern = "(\+\d\s\(\d{3}\)\s\d{3}\s\d{4}){1}" name = "phone" placeholder = "+0 (000) 000 0000">
                        <p>Дата рождения</p>
                        <input type = "date" name = "birthdate" max="'.date('Y-m-d').'">
                        <p>IP адресс</p>
                        <input type = "text" pattern="^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$" name = "internetProtocolAddress" placeholder = "255.255.255.255">
                        <input type = "reset" value = "Очистить форму">
                        <input type = "submit" name = "createUser" value = "Создать аккаунт">
                    </form>
                </div>
        ';
    }

    public function showGoodsList() {
        $isEmpty = true;
        foreach($this->products as $key => $value) {
            if ($value->getAmount() > 0) {
                $isEmpty = false;
                echo '
                <div class = "Product">
                    <p class = "ProductName">'.$value->getName().'</p>
                ';
                if ($value->getPathToImage() != "") {
                    echo '
                    <img class = "ProductImage" src = "'.$value->getPathToImage().'" alt = "'.$value->getPathToImage().' (фото)">
                    ';
                }
                else {
                    echo '
                    <p class = "NoImage">Изображение товара отсутствует.</p>
                    ';
                }
                echo '
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
        
        if ($isEmpty) {
            echo '
                <div class = "EmptyCart">
                    <p>Здесь пусто!</p>
                </div>
            ';
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
                        <p class = "ProductName">'.$value->getName().'</p>';
                if ($value->getPathToImage() != "") {
                    echo '
                    <img class = "ProductImage" src = "'.$value->getPathToImage().'" alt = "'.$value->getPathToImage().' (фото)">
                    ';
                }
                else {
                    echo '
                    <p class = "NoImage">Изображение товара отсутствует.</p>
                    ';
                }
                echo '
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
                                    <li><a href="http://db1.mati.su/">Сайт Ильи</a></li>
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
                    <form class = "ToMain" method = "POST">
                        <input type = "submit" name = "toMain" value = "На главную">
                    </form>
                    <p>Вход</p>
                    <form method = "POST">
                        <p class = "Username">Email пользователя</p>
                        <input type = "text" name = "email" pattern="^([A-Za-z0-9]+([-]{1}[A-Za-z0-9]+)*\.)*([A-Za-z0-9]+[-]{1}[A-Za-z0-9]+)*[A-Za-z0-9]+@[A-Za-z0-9]+(\.[A-Za-z0-9]+)*\.[a-z]{2,6}$" placeholder = "example@domain.zone.ru" required>
                        <p class = "Password">Пароль</p>
                        <input type = "password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}" name = "password" placeholder = "Пароль" required>
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

?>