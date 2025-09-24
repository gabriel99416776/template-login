<?php

include("./conexao.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nome"], $_POST["email"], $_POST["tel"], $_POST["senha"], $_POST["profissao"])) {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $tel = $_POST["tel"];
    $profissao = $_POST["profissao"];
    $senha = $_POST["senha"];

    
    if (empty($nome) || empty($email) || empty($senha) || empty($tel)) {
        header("location: index.php?erro=3");
        exit;
    }


    $stmt = $conn->prepare("SELECT id FROM tbl_user WHERE email = ? OR celular = ?");
    $stmt->bind_param("ss", $email, $tel);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0) {
        header("location: index.php?erro=4");
        $stmt->close();
        exit;
    }


    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO tbl_user (`nome`, `email`, `celular`, `profissao`, `senha`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nome, $email, $tel, $senhaHash);
    if ($stmt->execute() === TRUE) {
        header("location: index.php?sucess=1");
    } else {
        echo "Erro ao cadastrar usuário: " . $stmt->error;
    }
    $stmt->close();
    exit;
}
