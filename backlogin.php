<?php

include("./conexao.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $tel = $_POST["tel"];
    $senha = $_POST["senha"];

    if (empty($nome) || empty($email) || empty($senha) || empty($tel)) {
        echo "Por favor, preencha todos os campos.";
        exit;
    }
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO tbl_usuarios (`nome`, `email`, `celular`, `senha`) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $tel, $senhaHash);
    if ($stmt->execute() === TRUE) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário: " . $stmt->error;
    }
    $stmt->close();
}
