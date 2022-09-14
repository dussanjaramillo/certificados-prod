<?php
session_start();
?>

<style>
    @media screen and (max-width: 600px) {
        table {
            width: 100%;
        }


        thead {
            display: none;
        }

        tr:nth-of-type(2n) {
            background-color: inherit;
        }

        tr td:first-child {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 1.3em;
        }

        tbody td {
            display: block;
            text-align: center;
        }

        tbody td:before {
            content: attr(data-th);
            display: block;
            text-align: center;
        }

        input {

            width: 100%;
            margin-bottom: 1px;
            padding: 15px;
            background-color: #ECF4F4;
            border-radius: 2px;
            border: none;

        }

    }


    .login-sec h2 {
        margin-bottom: 28px;
        font-weight: 800;
        font-size: 28px;
        color: #ff6719;
    }

    .login-sec h2:after {
        content: " ";
        width: 100px;
        height: 5px;
        background: #FEB58A;
        display: block;
        margin-top: 10px;
        border-radius: 3px;
        margin-left: auto;
        margin-right: auto
    }

    .body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        text-align: center;
    }

    a:visited {

        outline: none;
        color: #ff6719;;

    }
</style>
<!--<script src='https://kit.fontawesome.com/a076d05399.js'></script>-->

<script>
    function beforeSubmit() {
        $('#login').submit();
    }

    $(function () {
        $('#defaultUnchecked').on('change', function () {
            beforeSubmit();
        });
    });


    function beforeSubmit() {
        $('#login').submit();
    }

    $(function () {
        $('#ingresar').on('change', function () {
            beforeSubmit();
        });
    });
</script>


</head>
<p><br>
    <!--<div class="container-fluid">-->
<div id="contents " style=" min-width: 100%;">
    <div class="center-form-large " style=" max-width:100% ">
        <div id="contentdiv" class="contcustom">
            <?php if (isset($message) && !empty($message)) { ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php } ?>
            <form method="post" action="<?= base_url('index.php/auth/login') ?>">
                <table border="0">
                    <tr HEIGHT="60">
                        <td colspan="2">
                            <div class="p-1 col-sm-12 bg-light text-dark ">
                                <div class="col-md-12 login-sec">
                                    <h2 class="text-center">CERTIFICADOS DE APORTES Y FIC</h2>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr HEIGHT="60">
                        <td><label class="body" for="fname"> Nombre de Usuario:</label></td>
                        <td>
                            <label class="sr-only" for="inlineFormInputGroup">Username</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">@</div>
                                </div>
                                <input type="text" class="form-control" name="identity" id="identity"
                                       placeholder="Usuario"
                                       required>
                            </div>
                        </td>
                    </tr>
                    <tr HEIGHT="60">
                        <td width="130px"><label for="fname" class="body">Contraseña: &nbsp; &nbsp;</label></td>
                        <td>
                            <label class="sr-only" for="inlineFormInputGroup">Password</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-key prefix" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <input name="password" id="input-pwd" type="password" class="form-control validate"
                                       required>
                                <div class="input-group-text"><span toggle="#input-pwd"
                                                                    class="fa fa-fw field-icon toggle-password fa-eye-slash"
                                                                    size="40"></span></div>
                            </div>
                        </td>
                    </tr>
                    <tr HEIGHT="60">
                        <td colspan="2">
                            <?php
                            if (isset($_SESSION['msg'])) {
                                echo $_SESSION['msg'];
                                unset($_SESSION['msg']);
                            }
                            ?>
                            <div class="box">
                                <img src="<?= base_url('captcha.php') ?>" alt="Código captcha" style="width:43%;">
                                <br><br>
                                <center><input type="text" size="29" style="width: 196px" class="form-control"
                                               name="captcha" id="captcha" required></center>
                            </div>
                        </td>
                    </tr>
                    <tr HEIGHT="60">
                        <td colspan="2">
                            <center>
                                <button style="width: 100px; color: white; background-color: #F27323;padding: 3px 10px;
     border: 1px solid #ababab; " type="submit" name="submit" value="Ingresar">
                                    Ingresar
                                </button>
                            </center>
                        </td>
                    </tr>
                </table>
            </form>

            <center><a class="body" href="<?= base_url('index.php/iniciocertificados/registrar') ?> "> Registro </a>
            </center>

            <center><a class="body" href="<?= base_url('index.php/iniciocertificados/recuperarClave') ?> "> Recuperar
                    Clave </a></center>
            <br>

            <div>
                <a href="<?= base_url('index.php/iniciocertificados/mirar_certificados') ?>"
                   title="CONSULTAR CÓDIGO CERTIFICADO"
                   style="font-size:14px;font-family:Roboto Condensed ;color: black;"><i class="fa fa-search fa-lg"></i>CONSULTAR
                    CÓDIGO CERTIFICADO</a>
            </div>

        </div>


        <script>
            $('.toggle-password').on('click', function () {
                $(this).toggleClass('fa-eye fa-eye-slash');
                let input = $($(this).attr('toggle'));
                if (input.attr('type') == 'password') {
                    input.attr('type', 'text');
                } else {
                    input.attr('type', 'password');
                }
            });
        </script>