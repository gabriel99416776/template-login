<?php
include("./conexao.php");
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("location: index.php");
    exit;
}
$usuario_id = $_SESSION["usuario_id"];
$query = "SELECT * FROM tbl_user WHERE id = '$usuario_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $nome = $row["nome"];
    $email = $row["email"];
    $celular = $row["celular"];
    // Expressão regular para formatar
    $celular_formatado = preg_replace('/(\d{2})(\d{1})(\d{4})(\d{4})/', '($1) $2 $3-$4', $celular);
    $foto_64 = $row["foto_64"];
} else {
    // Usuário não encontrado, redirecionar para a página de login
    header("location: index.php");
    exit;
}







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

$saldo = (float)$total_receitas - (float)$total_despesa;







$sql_grafico = "SELECT tipo, SUM(valor) AS total FROM tbl_transacao WHERE usuario_id = '$usuario_id' GROUP BY tipo";
$result_grafico = mysqli_query($conn, $sql_grafico);

$data_grafico = array();
$data_grafico[] = array('Tipo', 'Total', array('role' => 'style')); // adiciona coluna style

while ($row_grafico = mysqli_fetch_assoc($result_grafico)) {
    $tipo = $row_grafico['tipo'];
    $total = (float)$row_grafico['total'];

    // Define a cor baseada no tipo
    $cor = ($tipo === 'receita') ? '#198754' : '#DC3545';

    $data_grafico[] = array($tipo, $total, $cor);
}

$json_data_grafico = json_encode($data_grafico);


$consultaSQL = "SELECT 
    tipo,
    SUM(valor) AS total,
    DATE(data) AS data_transacao
FROM 
    tbl_transacao
WHERE 
    usuario_id = $usuario_id AND
    data BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()
GROUP BY 
    tipo, DATE(data)
ORDER BY
    DATE(data), tipo;";

$resultadoConsulta = $conn->query($consultaSQL);

$dadosGrafico = array();
$dadosGrafico[] = array('Data', 'Receitas', 'Despesas');

$valoresReceitas = array();
$valoresDespesas = array();

while ($linha = $resultadoConsulta->fetch_assoc()) {
    $dataTransacao = $linha['data_transacao'];
    $tipoTransacao = $linha['tipo'];
    $totalTransacao = (float)$linha['total'];

    if ($tipoTransacao == 'receita') {
        $valoresReceitas[$dataTransacao] = $totalTransacao;
    } elseif ($tipoTransacao == 'despesa') {
        $valoresDespesas[$dataTransacao] = $totalTransacao;
    }
}

$dataInicial = new DateTime('-6 days');
$dataFinal = new DateTime('today');

for ($data = $dataInicial; $data <= $dataFinal; $data->modify('+1 day')) {
    $dataString = $data->format('Y-m-d');
    $receitaDia = isset($valoresReceitas[$dataString]) ? $valoresReceitas[$dataString] : 0;
    $despesaDia = isset($valoresDespesas[$dataString]) ? $valoresDespesas[$dataString] : 0;

    $dadosGrafico[] = array($dataString, $receitaDia, $despesaDia);
}

