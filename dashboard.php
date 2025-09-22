<?php
include("./conexao.php");
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("location: index.php");
    exit;
}

$nome = $_SESSION["usuario_nome"];
$celular = $_SESSION["usuario_celular"];




// Somar receitas do usuário
$usuario_id = $_SESSION["usuario_id"];
$sql_total_receitas = "SELECT SUM(valor) AS total_receitas FROM tbl_transacao WHERE usuario_id = '$usuario_id' AND tipo = 'receita'";
$result_total = mysqli_query($conn, $sql_total_receitas);
$row_total = mysqli_fetch_assoc($result_total);
$total_receitas = $row_total['total_receitas'] ?? 0; // se não houver receitas, retorna 0


// Somar Despesas do usuário
$sql_total_despesa = "SELECT SUM(valor) AS total_despesa FROM tbl_transacao WHERE usuario_id = '$usuario_id' AND tipo = 'despesa'";
$result_total = mysqli_query($conn, $sql_total_despesa);
$row_total = mysqli_fetch_assoc($result_total);
$total_despesa = $row_total['total_despesa'] ?? 0;


$saldo = $total_receitas - $total_despesa;







?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Menu</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
    <!-- JS (jQuery para o OwlCarousel) -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</head>

<body id="body-pd">
    <!-- Header -->
    <header class="header" id="header">
        <div class="header_toggle">
            <i class='bx bx-menu' id="header-toggle"></i>
        </div>
        <img src="./assets/capa.png" alt="" style="width: 10%; margin: 10px auto">
    </header>

    <!-- Sidebar -->
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav_logo">
                    <i class='bx bx-layer nav_logo-icon'></i>
                    <span class="nav_logo-name">Planeja <strong>+</strong></span>
                </a>
                <div class="nav_list">
                    <a href="#" class="nav_link active" id="menu-dashboard">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name">Dashboard</span>
                    </a>
                    <a href="#" class="nav_link" id="menu-receitas">
                        <i class='bx bx-dollar nav_icon'></i>
                        <span class="nav_name">Receitas</span>
                    </a>
                    <a href="#" class="nav_link" id="menu-despesas">
                        <i class='bx bx-credit-card nav_icon'></i>
                        <span class="nav_name">Despesas</span>
                    </a>
                    <a href="#" class="nav_link" id="menu-relatorios">
                        <i class='bx bx-bar-chart-alt-2 nav_icon'></i>
                        <span class="nav_name">Relatórios</span>
                    </a>
                    <a href="#" class="nav_link" id="menu-painel">
                        <i class='bx bx-user nav_icon'></i>
                        <span class="nav_name">Painel</span>
                    </a>
                </div>
            </div>
            <div class="dropdown nav_link" style="display: flex; align-items: center;">
                <a href="#" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
                    <img src="https://res.cloudinary.com/dpjpz26qm/image/upload/v1674890312/codepen/avatar/man_1_cpqkhl.png" class="img-fluid" alt="" style="width: 35px; border-radius: 50%;">
                    <span class="nav_name ms-2"><?php echo htmlspecialchars($nome); ?></span>
                    <i class='bx bx-chevron-down ms-2 dropdown-arrow'></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class='bx bx-user nav_icon'></i> Painel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="logout.php">
                            <i class='bx bx-exit nav_icon'></i> Sair
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <main id="main-content">
        <div id="section-dashboard" class="section-content section-chart">
            <h2 class="title-dash">Olá <?= htmlspecialchars($nome); ?>, esse é seu Painel Geral!</h2>
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-xs-12">
                    <div class="card bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="desc">
                                    <h3 class="mb-0">R$ <?= number_format($total_receitas, 2, ',', '.') ?></h3>
                                    <span>Total de Faturamento</span>
                                </div>
                                <i class="bi bi-cash-coin" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-xs-12">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="desc">
                                    <h3 class="mb-0">R$ <?= number_format($total_despesa, 2, ',', '.') ?></h3>
                                    <span>Total de Despesas</span>
                                </div>
                                <i class="bi bi-cart-dash" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-xs-12">
                    <div class="card bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="desc">
                                    <h3 class="mb-0">R$ <?= number_format($saldo, 2, ',', '.') ?></h3>
                                    <span>Está Sobrando</span>
                                </div>
                                <i class="bi bi-clipboard2-check-fill" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div id="section-receitas" class="section-content sec-receita" style="display:none;">
            <div class="title-receita">
                <h2>Faturamentos</h2>
                <p>Aqui você fará a adição de seus lucros.</p>
            </div>
            <form action="./backtransacao.php" method="POST">
                <input type="hidden" name="tipo" value="receita">
                <div class="row g-3">

                    <div class="col-md-3 form-floating">
                        <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="valor" required>
                        <label for="floatingInput">Valor do Faturamento</label>
                    </div>
                    <div class="col-md-3 form-floating">
                        <select class="form-select" id="categoriaReceita" aria-label="Categoria" name="categoria">
                            <option value="" disabled selected>Selecione uma opção</option>
                            <option value="salario">Salário</option>
                            <option value="bonificacao">Bonificação</option>
                            <option value="diaria">Diárias</option>
                            <option value="extras">Extras</option>
                            <option value="outros">Outros...</option>
                        </select>
                        <label for="categoriaReceita">Tipo de Faturamento</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="descricao">
                        <label for="floatingTextarea2">Descrição</label>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Adicionar Receita">
                </div>
            </form>
            <table class="table table-success table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID da Receita</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $usuario_id = $_SESSION["usuario_id"];
                    $query = "SELECT * FROM tbl_transacao WHERE usuario_id = '$usuario_id' AND tipo = 'receita'";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($row["id"])  ?></th>
                                <td><?= htmlspecialchars($row["valor"]) ?></td>
                                <td><?= htmlspecialchars($row["categoria_nome"]) ?></td>
                                <td><?= htmlspecialchars($row["descricao"]) ?></td>
                            </tr>
                        <?php

                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4">Nenhuma receita cadastrada.</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div id="section-despesas" class="section-content sec-despesa" style="display:none;">
            <div class="title-despesa">
                <h2>Despesas</h2>
                <p>Aqui você fará a adição de suas despesas.</p>
            </div>
            <form action="./backtransacao.php" method="POST">
                <input type="hidden" name="tipo" value="despesa">
                <div class="row g-3">

                    <div class="col-md-3 form-floating">
                        <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="valor">
                        <label for="floatingInput">Valor da Despesa</label>
                    </div>
                    <div class="col-md-3 form-floating">
                        <select class="form-select" id="categoriaDepesa" aria-label="Categoria" name="categoria">
                            <option selected>Selecione uma Opção</option>
                            <option value="viagem">Viagem</option>
                            <option value="compras_fisicas">Compras Fisicas</option>
                            <option value="compras_online">Compras Online</option>
                            <option value="saidas">Saidas</option>
                            <option value="outros">Outros...</option>
                        </select>
                        <label for="categoriaDepesa">Tipo de Despesa</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="descricao">
                        <label for="floatingTextarea2">Descrição</label>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Adicionar Depesa">
                </div>
            </form>
            <table class="table table-danger table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID da Despesa</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $usuario_id = $_SESSION["usuario_id"];
                    $query = "SELECT * FROM tbl_transacao WHERE usuario_id = '$usuario_id' AND tipo = 'despesa'";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($row["id"])  ?></th>
                                <td><?= htmlspecialchars($row["valor"]) ?></td>
                                <td><?= htmlspecialchars($row["categoria_nome"]) ?></td>
                                <td><?= htmlspecialchars($row["descricao"]) ?></td>
                            </tr>
                        <?php

                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4">Nenhuma despesa cadastrada.</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        </div>
        <div id="section-relatorios" class="section-content" style="display:none;">
            <h2>Relatórios</h2>
            <p>Gráficos e relatórios financeiros...</p>
        </div>
        <div id="section-painel" class="section-content" style="display:none;">
            <h2>Painel do Usuário</h2>
            <p>Informações da sua conta...</p>
        </div>

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
        <script src="dashboard.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js"></script>

        <script>
            function showSection(section) {
                document.querySelectorAll('.section-content').forEach(div => div.style.display = 'none');
                document.getElementById('section-' + section).style.display = 'block';
            }

            document.getElementById('menu-dashboard').addEventListener('click', function(e) {
                e.preventDefault();
                showSection('dashboard');
            });
            document.getElementById('menu-receitas').addEventListener('click', function(e) {
                e.preventDefault();
                showSection('receitas');
            });
            document.getElementById('menu-despesas').addEventListener('click', function(e) {
                e.preventDefault();
                showSection('despesas');
            });
            document.getElementById('menu-relatorios').addEventListener('click', function(e) {
                e.preventDefault();
                showSection('relatorios');
            });
            document.getElementById('menu-painel').addEventListener('click', function(e) {
                e.preventDefault();
                showSection('painel');
            });
            // Troca a seta quando o dropdown abre/fecha
            document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
                toggle.addEventListener('show.bs.dropdown', function() {
                    this.querySelector('.dropdown-arrow').classList.remove('bx-chevron-down');
                    this.querySelector('.dropdown-arrow').classList.add('bx-chevron-up');
                });
                toggle.addEventListener('hide.bs.dropdown', function() {
                    this.querySelector('.dropdown-arrow').classList.remove('bx-chevron-up');
                    this.querySelector('.dropdown-arrow').classList.add('bx-chevron-down');
                });
            });
            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 10,
                dots: false,
                nav: true,
                autoplay: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    1000: {
                        items: 1
                    }
                }
            });
        </script>
</body>

</html>