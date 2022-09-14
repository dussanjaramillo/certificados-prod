<?php
error_reporting(0);

class Iniciocertificados extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Nu_soap');

        date_default_timezone_set('America/Bogota');

        $this->load->library('form_validation');
        $this->load->helper('url');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $this->load->helper('language');
        $this->template_file = 'templates/main';

        $this->data['user'] = $this->session->all_userdata();

        define("COD_USUARIO", $this->data['user']['user_id']);
        define("COD_REGIONAL", $this->data['user']['regional']);
    }

    function index()
    {
    }

    function tipoCertificados()
    {
        if ($this->ion_auth->logged_in()) {

            $this->data['nit'] = COD_USUARIO;

            $cliente = new nusoap_client(service_base_url('certificados_services/index/wsdl?wsdl'), false);

            $parametros = array('numero' => "1");
            $respuesta = $cliente->call('Certificados_services..ListaCertificadosFic', $parametros);

            $tipo = $this->input->post('tipoCertificado');

            switch ($tipo) {
                case "APORTESPARAFISCALES":
                    $this->data['input'] = "1";
                    $this->data['vista'] = 'certificados1';
                    //  $this->activo2('certificados1');
                    $this->data['pdf'] = "";
                    $this->data['titulo'] = "Certificado Aportes Parafiscales";
                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                    break;
                case "RECIPROCAS":
                    //  $post = $this->input->post();
                    $this->data['titulo'] = "Certificados Recíprocas";
                    $this->data['vista'] = 'certificados17';
                    //    $this->activo2('certificados17');
                    $this->data['pdf'] = "";
                    $this->data['input'] = "17";
                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                    break;
                case "TRIBUTARIO-RECAUDO":
                    $this->data['titulo'] = "Certificado Tributario y Recaudo de Pagos";
                    $this->data['vista'] = 'certificados2';
                    //     $this->activo2('certificados2');
                    $this->data['pdf'] = "";
                    $this->data['input'] = "2";
                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                    break;
                case "FIC":
                    $this->data['select'] = $respuesta['Respuesta'];
                    $this->template->load($this->template_file, 'certificados/generador_certificados', $this->data);
                    break;
            }
        } else {
            redirect(base_url('index.php/auth/login'), 'refresh');
        }
    }

    function imprimir()
    {
        $cliente = new nusoap_client(service_base_url('certificados_services/index/wsdl?wsdl'), false);

        $id = COD_USUARIO;
        $regional = COD_REGIONAL;
        $tipo = $this->input->post('vista');
        switch ($tipo) {

            case "certificados1":
                $parametros = array('nit' => $id, 'regional' => $regional);
                $respuesta = $cliente->call('Certificados_services..CetificadosAportes', $parametros);

                if ($respuesta['Respuesta'] == '') {
                    $this->data['title'] = "No es posible Realizar la consulta ";
                    $this->session->set_flashdata('message', $this->data['title']);
                    redirect(base_url('index.php/auth/consulta'), 'refresh');
                } else {
                    $this->data['input'] = "1";
                    $this->data['vista'] = 'certificados1';
                    $this->data['titulo'] = "Certificado Aportes Parafiscales";
                    $this->data['pdf'] = $respuesta['pdf'];

                    $this->data['nombre'] = $respuesta['nombre'];
                }
                $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                break;

            case "certificados17":
                $parametros = array('numero' => $id, 'ano' => $this->input->post('ano'));
                $resultado = $cliente->call('Certificados_services..CetificadosReciprocas', $parametros);
                if ($resultado['Respuesta'] == '') {
                    $this->data['title'] = "No es posible Realizar la consulta ";
                    $this->session->set_flashdata('message', $this->data['title']);
                    redirect(base_url('index.php/auth/consulta'), 'refresh');
                } else {
                    $this->data['titulo'] = "Certificados Recíprocas";
                    $this->data['vista'] = 'certificados17';
                    $this->data['nombre'] = $resultado['nombre'];
                    $this->data['pdf'] = $resultado['pdf'];
                    $this->data['input'] = "17";
                }
                $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);

                break;
            case "certificados2":
                $parametros = array('numero' => $id, 'ano' => $this->input->post('ano'), 'regional' => $regional);
                $resultado = $cliente->call('Certificados_services..CertificadoTributarioRecaudo', $parametros);

                if ($resultado['Respuesta'] == '') {
                    $this->data['title'] = "No es posible Realizar la consulta ";
                    $this->session->set_flashdata('message', $this->data['title']);
                    redirect(base_url('index.php/auth/consulta'), 'refresh');
                } else {
                    $this->data['titulo'] = "Certificado Tributario y Recaudo de Pagos";
                    $this->data['vista'] = 'certificados2';
                    $this->data['nombre'] = $resultado['nombre'];
                    $this->data['pdf'] = $resultado['pdf'];
                    $this->data['input'] = "2";
                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                }

                break;

            case "certificados7":
                $parametros = array('nit' => $id, 'obra' => $this->input->post('obra'), 'regioanl' => $regional);

                $otro = array($this->input->post('vista') => $parametros);
                $parametros1 = array("fic" => $otro);
                $resultado = $cliente->call('Certificados_services..certificadosFic', $parametros1);

                if ($resultado['Respuesta'] == '') {
                    $this->data['title'] = "No es posible Realizar la consulta ";
                    $this->session->set_flashdata('message', $this->data['title']);
                    redirect(base_url('index.php/auth/consulta'), 'refresh');
                } else {
                    $this->data['titulo'] = "Certificados FIC";
                    $this->data['vista'] = 'certificados7';
                    $this->data['input'] = "7";
                    $this->data['nombre'] = $resultado['nombre'];
                    $this->data['pdf'] = $resultado['pdf'];
                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                }

                break;

            case "certificados8":
                $parametros = array('nit' => $this->input->post('transac'), 'regioanl' => $regional);

                $otro = array($this->input->post('vista') => $parametros);
                $parametros1 = array("fic" => $otro);
                $resultado = $cliente->call('Certificados_services..certificadosFic', $parametros1);

                if ($resultado['Respuesta'] == '') {
                    $this->data['title'] = "No es posible Realizar la consulta ";
                    $this->session->set_flashdata('message', $this->data['title']);
                    redirect(base_url('index.php/auth/consulta'), 'refresh');
                } else {
                    $this->data['titulo'] = "Certificados FIC";
                    $this->data['vista'] = 'certificados8';
                    $this->data['input'] = "8";
                    $this->data['nombre'] = $resultado['nombre'];
                    $this->data['pdf'] = $resultado['pdf'];
                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                }

                break;

            case "certificados9":
                $parametros = array('nit' => $id, 'obra' => $this->input->post('transacion'), 'regioanl' => $regional);

                $otro = array($this->input->post('vista') => $parametros);
                $parametros1 = array("fic" => $otro);
                $resultado = $cliente->call('Certificados_services..certificadosFic', $parametros1);

                if ($resultado['Respuesta'] == '') {
                    $this->data['title'] = "No es posible Realizar la consulta ";
                    $this->session->set_flashdata('message', $this->data['title']);
                    redirect(base_url('index.php/auth/consulta'), 'refresh');
                } else {
                    $this->data['titulo'] = "Certificados FIC";
                    $this->data['vista'] = 'certificados9';
                    $this->data['input'] = "9";
                    $this->data['nombre'] = $resultado['nombre'];
                    $this->data['pdf'] = $resultado['pdf'];
                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                }

                break;

            case "certificados12":
                $parametros = array('nit' => $this->input->post('Ntickei'), 'regioanl' => $regional);

                $otro = array($this->input->post('vista') => $parametros);
                $parametros1 = array("fic" => $otro);
                $resultado = $cliente->call('Certificados_services..certificadosFic', $parametros1);

                if ($resultado['Respuesta'] == '') {
                    $this->data['title'] = "No es posible Realizar la consulta ";
                    $this->session->set_flashdata('message', $this->data['title']);
                    redirect(base_url('index.php/auth/consulta'), 'refresh');
                } else {
                    $this->data['titulo'] = "Certificados FIC";
                    $this->data['vista'] = 'certificados12';
                    $this->data['input'] = "12";
                    $this->data['nombre'] = $resultado['nombre'];
                    $this->data['pdf'] = $resultado['pdf'];
                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                }

                break;

            case "certificados13":
                $parametros = array('nit' => $this->input->post('Nticempresakei'), 'obra' => $this->input->post('num_proceso'), 'regioanl' => $regional);

                $otro = array($this->input->post('vista') => $parametros);
                $parametros1 = array("fic" => $otro);
                $resultado = $cliente->call('Certificados_services..certificadosFic', $parametros1);

                if ($resultado['Respuesta'] == '') {
                    $this->data['title'] = "No es posible Realizar la consulta ";
                    $this->session->set_flashdata('message', $this->data['title']);
                    redirect(base_url('index.php/auth/consulta'), 'refresh');
                } else {
                    $this->data['titulo'] = "Certificados FIC";
                    $this->data['vista'] = 'certificados13';
                    $this->data['input'] = "13";
                    $this->data['nombre'] = $resultado['nombre'];
                    $this->data['pdf'] = $resultado['pdf'];
                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                }

                break;

            case "certificados15":

                $parametros = array('nit' => $this->input->post('num_proceso'), 'regioanl' => $regional);

                $otro = array($this->input->post('vista') => $parametros);
                $parametros1 = array("fic" => $otro);
                $resultado = $cliente->call('Certificados_services..certificadosFic', $parametros1);

                if ($resultado['Respuesta'] == '') {
                    $this->data['title'] = "No es posible Realizar la consulta ";
                    $this->session->set_flashdata('message', $this->data['title']);
                    redirect(base_url('index.php/auth/consulta'), 'refresh');
                } else {
                    $this->data['titulo'] = "Certificados FIC";
                    $this->data['vista'] = 'certificados15';
                    $this->data['nombre'] = $resultado['nombre'];
                    $this->data['input'] = "15";
                    $this->data['pdf'] = $resultado['pdf'];
                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                }

                break;

            case "certificados21":

                $parametros = array('nit' => COD_USUARIO, 'regioanl' => $regional);

                $otro = array($this->input->post('vista') => $parametros);

                $parametros1 = array("fic" => $otro);
                $resultado = $cliente->call('Certificados_services..certificadosFic', $parametros1);

                if ($resultado['Respuesta'] == '') {
                    $this->data['title'] = "No es posible Realizar la consulta ";
                    $this->session->set_flashdata('message', $this->data['title']);
                    redirect(base_url('index.php/auth/consulta'), 'refresh');
                } else {
                    $this->data['titulo'] = "Certificados FIC";
                    $this->data['vista'] = 'certificados21';
                    $this->data['nombre'] = $resultado['nombre'];
                    $this->data['input'] = "21";
                    $this->data['pdf'] = $resultado['pdf'];

                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                }

                break;

            case "certificados23":

                $parametros = array('nit' => COD_USUARIO, 'regioanl' => $regional, 'ano' => $this->input->post('ano'));

                $otro = array($this->input->post('vista') => $parametros);

                $parametros1 = array("fic" => $otro);
                $resultado = $cliente->call('Certificados_services..certificadosFic', $parametros1);

                if ($resultado['Respuesta'] == '') {
                    $this->data['title'] = "No es posible Realizar la consulta ";
                    $this->session->set_flashdata('message', $this->data['title']);
                    redirect(base_url('index.php/auth/consulta'), 'refresh');
                } else {
                    $this->data['titulo'] = "Certificados FIC";
                    $this->data['vista'] = 'certificados23';
                    $this->data['nombre'] = $resultado['nombre'];

                    $this->data['input'] = "23";
                    $this->data['pdf'] = $resultado['pdf'];
                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                }

                break;

            case "certificados16":
                $parametros = array('nit' => $this->input->post('empresa'), 'obra' => $this->input->post('periodo'), 'regioanl' => $regional);

                $otro = array($this->input->post('vista') => $parametros);
                $parametros1 = array("fic" => $otro);
                $resultado = $cliente->call('Certificados_services..certificadosFic', $parametros1);

                if ($resultado['Respuesta'] == '') {
                    $this->data['title'] = "No es posible Realizar la consulta ";
                    $this->session->set_flashdata('message', $this->data['title']);
                    redirect(base_url('index.php/auth/consulta'), 'refresh');
                } else {
                    $this->data['titulo'] = "Certificados FIC";
                    $this->data['vista'] = 'certificados16';
                    $this->data['nombre'] = $resultado['nombre'];
                    $this->data['input'] = "16";
                    $this->data['pdf'] = $resultado['pdf'];
                    $this->template->load($this->template_file, 'certificados/certificadosAportes', $this->data);
                }

                break;
        }
    }

    function certificados7()
    {
        $this->data['nit'] = COD_USUARIO;
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados7';
        $this->data['pdf'] = "";
        $this->data['input'] = "7";
        $this->load->view('certificados/certificadosAportes', $this->data);
    }

    function certificados8()
    {
        $this->data['nit'] = COD_USUARIO;
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados8';
        $this->data['pdf'] = "";
        $this->data['input'] = "8";
        $this->load->view('certificados/certificadosAportes', $this->data);
    }

    function certificados9()
    {
        $this->data['nit'] = COD_USUARIO;
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados9';
        $this->data['pdf'] = "";
        $this->data['input'] = "9";
        $this->load->view('certificados/certificadosAportes', $this->data);
    }

    function certificados12()
    {
        $this->data['nit'] = COD_USUARIO;
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados12';
        $this->data['pdf'] = "";
        $this->data['input'] = "12";
        $this->load->view('certificados/certificadosAportes', $this->data);
    }

    function certificados13()
    {
        $this->data['nit'] = COD_USUARIO;
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados13';
        $this->data['pdf'] = "";
        $this->data['input'] = "13";
        $this->load->view('certificados/certificadosAportes', $this->data);
    }


    function certificados15()
    {
        $this->data['nit'] = COD_USUARIO;
        $post = $this->input->post();
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados15';
        $this->data['pdf'] = "";
        $this->data['input'] = "15";
        $this->load->view('certificados/certificadosAportes', $this->data);
    }

    function certificados16()
    {
        $this->data['nit'] = COD_USUARIO;
        $post = $this->input->post();
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados16';
        $this->data['pdf'] = "";
        $this->data['input'] = "16";
        $this->load->view('certificados/certificadosAportes', $this->data);
    }

    function mirar_certificados()
    {
        $this->template->load($this->template_file, 'certificados/mirar_certificados');
    }

    function buscar_certificado()
    {
        $cliente = new nusoap_client(service_base_url('certificados_services/index/wsdl?wsdl'), false);

        $parametros = array('numero' => $this->input->post('numero'), 'codigo' => $this->input->post('codigo'));

        $resultado = $cliente->call('Certificados_services..Buscarcertificado', $parametros);
        echo $resultado['Respuesta'];
    }

    function registrar()
    {
        $myVar = $this->session->flashdata('item2');
        if ($myVar != '') {
            $this->data['mensaje'] = $myVar;

        } else {
            $this->data['mensaje'] = "";
        }
        $this->data['TipoId'] = "";
        $this->data['idAportante'] = "";
        $this->data['nombres'] = "";
        $this->data['apellidos'] = "";
        $this->data['correo'] = "";
        $this->data['TipoIdLegal'] = "";
        $this->data['idLegal'] = "";
        $this->data['registro'] = "";

        $this->template->load($this->template_file, 'inicio/registro', $this->data);
    }

    function verificacion()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            // Indica los métodos permitidos.
            header('Access-Control-Allow-Methods: GET, POST, DELETE');
            // Indica los encabezados permitidos.
            header('Access-Control-Allow-Headers: Authorization');
            http_response_code(204);
        }
        $valor = date('Y/m/d H:i:s');
        $fechaA = $this->input->post('fefhas');
        $fechaB = $valor;
        $diff = gmdate("i:s", strtotime($fechaB) - strtotime($fechaA));
        //print_r( $diff);die;
        if ($diff > '15:00') {

            $this->data['message'] = "SU CODIGO EXPIRO";
            $this->template->load($this->template_file, 'auth/login', $this->data);
        } else {
            $datos = $this->input->post();
            if ($datos != '') {
                @$contador = $this->input->post('contante');
                if (@$contador != '') {
                    $parametros = array('id1' => $this->input->post('idAportante'), 'correo' => $this->input->post('correo'), 'intentos' => $contador);

                    $cliente = new nusoap_client(service_base_url('certificadosempresa_services/index/wsdl?wsdl'), false);

                    $respuesta = $cliente->call('Certificadosempresa_services..verificar', $parametros);
                    // print_r($respuesta);die;
                    $data = $respuesta['Codigo'];
                    echo json_encode($data);
                } else if ($this->input->post('codigo') == 3) {
                    redirect(base_url('index.php/auth/login'), 'refresh');
                } else {
                    $this->data['idAportante'] = $this->input->post('Aportante');
                    $this->data['correo'] = $this->input->post('correos');
                    $this->template->load($this->template_file, 'inicio/verificacion', $this->data);
                }
            } else {
                redirect(base_url('index.php/auth/login'), 'refresh');
            }
        }
    }

    function registrarCodigo()
    {
        $myVar = $this->session->flashdata('item');
        $this->data['registro'] = "1";
        $this->data['idAportante'] = $myVar['idAportante'];
        $this->data['TipoId'] = $myVar['TipoId'];
        $this->data['nombres'] = $myVar['nombres'];
        $this->data['apellidos'] = $myVar['apellidos'];
        $this->data['correo'] = $myVar['correo'];
        $this->data['TipoIdLegal'] = $myVar['TipoIdLegal'];
        $this->data['idLegal'] = $myVar['idLegal'];
        $this->data['Fechas'] = $myVar['Fechas'];
        $this->data['mensaje'] = "";
        $this->data['validacion'] = $myVar['validacion'];
        if ($myVar == '') {
            $this->data['mensaje'] = " SESION INACTIVA.. INGRESE NUEVAMENTE EL REGISTRO";
            $this->load->library('session');
            $this->session->set_flashdata('item2', $this->data['mensaje']);

            redirect(base_url("index.php/iniciocertificados/registrar"));

        }
        $this->template->load($this->template_file, 'inicio/registro', $this->data);
    }

    function registrarServices()
    {
        $this->data['idAportante'] = $this->input->post('idAportante');
        $cont = 3;
        @$codigo = $this->input->post('codigo');
        @$validacion = $this->input->post('validacion');

        //print_r(@$codigo);
        if ($codigo != '') {
            if (@$validacion == @$codigo) {
                $this->template->load($this->template_file, 'inicio/verificacion', $this->data);
            }
        } else {


            $this->data['validacion'] = "";
            $this->data['registro'] = "1";
            $this->data['TipoId'] = $this->input->post('TipoId');
            $this->data['nombres'] = $this->input->post('nombres');
            $this->data['apellidos'] = $this->input->post('apellidos');
            $this->data['correo'] = $this->input->post('correo');
            $this->data['TipoIdLegal'] = $this->input->post('TipoIdLegal');
            $this->data['idLegal'] = $this->input->post('idLegal');
            $this->data['mensaje'] = "";
            $this->data['ver'] = $this->input->post('ver');
            //  print_r($this->input->post());die;  //$this->data['validación']!=$this->input->post('idVerificacion')
            $var = $this->input->post('idVerificacion');

            $parametros = array('user' => $this->input->post('nombres'), 'apellidos' => $this->input->post('apellidos'), 'correo' => $this->input->post('correo'), 'tipo1' => $this->input->post('TipoId'), 'tipo2' => $this->input->post('TipoIdLegal'), 'id1' => $this->input->post('idAportante'), 'id2' => $this->input->post('idLegal'));

            $cliente = new nusoap_client(service_base_url('certificadosempresa_services/index/wsdl?wsdl'), false);

            // $respuesta = $cliente->call('Certificadosempresa_services..RegistroSirec', $parametros);
            $respuesta = $cliente->call('Certificadosempresa_services..RegistroSirec', $parametros);

            $codigoVerificcion = "";
            $codigoVerificcion = $this->input->post('idVerificacion');

            if ($respuesta['Respuesta'] == 4) {
                $this->data['mensaje'] = " YA SE ENCUENTRA REGISTRADO INGRESE A RECUPERAR CLAVE";
                $this->template->load($this->template_file, 'inicio/registro', $this->data);
                return;
            }
            if ($respuesta['Respuesta'] == 3) {
                $this->data['mensaje'] = "Señor Usuario debe acercarse a actulizar los datos ";
                $this->template->load($this->template_file, 'inicio/registro', $this->data);
            } else {

                if ($respuesta['Respuesta'] == 1) {
                    $this->data['validacion'] = $respuesta['Codigo'];
                    $this->data['Fechas'] = date('Y/m/d H:i:s');

                    $headers = '<p>El Servicio Nacional de Aprendizaje – SENA le informa que usted ha solicitado registrarse Certificados de Aportes y FIC.</p><br><br>';
                    $headers .= '<p>Su Código de Acceso Seguro es: ' . $respuesta['Codigo'] . '</p><br><br>';
                    $headers .= '<p> Este código expira en 15 minutos </p><br><br>';
                    $headers .= '<p>En caso de presentar inconvenientes en la generación de Certificados de Aportes y FIC, escribir al correo: certiaportes@sena.edu.co</p><br><br>';
// Additional headers

// Additional headers
                    $headers .= ' <p>**********************NO RESPONDER - Mensaje Generado Automáticamente**********************</p><br><br>';
                    $headers .= 'Este correo es únicamente informativo y es de uso exclusivo del destinatario(a), puede contener información privilegiada y/o confidencial. Si no es usted el destinatario(a) deberá borrarlo inmediatamente. Queda notificado que el mal uso, divulgación no autorizada, alteración y/o  modificación malintencionada sobre este mensaje y sus anexos quedan estrictamente prohibidos y pueden ser legalmente sancionados.  El SENA  no asume ninguna responsabilidad por estas circunstancias.' . "\r\n";

                    $config = array(
                        'protocol' => 'smtp',
                        'smtp_host' => 'relay.sena.edu.co',
                        'smtp_port' => 25,
                        'smtp_user' => 'certiaportes@sena.edu.co',
                        'smtp_pass' => 'Luis0610*',
                        'mailtype' => 'html',
                        'charset' => 'utf-8',
                        'newline' => "\r\n"
                    );

                    $this->email->initialize($config);
                    $this->email->to($this->input->post('correo'), 'Certificados En Línea');
                    $this->email->from('certiaportes@sena.edu.co', 'Certificados En Línea');
                    $this->email->subject('SENA -Código de Acceso Seguro');
                    $this->email->message($headers);
                    $this->email->send();

                    $this->load->library('session');
                    $this->session->set_flashdata('item', $this->data);

                    redirect(base_url("index.php/iniciocertificados/registrarCodigo"));
                }

                if ($respuesta['Respuesta'] == 6) {
                    $this->data['validacion'] = $respuesta['Codigo'];
                    $this->template->load($this->template_file, 'inicio/registro', $this->data);
                }
            }
        }
    }

    function modificarClave()
    {
        $parametros = array('user' => $this->input->post('identity'), 'pass' => $this->input->post('password'), 'nit' => $this->input->post('nit'));

        $cliente = new nusoap_client(service_base_url('certificadosempresa_services/index/wsdl?wsdl'), false);

        $respuesta = $cliente->call('Certificadosempresa_services..RegistroUpdateLogin', $parametros);

        if ($respuesta['Respuesta'] == 1) {
            $this->load->library('session');
            $this->session->set_flashdata('item', $this->input->post());
            redirect(base_url('index.php/auth/login'), 'refresh');
        } else {
            redirect(base_url('index.php/auth/login'), 'refresh');
        }
    }

    function recuperarClave()
    {
        if ($this->ion_auth->logged_in()) {
            redirect(base_url('index.php/auth/consulta'), 'refresh');
        } else {

            $myVar = $this->session->flashdata('item2');
            if ($myVar != '') {
                $this->data['mensaje'] = $myVar;

            } else {
                $this->data['mensaje'] = "";
            }
            $this->data['idAportante'] = '';
            $this->data['correo'] = '';
            $this->data['registro'] = "";

            $this->template->load($this->template_file, 'inicio/recuperarClave', $this->data);
        }
    }


    function VerificarCodigo()
    {
        $myVar = $this->session->flashdata('item');
        $dates = date('d/m/Y H:i:s');

        $this->data['mensaje'] = "";
        $this->data['registro'] = "1";
        $this->data['validacion'] = "";

        $this->data['idAportante'] = $myVar['idAportante'];
        $this->data['correo'] = $myVar['correo'];
        $this->data['validacion'] = $myVar['validacion'];
        $this->data['Fechas'] = $myVar['Fechas'];

        if ($myVar == '') {
            $this->data['mensaje'] = " SESION INACTIVA.. INTENTE RECUPERAR SU CLAVE NUEVAMENTE";
            $this->load->library('session');
            $this->session->set_flashdata('item2', $this->data['mensaje']);

            redirect(base_url("index.php/iniciocertificados/recuperarClave"));
        }

        $this->template->load($this->template_file, 'inicio/recuperarClave', $this->data);
    }

    function verificarClave()
    {
        $dates = date('d/m/Y H:i:s');

        $this->data['mensaje'] = "";
        $this->data['registro'] = "1";
        $this->data['validacion'] = "";

        $this->data['idAportante'] = $this->input->post('idAportante');
        $this->data['correo'] = $this->input->post('correo');

        $var = $this->input->post('idVerificacion');
        $cc = $_POST['idAportante'];

        $intentos = '';

        $parametros = array('id1' => $this->input->post('idAportante'), 'correo' => $this->input->post('correo'), 'intentos' => $intentos);

        $cliente = new nusoap_client(service_base_url('certificadosempresa_services/index/wsdl?wsdl'), false);

        $respuesta = $cliente->call('Certificadosempresa_services..verificar', $parametros);

        if ($respuesta['Respuesta'] == 3) {
            $this->data['mensaje'] = " EL CORREO INGRESADO NO SE ENCUENTRA REGISTRADO ";
            $this->template->load($this->template_file, 'inicio/recuperarClave', $this->data);
        }
        if ($respuesta['Respuesta'] == 5) {
            $this->data['mensaje'] = " EL NÚMERO DE IDENTIFICACIÓN NO SE ENCUENTRA REGISTRADO ";
            $this->template->load($this->template_file, 'inicio/recuperarClave', $this->data);
        }
        if ($respuesta['Respuesta'] == 7) {
            $this->data['mensaje'] = "EL CORREO INGRESADO NO SE ENCUENTRA REGISTRADO";
            $this->template->load($this->template_file, 'inicio/recuperarClave', $this->data);
        } else {
            $this->data['validacion'] = $respuesta['Codigo'];
            $this->data['Fechas'] = date('Y/m/d H:i:s');

            if ($respuesta['Respuesta'] == 1) {

                $headers = '<p>El Servicio Nacional de Aprendizaje – SENA le informa que usted ha solicitado registrarse Certificados de Aportes y FIC.</p><br><br>';
                $headers .= '<p>Su Código de Recuperación de Contraseña es: ' . $respuesta['Codigo'] . '</p><br><br>';
                $headers .= '<p> Este código expira en 15 minutos </p><br><br>';
                $headers .= '<p>En caso de presentar inconvenientes en la generación de Certificados de Aportes y FIC, escribir al correo: certiaportes@sena.edu.co</p><br><br>';
                // Additional headers
                $headers .= ' <p>**********************NO RESPONDER - Mensaje Generado Automáticamente**********************</p><br><br>';
                $headers .= 'Este correo es únicamente informativo y es de uso exclusivo del destinatario(a), puede contener información privilegiada y/o confidencial. Si no es usted el destinatario(a) deberá borrarlo inmediatamente. Queda notificado que el mal uso, divulgación no autorizada, alteración y/o  modificación malintencionada sobre este mensaje y sus anexos quedan estrictamente prohibidos y pueden ser legalmente sancionados.  El SENA  no asume ninguna responsabilidad por estas circunstancias.' . "\r\n";


                $htmlContent = 'Su Código de Recuperación de Contraseña es: ' . $respuesta['Codigo'];
                $config = array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'relay.sena.edu.co',
                    'smtp_port' => 25,
                    'smtp_user' => 'certiaportes@sena.edu.co',
                    'smtp_pass' => 'Luis0610*',
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'newline' => "\r\n"
                );

                $this->email->initialize($config);
                $this->email->to($this->input->post('correo'), 'Certificados En Línea');
                $this->email->from('certiaportes@sena.edu.co', 'Certificados En Línea');
                $this->email->subject('SENA -Código de  Recuperación de Contraseña');
                $this->email->message($headers);
                $this->email->send();
                //echo $this->email->print_debugger();die();

                $this->load->library('session');
                $this->session->set_flashdata('item', $this->data);

                redirect(base_url("index.php/iniciocertificados/VerificarCodigo"));

            }
            if ($respuesta['Respuesta'] == 6) {
                $this->data['mensaje'] = "A OCURRIDO UN ERROR";
            }

        }

    }

    function certificados21()
    {
        $this->data['nit'] = COD_USUARIO;
        $post = $this->input->post();
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados21';
        $this->data['pdf'] = "";
        $this->data['input'] = "21";
        $this->load->view('certificados/certificadosAportes', $this->data);
    }

    function certificados23()
    {
        $this->data['nit'] = COD_USUARIO;
        $post = $this->input->post();
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados23';
        $this->data['pdf'] = "";
        $this->data['input'] = "23";
        $this->load->view('certificados/certificadosAportes', $this->data);
    }
}

/* End of file categorias.php */
/* Location: ./system/application/controllers/categorias.php */