$jsonDataGrafico = json_encode($dadosGrafico);

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

                    <?php
                    if (!empty($foto_64)) {
                        $foto_64 = "oi";
                    } else {
                        $foto_64 = "./assets/foto_user.png";
                    }
                    ?>
                    <img src="<?= $foto_64 ?>" alt="Avatar" class="img-fluid my-5" style="width: 35px; border-radius: 50%;"> 


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
            <hr class="linha-principal">
            <?php
            if ($saldo < 0) {
                $cor = 'bg-danger';
                $icone = 'bi bi-exclamation-lg';
            } elseif ($saldo > 0) {
                $cor = 'bg-success';
                $icone = 'bi bi-check-lg';
            }
            ?>
            <!-- Cards de resumo -->
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
                    <div class="card  <?= $cor ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="desc">
                                    <h3 class="mb-0">R$ <?= number_format($saldo, 2, ',', '.') ?></h3>
                                    <?php if ($saldo < 0): ?>
                                        <span>Está Devendo !!!</span>
                                    <?php else: ?>
                                        <span>Está Sobrando</span>
                                    <?php endif; ?>
                                </div>
                                <i class="<?= $icone ?>" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row mt-4">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body" style="height: 400px;">
                            <div id="piechart" style="width: 100%; height: 100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body" style="height: 400px;">
                            <div id="columnchart_values" style="width: 100%; height: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body" style="height: 400px;">
                        <div id="chart_div" style="width: 100%; height: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="section-receitas" class="section-content sec-receita" style="display:none;">
            <div class="title-receita">
                <h2>Faturamentos</h2>
                <p>Aqui você fará a adição de seus lucros.</p>
                <hr class="linha">
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
                <hr class="linha-red">
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
        <div id="section-relatorios" class="section-content tabela-relatorio" style="display:none;">
            <h2>Relatórios</h2>
            <a href="./gerar_pdf.php" class="gerar-pdf">Gerar PDF do Relatorio</a>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID Transação</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $usuario_id = $_SESSION["usuario_id"];
                    $query = "SELECT * FROM tbl_transacao WHERE usuario_id = '$usuario_id' ORDER BY tipo = 'receita' DESC";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {


                        while ($row = mysqli_fetch_assoc($result)) {
                            $classe = ($row["tipo"] === "receita") ? "text-success" : "text-danger";
                    ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($row["id"])  ?></th>
                                <td>R$ <?= htmlspecialchars($row["valor"]) ?></td>
                                <td class="<?= $classe ?>"><?= htmlspecialchars($row["tipo"]) ?></td>
                                <td><?= htmlspecialchars($row["descricao"]) ?></td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4">Nenhuma transação cadastrada.</td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
            <div class="col-xl-4 col-lg-6 col-xs-12">
                <?php
                if ($saldo < 0) {
                    $cor = 'bg-danger';
                    $icone = 'bi bi-exclamation-lg';
                } elseif ($saldo > 0) {
                    $cor = 'bg-success';
                    $icone = 'bi bi-check-lg';
                }
                ?>
                <div class="card card-relatorio <?= $cor ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="desc">
                                <h3 class="mb-0">R$ <?= number_format($saldo, 2, ',', '.') ?></h3>
                                <?php if ($saldo < 0): ?>
                                    <span>Está Devendo !!!</span>
                                <?php else: ?>
                                    <span>Está Sobrando</span>
                                <?php endif; ?>
                            </div>
                            <i class="<?= $icone ?>" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="section-painel" class="section-content" style="display:none;">
            <h2>Painel do Usuário</h2>



            <section style="background-color: #f4f5f7;">
                <div class="container py-5 h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col col-lg-9 mb-4 mb-lg-0">
                            <div class="card mb-3" style="border-radius: .5rem;">
                                <div class="row g-0">
                                    <div class="col-md-4 gradient-custom text-center text-white" style="background-color: #031530;"
                                        style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                        <?php
                                        if (!empty($foto_64)) {
                                            $foto_64 = "oi";
                                        } else {
                                            $foto_64 = "./assets/foto_user.png";
                                        }
                                        ?>
                                        <img src="<?= $foto_64 ?>" alt="Avatar" class="img-fluid my-5" style="width: 150px;" />
                                        <h5><?= htmlspecialchars($nome) ?></h5>
                                        <p>Web Designer</p>
                                        <i class="far fa-edit mb-5"></i>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body p-4">
                                            <h6>Informações</h6>
                                            <hr class="mt-0 mb-4">
                                            <div class="row pt-1">
                                                <div class="col-6 mb-3">
                                                    <h6>Email</h6>
                                                    <p class="text-muted"><?= htmlspecialchars($email) ?></p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>Celular</h6>
                                                    <p class="text-muted"><?= htmlspecialchars($celular_formatado) ?></p>
                                                </div>
                                            </div>
                                            <h6>Projects</h6>
                                            <hr class="mt-0 mb-4">
                                            <div class="row pt-1">
                                                <div class="col-6 mb-3">
                                                    <h6>Recent</h6>
                                                    <p class="text-muted">Lorem ipsum</p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>Most Viewed</h6>
                                                    <p class="text-muted">Dolor sit amet</p>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <a href="#!"><i class="fab fa-facebook-f fa-lg me-3"></i></a>
                                                <a href="#!"><i class="fab fa-twitter fa-lg me-3"></i></a>
                                                <a href="#!"><i class="fab fa-instagram fa-lg"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
        <script src="dashboard.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js"></script>

        <script>
            function drawColumnChart() {
                var data = google.visualization.arrayToDataTable(<?= $json_data_grafico ?>);
                var options = {
                    width: '100%',
                    height: '100%',
                    legend: {
                        position: 'none'
                    },
                    vAxis: {
                        title: 'Valor'
                    },
                    hAxis: {
                        title: 'Tipo'
                    }
                };
                var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_values'));
                chart.draw(data, options);
            }

            function drawPieChart() {
                var data = google.visualization.arrayToDataTable(<?= $json_data_grafico ?>);
                var options = {
                    width: '100%',
                    height: '100%',
                    is3D: true,
                    pieSliceText: 'value',
                    colors: ['#198754', '#DC3545']
                };
                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
            }

            function drawAreaChart() {
                var data = google.visualization.arrayToDataTable(<?= $jsonDataGrafico ?>);
                var options = {
                    width: '100%',
                    height: '100%',
                    series: {
                        0: {
                            color: '#198754'
                        },
                        1: {
                            color: '#DC3545'
                        }
                    },
                    hAxis: {
                        title: 'Dias'
                    },
                    vAxis: {
                        minValue: 0
                    }
                };
                var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            }

            function redrawAll() {
                drawColumnChart();
                drawPieChart();
                drawAreaChart();
            }

            google.charts.load('current', {
                packages: ['corechart']
            });
            google.charts.setOnLoadCallback(redrawAll);

            // redesenha quando redimensiona a tela
            window.addEventListener('resize', redrawAll);

            // redesenha se o menu lateral muda
            document.getElementById('header-toggle').addEventListener('click', function() {
                setTimeout(redrawAll, 300); // espera a animação do menu
            });

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