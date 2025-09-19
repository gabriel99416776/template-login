<?php
include("./conexao.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"], $_POST["senha"])) {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    if (empty($email) || empty($senha)) {
        header("location: index.php?erro=2");
        exit;
    }

    // BUSCA O USUÁRIO PELO EMAIL
    $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        if (password_verify($senha, $usuario["senha"])) {
            session_start();
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nome"] = $usuario["nome"];
            $_SESSION["usuario_celular"] = $usuario["celular"];
            header("location: dashboard.php");
            exit;
        }
    }
    $stmt->close();

    header("location: index.php?erro=1");
    exit;
}
