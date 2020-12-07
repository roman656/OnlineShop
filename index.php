<?php

/* Пароль для admin: 1234567890Ab */

include_once 'OnlineShop.php';

$host = "localhost";
$dataBaseName = "karelia_online_shop";
$charset = "utf8";
$user = "karelia";
$password = "12345Ab";

$dsn = "mysql:host=$host;dbname=$dataBaseName;charset=$charset;";

$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC); 

try {
    $dataBaseConn = new PDO($dsn, $user, $password, $options);
}
catch (PDOException $exception) {
    die('Подключение не удалось: '.$exception->getMessage());
}

$products = array();
$users = array();

$query = $dataBaseConn->query('SELECT * FROM products', PDO::FETCH_ASSOC);

while ($row = $query->fetch()) {
    $products[$row['id']] = new Product(
        $row['id'], 
        $row['name'], 
        $row['price'], 
        $row['discount'], 
        $row['amount'], 
        (isset($row['pathToImage']) ? $row['pathToImage'] : ""), 
        (isset($row['description']) ? $row['description'] : "")
    );
}

$query = $dataBaseConn->query('SELECT * FROM users', PDO::FETCH_ASSOC);

while ($row = $query->fetch()) {
    $users[$row['email']] = new User(
        $row['firstName'],
        $row['lastName'], 
        $row['passwordHash'], 
        $row['email'],
        (isset($row['phone']) ? $row['phone'] : ""),
        (isset($row['birthdate']) ? $row['birthdate'] : ""), 
        (isset($row['website']) ? $row['website'] : ""), 
        (isset($row['internetProtocolAddress']) ? $row['internetProtocolAddress'] : ""), 
        $row['level']
    );
}

$magazin4ik = new OnlineShop('Magazin4ik', 'Администратор', '0.0.0.5', $products, $users);

session_start();

$magazin4ik->printHead();
$magazin4ik->printHeader();

