<?php //print_r($Fechas);?>


<div class="center-form-large " style=" width:60%; ">
    <center>
        <h1> Recuperar Clave </h1>
    </center>
    <br><br>

    <div class="vista">
        <p class="alert alert-danger" id="demo" hidden></p>
    </div>
    <div class="p-3 col-sm-12 bg-light text-dark ">

        <?php $attributes = array("autocomplete" => "off");
        echo form_open_multipart("iniciocertificados/verificarClave", $attributes); ?>


        <input type="hidden" readonly="readonly" id="prueba" name="prueba">
        <div class="form-row">

            <div class="col-md-12">

                <div class="col-md-6">
                    <label for="validationCustom04">Número de identificación </label>
                    <input value="<?php if ($idAportante != "") {
                        echo $idAportante;
                    } else {
                        echo "";
                    } ?>" onkeypress='return validaNumericos(event)' type="text" class="form-control" id="idAportante"
                           name="idAportante" placeholder="Digite Número de Identificación" required>
                    <div class="invalid-feedback">
                        Escriba Número De Identificación!
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">

            <div class="form-row">

                <div class="col-md-6 mb-3">
                    <label for="validationCustomUsername">Correo:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                        </div>
                        <input value="<?php if ($correo != "") {
                            echo $correo;
                        } else {
                            echo "";
                        } ?>" type="email" class="form-control" name="correo" id="correo"
                               placeholder="Digite Correo de la empresa"
                               aria-describedby="inputGroupPrepend" required>
                        <div class="invalid-feedback">
                            Ingrese Correo de la Empresa.
                        </div>
                    </div>
                </div>


            </div>

        </div>
        <?php if ($registro == "") { ?>


        <div class="col-md-12">
            <center>
                <div class="btn-group">

                    <div class="col-sm">
                        <button id="validar" class="btn btn-primary btn-sm"
                                type="submit">Recupera
                        </button>
                    </div>
                    <div class="col-sm">  <?php echo anchor(base_url().'index.php/auth/consulta', '<i class="icon-remove"></i> Cancelar', 'class="btn btn-warning"'); ?></div>
            </center>
        </div>
    </div>
    <?php } ?>

</div>
<?php echo form_close(); ?>

<?php
$attributes = array("id" => "form1");
echo form_open_multipart("iniciocertificados/verificacion", $attributes);
?>

<input type="hidden" readonly="readonly" id="validacion" name="validacion">
<input type="hidden" readonly="readonly" id="codigo" name="codigo">
<input type="hidden" readonly="readonly" id="Aportante" name="Aportante">
<input type="hidden" readonly="readonly" id="correos" name="correos">
<input type="hidden" readonly="readonly" id="fechas" name="fefhas">
</form>


<br><?php if ($mensaje != "") { ?>
    <div class="alert alert-danger"> <?php echo $mensaje; ?> </div> <br>
    <div class="col-md-12">
        <center>
            <div class="btn-group">
                <div class="col-sm">
                    <a href="<?= base_url('index.php/auth/consulta') ?>" class="btn btn-warning"><i
                                class="icon-remove"></i>
                        Cancelar</a>
                </div>
        </center>
    </div>
    </div>
<?php }
if ($registro != "" && $validacion != "") { ?>


    <div class="col-md-12">
        <p><strong>Validar :</strong></p>
        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="input-group col-md-6">
                <!-- <label for="validationCustom04">Código De Activación </label>-->
                <label for="validationCustomUsername">Código De Activación :</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Código</label>
                    </div>
                    <input value="<?php if ($validacion != "") {
                    } else {
                        echo "";
                    } ?>" onkeypress='return validaNumericos(event)' type="text" class="form-control"
                           id="idVerificacion" name="idVerificacion" placeholder="Digite Código Verificación" required>
                    <div class="invalid-feedback">
                        Ingrese Código De Verificación enviado al correo!
                    </div>
                </div>
                <div class="col-md-3">

                </div>

            </div>
        </div>
        <br>
        <div class="col-md-12">
            <center>
                <div class="btn-group">

                    <div class="col-sm" id="acepta" name="acepta">
                        <button
                                class="btn btn-primary btn-sm" onclick="aceptar()">Validar
                        </button>
                    </div>
                    <div class="col-sm">
                        <a href="<?= base_url('index.php/auth/consulta') ?>" class="btn btn-warning"><i class="icon-remove"></i> Cancelar</a>
                    </div>
            </center>
        </div>
    </div>

<?php } ?>
</div>


<script></script>
<script>
    var fecha = '<?php echo @$Fechas ?>';
    console.log(fecha);

    function aceptar() {
        var codigo = $("#idVerificacion").val();
        var validacion = '<?php echo @$validacion ?>';
        var idAportante = '<?php echo @$idAportante ?>';
        var correo = $("#correo").val();
        $('#codigo').val(codigo);
        $('#validacion').val(validacion);
        $('#Aportante').val(idAportante);
        $('#correos').val(correo);
        $('#fechas').val(fecha);
        // console.log("holllla")
        //console.log(idAportante);
        var url = "verificacion";
        if (codigo == '') {

        } else {
            //  let i = +$(this).data("count");
            //if (i < 3) {
            // ++i;
            //   $(this).data("count", i);
            // console.log(i);
            if (codigo == validacion) {
                var contante = '';
            } else {
                var contante = 1;
            }
            $.ajax({
                url: url,
                type: 'POST',

                data: {
                    contante: contante,
                    idAportante: idAportante,
                    correo: correo
                },
                error: function () {
                    alert('Something is wrong');
                },


                success: function (data) {
                    console.log("SI");
                    console.log(data);
                    value = data.replace(/["]/gi, '');
                    var numero = value;
                    var cont = 3 - numero;
                    if (codigo != validacion) {
                        alert("CODIGO ERRONEO LE QUEDAN  " + cont + "  INTENTOS");
                    }
                    if (numero == 3 && codigo != validacion) {
                        var contante = 0;
                        var limite = 3;
                        $('#codigo').val(limite);

                        alert("DEMASIADOS  INTENTOS SE CANCELA EL PROCESO");
                        $('#form1').submit();

                    }
                    if (codigo == validacion) {
                        ////////////////---------------
                        $('#form1').submit();
                        setTimeout(function () {
                            jQuery(".preload, .load").hide();
                        }, 2000);
                    }
                    // cont = cont - 1;
                }
            });

            // }
        }
    };
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    document.getElementById('demo').style.display = 'none';


    $('#validar').click(function () {
        var recepcion = 1;
        console.log(recepcion);
        $('#prueba').val(recepcion);

    });


    function regresar() {
        window.location.href = "<?php echo base_url('index.php/auth/login') ?>";
    }

    function validaNumericos(event) {
        if (event.charCode >= 48 && event.charCode <= 57) {
            return true;
        }
        return false;
    }

    $("#TipoIdLegal").val('<?php echo @$TipoIdLegal; ?>');
    $("#TipoId").val('<?php  echo @$TipoId; ?>');

    var contador = "<?php echo $registro ?>";
    if (contador != "") {
        $('#TipoId').attr('disabled', true);
        $('#TipoIdLegal').attr('disabled', true);
        $("#idAportante").attr("readonly", "readonly");
        $("#nombres").attr("readonly", "readonly");
        $("#apellidos").attr("readonly", "readonly");
        $("#correo").attr("readonly", "readonly");
        $("#idLegal").attr("readonly", "readonly");
    }
</script>