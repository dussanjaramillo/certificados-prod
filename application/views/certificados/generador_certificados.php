<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128"/>

<p><br>

<div class="center-form-large " style=" width:70%; ">
    <h1 style=" font-weight: 550; ">Certificados Fic
    </h1>
    <br>

    <div class="p-3 col-sm-12 bg-light text-dark ">


        <div class="abs-center">

            <table id="validar" class="table table-bordered table-striped">
                <tbody>
                <tr style="display:block; visibility:visible">

                    <td><strong>Certificado: </strong></td>
                    <td>
                        <select id="certificado" name="certificado" style="width: 260px;height: 32px;">
                            <option value="-1" select>Seleccione</option>
                            <?php
                            foreach ($select as $key => $value) {
                                echo '<option value="' . $value['VALUE'] . '">' . $value['NOMBRE'] . '</option>';
                            }
                            ?>

                        </select>
                    </td>
                </tr>

                <br>


                <td colspan="2">
                    <br>
                    <div class="col-md-12">
                        <center>
                            <div class="btn-group">
                                <br>
                                <center>
                                    <button id="cargar" class="btn btn-primary btn-sm">Cargar</button>
                                </center>
                                <div id="ajax_load" class="col-sm"
                                     style="display: none"> <?php echo anchor(base_url('index.php/auth/consulta'), '<i class="icon-remove"></i> Cancelar', 'class="btn btn-warning"'); ?></div>
                        </center>
                    </div>
        </div>


        </td>

        </tbody>
        </table>
        <?php $inputs = $input; ?>
    </div>
    <td align="center" colspan="2">


        <div class="col-md-12">

        </div>
</div>


</div>
</div>

<div id="resultado" style="display: none; "></div>

<script>
    $("#certificado option[value=certificados22]").hide();

    function regresar() {
        window.location.href = "<?php echo base_url('index.php/auth/consulta') ?>";
    }

    $("#ajax_load").show();
    $('#certificado').change(function () {


        document.getElementById('resultado').style.display = 'none';
        $("#ajax_load").show();

    });

    document.getElementById('resultado').style.display = 'block';

    $('#cargar').click(function () {
        $("#ajax_load").hide();
        document.getElementById('resultado').style.display = 'block';

        var certificado = $('#certificado').val();
        if (certificado == '-1') {
            alert('No se ha seleccionado el certificado');
            $("#ajax_load").show();
            return false;
        }
        jQuery(".preload, .load").show();

        var url = certificado;
        //
        $.post(url + '/', {
            certificado: certificado
        })
            .done(function (msg) {
                $('#resultado').html(msg)
                jQuery(".preload, .load").hide();
            }).fail(function (msg) {
            alert('Error de conexión');
            jQuery(".preload, .load").hide();
        })
    })
    $("#ajax_load").show();
    jQuery(".preload, .load").hide();
</script>
<style>
    input {
        width: 100%;
        margin-bottom: 7px;
        padding: 1px;
        background-color: none;
        border-radius: 2px;
        border: 1.5px solid #AAA;
    }

    .table {
        margin: auto;
        width: 50% !important;
    }

    .table td {
        text-align: center;
    }

    .abs-center {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 10vh;
    }
</style>