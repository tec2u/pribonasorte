<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("America/New_York");

$lines = file('../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) {
        continue;
    } else if (strpos(trim($line), 'DB') === 0) {
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

$con = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE']);


if (!isset($_SERVER["HTTP_HOST"])) {
    parse_str($argv[1], $_GET);
    parse_str($argv[1], $_POST);
}

if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

$_POST = json_decode(file_get_contents('php://input'), true);

$name = isset($_POST['name']) ? $_POST['name'] : '';
$login = isset($_POST['login']) ? $_POST['login'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
$cell = isset($_POST['cell']) ? $_POST['cell'] : '';
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$created_at = isset($_POST['created_at']) ? $_POST['created_at'] : '';
$updated_at = isset($_POST['updated_at']) ? $_POST['updated_at'] : '';
$recommendation_user_id = isset($_POST['recommendation_user_id']) ? $_POST['recommendation_user_id'] : '';
$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
$address1 = isset($_POST['address1']) ? $_POST['address1'] : '';
$address2 = isset($_POST['address2']) ? $_POST['address2'] : '';
$country = isset($_POST['country']) ? $_POST['country'] : '';
$financial_password = isset($_POST['financial_password']) ? $_POST['financial_password'] : '';
$city = isset($_POST['city']) ? $_POST['city'] : '';
$postcode = isset($_POST['postcode']) ? $_POST['postcode'] : '';
$state = isset($_POST['state']) ? $_POST['state'] : '';
$birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';
$special_comission= isset($_POST['special_comission']) ? $_POST['special_comission'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($login) || !preg_match('/^[a-z0-9]+$/', $login)) {
    $response = array(
        "status" => "error",
        "message" => "The login must contain only alphanumeric characters and lowercase letters"
    );
    echo json_encode($response);
    exit;
}

$sql = "SELECT * FROM users WHERE login = '$login'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
    $response = array(
        "status" => "error",
        "message" => "The login provided is already in use."
    );
    echo json_encode($response);
    exit;
}

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
    $response = array(
        "status" => "error",
        "message" => "The email entered is already in use."
    );
    echo json_encode($response);
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

if (empty($name) || empty($login) || empty($email) || empty($password) || empty($cell) || empty($country) || empty($city) || empty($last_name) || empty($recommendation_user_id)) {
    $response = array(
        "status" => "error",
        "message" => "Please fill in all required fields."
    );
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response = array(
        "status" => "error",
        "message" => "Please provide a valid email address."
    );
} else if (strlen($password) < 8) {
    $response = array(
        "status" => "error",
        "message" => "The password must be at least 8 characters long."
    );
} else if (!preg_match('/^\S*$/u', $password)) {
    $response = array(
        "status" => "error",
        "message" => "The password must only contain non-blank characters."
    );
} else if (!preg_match('/^[0-9+]+$/', $cell)) {
    $response = array(
        "status" => "error",
        "message" => "Please provide a valid phone number (numbers only)."
    );
} else if (!preg_match('/^[0-9+]+$/', $telephone)) {
    $response = array(
        "status" => "error",
        "message" => "Please provide a valid phone number (numbers only)."
    );
} else if (!filter_var($recommendation_user_id, FILTER_VALIDATE_INT)) {
    $response = array(
        "status" => "error",
        "message" => "The 'recommendation_user_id' field must only contain an integer value."
    );
} else {
    $sql = "INSERT INTO users (name, login, email, telephone, cell, gender, country, financial_password, password, activated, created_at, updated_at, recommendation_user_id, last_name, address1, address2, city, postcode, state, birthday, special_comission, special_comission_active)
    VALUES ('$name', '$login', '$email', '$telephone', '$cell', '$gender', '$country', '$password_hash', '$password_hash', '0', NOW(), NOW(), '$recommendation_user_id', '$last_name', '$address1', '$address2', '$city', '$postcode', '$state', '$birthday', '1.00', '0')";

    if (mysqli_query($con, $sql)) {
        $response = array(
            "status" => "success",
            "message" => "Registration completed successfully!"
        );
    } else {
        $response = array(
            "status" => "error",
            "message" => "Error when registering: " . mysqli_error($con)
        );
    }
}


echo json_encode($response);

mysqli_close($con);
