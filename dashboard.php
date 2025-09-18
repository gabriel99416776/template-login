<?php
include("./conexao.php");
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("location: index.php");
    exit;
}

$nome = $_SESSION["usuario_nome"];
$celular = $_SESSION["usuario_celular"];
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
    <!-- JS (jQuery para o OwlCarousel) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body id="body-pd">
    <!-- Header -->
    <header class="header" id="header">
        <div class="header_toggle">
            <i class='bx bx-menu' id="header-toggle"></i>
        </div>
        <div class="btn-group" style="width: 60px">
            <button type="button" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://res.cloudinary.com/dpjpz26qm/image/upload/v1674890312/codepen/avatar/man_1_cpqkhl.png" class="img-fluid" alt="" style="width: 35px;">
                <p><?php echo htmlspecialchars($nome); ?></p>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class='bx bx-user-circle'></i> Account</a></li>
                <li><a class="dropdown-item" href="#"><i class='bx bxs-widget'></i> Settings</a></li>
                <li><a class="dropdown-item" href="#"><i class='bx bx-exit'></i> Logout</a></li>
            </ul>
        </div>
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
                    <a href="#" class="nav_link active">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name">Dashboard</span>
                    </a>
                    <a href="#" class="nav_link">
                        <i class='bx bx-dollar nav_icon'></i>
                        <span class="nav_name">Receitas</span>
                    </a>
                    <a href="#" class="nav_link">
                        <i class='bx bx-credit-card nav_icon'></i>
                        <span class="nav_name">Despesas</span>
                    </a>
                    <a href="#" class="nav_link">
                        <i class='bx bx-bar-chart-alt-2 nav_icon'></i>
                        <span class="nav_name">Relatórios</span>
                    </a>
                    <a href="#" class="nav_link">
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

    <!-- Main Content -->
    <main>
        <!-- Conteúdo principal aqui -->
    </main>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
    <script src="dashboard.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js"></script>
    <script>
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