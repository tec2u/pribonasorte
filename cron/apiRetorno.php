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

$con = mysqli_connect($_ENV['DB_HOST'],$_ENV['DB_USERNAME'],$_ENV['DB_PASSWORD'],$_ENV['DB_DATABASE']);


if (!isset($_SERVER["HTTP_HOST"])) {
    parse_str($argv[1], $_GET);
    parse_str($argv[1], $_POST);
}

// Verifica se a conexão foi estabelecida com sucesso
if ($con->connect_error) {
    die("Falha na conexão com o banco de dados: " . $con->connect_error);
}

// Define o cabeçalho da resposta como JSON
header("Content-Type: application/json");

// Seleciona as informações da tabela do banco de dados
$sql = "SELECT * FROM packages";
$result = $con->query($sql);

// Verifica se há resultados e cria um array com as informações
if ($result->num_rows > 0) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data); // Retorna as informações em formato JSON
} else {
    echo "Nenhum resultado encontrado na tabela.";
}

// Fecha a conexão com o banco de dados
$con->close();
?>