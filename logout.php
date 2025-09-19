<?php
// filepath: c:\wamp64\www\login\logout.php
session_start();
session_unset(); // Remove todas as variáveis de sessão
session_destroy(); // Destroi a sessão
header("Location: index.php"); // Redireciona para a tela de login
exit;