<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>


    <div class="login-wrap">
        <div class="login-html">
            <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Login</label>
            <input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Register</label>
            <div class="login-form">
                <form action="./backlogin.php" method="POST">
                    <div class="sign-in-htm">
                        <div class="group">
                            <label for="user" class="label">Email</label>
                            <input id="user" type="text" class="input" name="email">
                        </div>
                        <div class="group">
                            <label for="pass" class="label">Senha</label>
                            <input id="pass" type="password" class="input" data-type="password" name="senha">
                        </div>
                        <?php if (isset($_GET['erro'])) { ?>
                            <div style="color: red; text-align: center; margin-bottom: 10px;">
                                <?php if ($_GET['erro'] == 1) { ?>
                                    <p>Email ou Senha Incorretos</p>
                                <?php } ?>
                                <?php if ($_GET['erro'] == 2) { ?>
                                    <p>Por favor, preencha todos os campos.</p>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div class="group">
                            <input id="check" type="checkbox" class="check" checked>
                            <label for="check"><span class="icon"></span> Lembrar de mim</label>
                        </div>
                        <div class="group">
                            <input type="submit" class="button" value="Entrar">
                        </div>
                        <div class="hr"></div>
                        <div class="foot-lnk">
                            <a href="#forgot">Esqueceu sua senha?</a>
                        </div>
                    </div>
                </form>
                <form action="./backcadastro.php" method="POST">

                    <div class="sign-up-htm">
                        <div class="group">
                            <label for="user" class="label">Nome</label>
                            <input id="user" type="text" class="input" name="nome">
                        </div>
                        <div class="group">
                            <label for="user" class="label">Email</label>
                            <input id="user" type="text" class="input" name="email">
                        </div>
                        <div class="group">
                            <label for="pass" class="label">Numero de Celular</label>
                            <input id="pass" type="text" class="input" name="tel">
                        </div>
                        <div class="group">
                            <label for="prof" class="label">Profissão</label>
                            <input id="prof" type="text" class="input" name="profissao">
                        </div>
                        <div class="group">
                            <label for="pass" class="label">Senha</label>
                            <input id="pass" type="password" class="input" data-type="password" name="senha">
                        </div>
                        <?php if (isset($_GET['sucess'])) { ?>
                            <?php if ($_GET['sucess'] == 1) { ?>

                                <div style="color: green; text-align: center; margin-bottom: 10px;">
                                    <p>Usuário cadastrado com sucesso, faça o Login!</p>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <?php if (isset($_GET['erro']) && $_GET['erro'] == 3) { ?>
                            <div style="color: red; text-align: center; margin-bottom: 10px;">
                                <p>Por favor, preencha todos os campos.</p>
                            </div>
                        <?php } ?>
                        <?php if (isset($_GET['erro']) && $_GET['erro'] == 4): ?>
                            <div style="color: red; text-align: center; margin-bottom: 10px;">
                                <p>Email ou celular já cadastrado!</p>
                            </div>
                        <?php endif; ?>
                        <div class="group">
                            <input type="submit" class="button" value="Cadastrar">
                        </div>
                        <div class="hr"></div>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php if ((isset($_GET['sucess']) && $_GET['sucess'] == 1) || (isset($_GET['erro']) && $_GET['erro'] == 3) || (isset($_GET['erro']) && $_GET['erro'] == 4)): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById('tab-2').checked = true;
            });
        </script>
    <?php endif; ?>
</body>

</html>