if (isset($_POST['login'])) {
    $magazin4ik->showLogin();
}
elseif (isset($_POST['registration'])) {
    $magazin4ik->showRegistration();
}
elseif (isset($_POST['createProduct'])) {
    $magazin4ik->createProduct();
}
elseif (isset($_POST['productCreated'])) {
    $query = $dataBaseConn->prepare('INSERT INTO products (name, price, discount, amount, pathToImage, description) VALUES (?,?,?,?,?,?);');

    $name = htmlspecialchars($_POST['name']);
    $price = htmlspecialchars($_POST['price']);
    $discount = htmlspecialchars($_POST['discount']);
    $amount = htmlspecialchars($_POST['amount']);
    $pathToImage = htmlspecialchars($_POST['pathToImage']);
    $description = htmlspecialchars($_POST['description']);

    $query->bindParam(1, $name); 
    $query->bindParam(2, $price);
    $query->bindParam(3, $discount);
    $query->bindParam(4, $amount);
    $query->bindParam(5, $pathToImage); 
    $query->bindParam(6, $description);
    
    $query->execute();

    $magazin4ik->showAdminPanel($dsn, $user, $password, $options);
}
elseif (isset($_POST['productUpdated'])) {
    $query = $dataBaseConn->prepare('UPDATE products SET name=(?), price=(?), discount=(?), amount=(?), pathToImage=(?), description=(?) WHERE id='.$_POST['id'].';');

    $name = htmlspecialchars($_POST['name']);
    $price = htmlspecialchars($_POST['price']);
    $discount = htmlspecialchars($_POST['discount']);
    $amount = htmlspecialchars($_POST['amount']);
    $pathToImage = htmlspecialchars($_POST['pathToImage']);
    $description = htmlspecialchars($_POST['description']);

    $query->bindParam(1, $name); 
    $query->bindParam(2, $price);
    $query->bindParam(3, $discount);
    $query->bindParam(4, $amount);
    $query->bindParam(5, $pathToImage); 
    $query->bindParam(6, $description);
    
    $query->execute();

    $magazin4ik->showAdminPanel($dsn, $user, $password, $options);
}
elseif (isset($_POST['updateProduct'])) {
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

    $found = false;

    foreach($currentProducts as $key => $value) {
        if ($currentProducts[$key]->getId() == $_POST['id']) {
            $found = true;
        }
    }

    if ($found) {
        $magazin4ik->updateProduct($currentProducts[$_POST['id']]);
    }
    else {
        echo '
            <p class = "loginError">Данный продукт уже не существует!</p>
        ';
        $magazin4ik->showAdminPanel($dsn, $user, $password, $options);
    }
}
elseif (isset($_POST['dropProduct'])) {
    $query = $dataBaseConn->prepare('DELETE FROM products WHERE id='.$_POST['id'].';');
    $query->execute();
    $magazin4ik->showAdminPanel($dsn, $user, $password, $options);
}
elseif (isset($_POST['createUser'])) {
    $query = $dataBaseConn->query('SELECT * FROM users', PDO::FETCH_ASSOC);

    while ($row = $query->fetch()) {
        $users[$row['email']] = new User(
            $row['firstName'],
            $row['lastName'], 
            $row['passwordHash'], 
            $row['email'],
            (isset($row['phone']) ? $row['phone'] : ""),
            (isset($row['birthdate']) ? $row['birthdate'] : ""), 
            (isset($row['website']) ? $row['website'] : ""), 
            (isset($row['internetProtocolAddress']) ? $row['internetProtocolAddress'] : ""), 
            $row['level']
        );
    }

    if (isset($users[$_POST['email']])) {
        echo '
            <p class = "loginError">Данный Email уже занят!</p>
        ';
        $magazin4ik->setUsers($users);
        $magazin4ik->showRegistration();
    }
    else {
        $query = $dataBaseConn->prepare('INSERT INTO users (email, firstName, lastName, passwordHash, phone, birthdate, website, internetProtocolAddress, '.'level) VALUES (?,?,?,?,?,?,?,?,?);');
        
        $email = htmlspecialchars($_POST['email']);
        $firstName = htmlspecialchars($_POST['firstName']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $passwordHash = md5(htmlspecialchars($_POST['password']));
        $phone = htmlspecialchars((isset($_POST['phone']) ? $_POST['phone'] : ""));
        $birthdate = htmlspecialchars((isset($_POST['birthdate']) ? date('d.m.Y', strtotime($_POST['birthdate'])) : ""));
        $website = htmlspecialchars((isset($_POST['website']) ? $_POST['website'] : ""));
        $internetProtocolAddress = htmlspecialchars((isset($_POST['internetProtocolAddress']) ? $_POST['internetProtocolAddress'] : ""));
        $level = 10;

        $query->bindParam(1, $email); 
        $query->bindParam(2, $firstName); 
        $query->bindParam(3, $lastName); 
        $query->bindParam(4, $passwordHash);
        $query->bindParam(5, $phone); 
        $query->bindParam(6, $birthdate); 
        $query->bindParam(7, $website); 
        $query->bindParam(8, $internetProtocolAddress); 
        $query->bindParam(9, $level); 
        $query->execute();

        $users[$email] = new User(
            $firstName,
            $lastName, 
            $passwordHash, 
            $email,
            $phone,
            $birthdate, 
            $website, 
            $internetProtocolAddress, 
            $level
        );

        $_SESSION['email'] = $email;
        $_SESSION['level'] = 10;
        $magazin4ik->setUsers($users);
        $magazin4ik->printMainPanel();
        $magazin4ik->showGoodsList();
    }

}
elseif (isset($_POST['selfPage'])) {
    $magazin4ik->showSelfPage();
}
elseif (isset($_POST['exit'])) {
    unset($_SESSION['email']);
    session_destroy();
    $magazin4ik->printMainPanel();
    $magazin4ik->showGoodsList();
}
elseif (isset($_POST['removeFilesPressed'])) {
    $dir = "uploads/";
    if (isset($_POST["file"])) {
        foreach ($_POST["file"] as $value) {
            unlink($dir.$value);
            echo '
            <p class = "UploadMessage">Файл '.$value.' удален.</p>
            ';
        }
    }
    $magazin4ik->showSelfPage();
}
elseif (isset($_POST['uploadPressed'])) {
    $dir = "uploads/";
    foreach ($_FILES["pictures"]["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
            if (file_exists($dir . $_FILES["pictures"]["name"][$key])) {
                echo '
                <p class = "loginError">Файл '. $_FILES["pictures"]["name"][$key] . ' уже у нас есть! Загрузка не была произведена.</p>
                ';
            }
            elseif ($_FILES["pictures"]["size"][$key] > 500000000000) {
                echo '
                <p class = "loginError">Файл '. $_FILES["pictures"]["name"][$key] . ' слишком большой! Загрузка не была произведена.</p>
                ';
            }
            elseif (($_FILES["pictures"]["type"][$key] !== "image/jpeg") && ($_FILES["pictures"]["type"][$key] !== "image/png") && ($_FILES["pictures"]["type"][$key] !== "image/pjpeg")) {
                echo '
                <p class = "loginError">Файл '. $_FILES["pictures"]["name"][$key] . ' не является изображением типа png или jpeg! Загрузка не была произведена.</p>
                ';
            }
            else {
                if (move_uploaded_file($_FILES["pictures"]["tmp_name"][$key], $dir . $_FILES["pictures"]["name"][$key])) {
                    echo '
                    <p class = "UploadMessage">Файл '. $_FILES["pictures"]["name"][$key] . ' сохранен.</p>
                    ';
                } else {
                    echo '
                    <p class = "loginError">Загрузка файла '. $_FILES["pictures"]["name"][$key] . ' не была произведена по причине ошибки со стороны сервера.</p>
                    ';
                }
            }
        }
    }
    $magazin4ik->showSelfPage();
}
elseif (isset($_POST['adminHere'])) {
    $magazin4ik->showAdminPanel($dsn, $user, $password, $options);
}
elseif (isset($_POST['loginButtonPressed'])) {
    if (isset($users[$_POST['email']])) {
        if ($users[$_POST['email']]->getPasswordHash() == md5($_POST['password'])) {
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['level'] = $users[$_POST['email']]->getLevel();
            $magazin4ik->printMainPanel();
            $magazin4ik->showGoodsList();
        }
        else {
            echo '
                <p class = "loginError">Неверный пароль!</p>
            ';
            $magazin4ik->showLogin();
        }    
    }
    else {
        echo '
            <p class = "loginError">Неверный email!</p>
        ';
        $magazin4ik->showLogin();
    }
}
else {
    $magazin4ik->printMainPanel();

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

        foreach($_COOKIE as $key => $value) {
            if (!empty($currentProducts[$key])) {
                $query = $dataBaseConn->prepare('UPDATE products SET amount = '.($currentProducts[$key]->getAmount() - $value).' WHERE id = '.$key.';');
                $query->execute();
            }
        }

        $magazin4ik->cleanCart();
        setcookie("buyRedirect", 1, time() + 60);
        header('Location: '.$_SERVER["HTTP_REFERER"]);
    }
    elseif (isset($_POST['buy'])) {
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

        if (!empty($currentProducts[$_POST['id']])) {
            $query = $dataBaseConn->prepare('UPDATE products SET amount = '.($currentProducts[$_POST['id']]->getAmount() - $_COOKIE[$_POST['id']]).' WHERE id = '.$_POST['id'].';');
            $query->execute();
        }

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

$dataBaseConn = NULL;

$magazin4ik->printFooter();

?>