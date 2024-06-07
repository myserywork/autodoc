<?php

function trueOrFail($condition, $message = 'Erro desconhecido')
{
    if (!$condition) {
        throw new Exception($message);
    }
}

function convertDate($date) {
   
    $timestamp = strtotime($date);
    $formattedDate = date('d/m/Y', $timestamp);

    return $formattedDate;
}

function format_price($value)
{
    $fmt = new NumberFormatter('pt_BR', NumberFormatter::CURRENCY);
    return $fmt->formatCurrency($value, "BRL");
}

function clean_char($string)
{
    return preg_replace('/\D/', '', $string);
}

function isMobile()
{
    $CI =& get_instance();
    $CI->load->library('user_agent');

    return $CI->agent->is_mobile();
}

function brazilianPhoneParser(string $phoneString, bool $forceOnlyNumber = true): ?array
{
    $phoneString = preg_replace('/[()]/', '', $phoneString);
    if (preg_match('/^(?:(?:\+|00)?(55)\s?)?(?:\(?([0-0]?[0-9]{1}[0-9]{1})\)?\s?)??(?:((?:9\d|[2-9])\d{3}\-?\d{4}))$/', $phoneString, $matches) === false) {
        return null;
    }

    $ddi = $matches[1] ?? '';
    $ddd = preg_replace('/^0/', '', $matches[2] ?? '');
    $number = $matches[3] ?? '';
    if ($forceOnlyNumber === true) {
        $number = preg_replace('/-/', '', $number);
    }

    return ['ddi' => $ddi, 'ddd' => $ddd, 'number' => $number];
}

function toBrasilFormat($numero)
{
    return "(" . $numero['ddd'] . ") " . $numero['number'];
}


function calcular_porcentagem($percent, $number)
{
    $porcentagem = '0.' . $percent;
    return $porcentagem * $number;
}

function delTree($dir)
{
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

function formatCnpjCpf($value)
{
    $cnpj_cpf = preg_replace("/\D/", '', $value);

    if (strlen($cnpj_cpf) === 11) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    }

    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}


function fetchOpenAIResponse($messages) {
    $url = 'https://api.openai.com/v1/chat/completions';
    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            [
                "role" => "system",
                "content" => "Você é um assistente que converterá html para plaintext bem formatado."
            ],
            [
                "role" => "user",
                "content" => $messages
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer sk-proj-c1EMLEZ1pGVV6R2i0cxCT3BlbkFJeKbUpn6rHstH1MLacOvD' // Ensure you have set this environment variable
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $message = json_decode($response, true);
    $message = $message['choices'][0]['message']['content'];
    return $message;
}


function zipfolder($folder)
{
    $rootPath = realpath($folder);

    // Initialize archive object
    $zip = new ZipArchive();
    $zip->open($folder . '/recibos.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        // Skip directories (they would be added automatically)
        if (!$file->isDir()) {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Zip archive will be created only after closing object
    $zip->close();
}


function getDiasUteis($dtInicio, $dtFim, $feriados = [])
{
    $tsInicio = strtotime($dtInicio);
    $tsFim = strtotime($dtFim);

    $quantidadeDias = 0;
    while ($tsInicio <= $tsFim) {
        // Verifica se o dia é igual a sábado ou domingo, caso seja continua o loop
        $diaIgualFinalSemana = (date('D', $tsInicio) === 'Sat' || date('D', $tsInicio) === 'Sun');
        // Verifica se é feriado, caso seja continua o loop
        $diaIgualFeriado = (count($feriados) && in_array(date('Y-m-d', $tsInicio), $feriados));

        $tsInicio += 86400; // 86400 quantidade de segundos em um dia

        if ($diaIgualFinalSemana || $diaIgualFeriado) {
            continue;
        }

        $quantidadeDias++;
    }

    return $quantidadeDias;
}



function dateAll()
{
    $date['month'] = date("m");
    $date['day'] = date("d");
    $date['year'] = date("Y");
    $date['date'] = date('d/m/Y');


    return $date;
}


function tirarAcentos($string)
{ //Declara a função e recebe o parâmetro $string.
    //Abaixo é usado str_replace em cada vogal ou consuante com acento que será retirado o acento.
    //Além de retirar o acento, retorna a informação na mesma variável $string.
    $string = str_replace('ã', 'a', $string);
    $string = str_replace('á', 'a', $string);
    $string = str_replace('Ã', 'A', $string);
    $string = str_replace('Á', 'A', $string);
    $string = str_replace('ç', 'c', $string);
    $string = str_replace('Ç', 'C', $string);
    $string = str_replace('ẽ', 'e', $string);
    $string = str_replace('é', 'e', $string);
    $string = str_replace('Ẽ', 'E', $string);
    $string = str_replace('É', 'E', $string);
    $string = str_replace('í', 'i', $string);
    $string = str_replace('Í', 'I', $string);
    $string = str_replace('ó', 'o', $string);
    $string = str_replace('Ó', 'O', $string);
    $string = str_replace('õ', 'o', $string);
    $string = str_replace('Ú', 'U', $string);
    $string = str_replace('ú', 'u', $string);
    
    $string = str_replace('Ãª', 'e', $string);
    
    //No final retorna a variável com o texto sem acento.
    return $string;
}



function corrigir_acentuacao_e_converter_para_html($texto) {
    // Remove BOM (Byte Order Mark) se existir
 
    
    // Mapa de substituição de caracteres corrompidos por entidades HTML
    $map = [
        'à' => '&agrave;', 'á' => '&aacute;', 'â' => '&acirc;', 'ã' => '&atilde;', 'ä' => '&auml;', 
        'ç' => '&ccedil;', 'è' => '&egrave;', 'é' => '&eacute;', 'ê' => '&ecirc;', 'ë' => '&euml;', 
        'ì' => '&igrave;', 'í' => '&iacute;', 'î' => '&icirc;', 'ï' => '&iuml;', 'ð' => '&eth;', 
        'ñ' => '&ntilde;', 'ò' => '&ograve;', 'ó' => '&oacute;', 'ô' => '&ocirc;', 'õ' => '&otilde;', 
        'ö' => '&ouml;', 'ù' => '&ugrave;', 'ú' => '&uacute;', 'û' => '&ucirc;', 'ü' => '&uuml;', 
        'ý' => '&yacute;', 'ÿ' => '&yuml;', 'À' => '&Agrave;', 'Á' => '&Aacute;', 'Â' => '&Acirc;', 
        'Ã' => '&Atilde;', 'Ä' => '&Auml;', 'Ç' => '&Ccedil;', 'È' => '&Egrave;', 'É' => '&Eacute;', 
        'Ê' => '&Ecirc;', 'Ë' => '&Euml;', 'Ì' => '&Igrave;', 'Í' => '&Iacute;', 'Î' => '&Icirc;', 
        'Ï' => '&Iuml;', 'Ð' => '&ETH;', 'Ñ' => '&Ntilde;', 'Ò' => '&Ograve;', 'Ó' => '&Oacute;', 
        'Ô' => '&Ocirc;', 'Õ' => '&Otilde;', 'Ö' => '&Ouml;', 'Ù' => '&Ugrave;', 'Ú' => '&Uacute;', 
        'Û' => '&Ucirc;', 'Ü' => '&Uuml;', 'Ý' => '&Yacute;', 'Þ' => '&THORN;', 'ß' => '&szlig;', 
        'º' => '&ordm;', 'ª' => '&ordf;', '‘' => '&lsquo;', '’' => '&rsquo;', '“' => '&ldquo;', 
        '”' => '&rdquo;', '«' => '&laquo;', '»' => '&raquo;', '–' => '&ndash;', '—' => '&mdash;', 
        ' ' => '&nbsp;'
    ];

    // Substitui os caracteres corrompidos no texto
    
    foreach ($map as $key => $value) {
        $texto = str_replace($key, $value, $texto);
    }
    

    return $texto_corrigido;
}



function xss_clean($data)
{
    // Fix &entity\n;
    $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    } while ($old_data !== $data);

    // we are done...
    return $data;
}

if (!function_exists('dump')) {
    function dump($var, $label = 'Dump', $echo = true)
    {

        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';

        if ($echo == true) {
            echo $output;
        } else {
        }
    }
}

function printJSON($response, $statusCode = 200)
{
    $ci = &get_instance();
    $ci->output->set_status_header($statusCode);
    $ci->output->set_content_type('application/json', 'utf-8');
    $ci->output->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

function format_error($errors)
{
    $error_formated = '';
    foreach ($errors as $error) {
        $error_formated .= $error . '<br>';
    }
    return $error_formated;
}

function mes_numero($numero)
{
    $meses = array(
        '01' => 'Janeiro',
        '02' => 'Fevereiro',
        '03' => 'Março',
        '04' => 'Abril',
        '05' => 'Maio',
        '06' => 'Junho',
        '07' => 'Julho',
        '08' => 'Agosto',
        '09' => 'Setembro',
        '10' => 'Outubro',
        '11' => 'Novembro',
        '12' => 'Dezembro',
    );
    return $meses[$numero];
}


function formata_cpf_cnpj($cpf_cnpj)
{

    $cpf_cnpj = preg_replace("/[^0-9]/", "", $cpf_cnpj);
    $tipo_dado = NULL;
    if (strlen($cpf_cnpj) == 11) {
        $tipo_dado = "cpf";
    }
    if (strlen($cpf_cnpj) == 14) {
        $tipo_dado = "cnpj";
    }
    switch ($tipo_dado) {
        default:
            $cpf_cnpj_formatado = "Não foi possível definir tipo de dado";
            break;

        case "cpf":
            $bloco_1 = substr($cpf_cnpj, 0, 3);
            $bloco_2 = substr($cpf_cnpj, 3, 3);
            $bloco_3 = substr($cpf_cnpj, 6, 3);
            $dig_verificador = substr($cpf_cnpj, -2);
            $cpf_cnpj_formatado = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "-" . $dig_verificador;
            break;

        case "cnpj":
            $bloco_1 = substr($cpf_cnpj, 0, 2);
            $bloco_2 = substr($cpf_cnpj, 2, 3);
            $bloco_3 = substr($cpf_cnpj, 5, 3);
            $bloco_4 = substr($cpf_cnpj, 8, 4);
            $digito_verificador = substr($cpf_cnpj, -2);
            $cpf_cnpj_formatado = $bloco_1 . "." . $bloco_2 . "." . $bloco_3 . "/" . $bloco_4 . "-" . $digito_verificador;
            break;
    }
    return $cpf_cnpj_formatado;
}

function is_mobile()
{
    require_once 'application/libraries/Mobile_Detect.php';
    $detect = new Mobile_Detect;
    return $detect->isMobile();
}

function loggedInUser($redirect = false)
{
    $ci = &get_instance();
    $ci->load->library('ion_auth');
    if (!$ci->ion_auth->logged_in()) {
        if ($redirect) {
            redirect(base_url('auth/login'));
        } else {
            return false;
        }
    } else {
        $user = $ci->ion_auth->user()->row();
        $userGroups = $ci->ion_auth->get_users_groups($user->id)->result();

        if (!empty($userGroups)) {
            $firstGroup = $userGroups[0];
            $groupId = $firstGroup->id;
            $groupName = $firstGroup->name;
        } else {
            $groupId = null;
            $groupName = null;
        }

        $user->group_id = $groupId;
        $user->group_name = $groupName;
        $user->groups = $userGroups;
        return $user;
    }
}



function temPermissao($permissao)
{
    $ci = &get_instance();
    $ci->load->library('ion_auth');
    if (!$ci->ion_auth->logged_in()) {
        return false;
    } else {
        $user = $ci->ion_auth->user()->row();
        $permissoes = json_decode($user->permissoes, true);

        if ($permissoes) {
            if (array_key_exists($permissao, $permissoes)) {
                return true;
            }
        } else {
            return false;
        }
    }
}




if (!function_exists('filter_data')) {
    function filter_data($data)
    {
        $data = strip_tags($data);
        $data = trim($data);
        return $data;
    }
}

if (!function_exists('random')) {
    function random($length)
    {
        $string = "";
        $chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[md5(rand(0, $size - 1))];
        }
        return $string;
    }
}

if (!function_exists('random_string')) {
    function random_string($length)
    {
        $string = "";
        $chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, $size - 1)];
        }
        return $string;
    }
}

if (!function_exists('textile_sanitize')) {
    function textile_sanitize($string)
    {
        $whitelist = '/[^a-zA-Z0-9Ð°-ÑÐ-Ð¯Ã©Ã¼Ñ€Ñ‚Ñ…Ñ†Ñ‡ÑˆÑ‰ÑŠÑ‹ÑÑŽÑŒÐÑƒÑ„Ò \.\*\+\\n|#;:!"%@{} _-]/';
        return preg_replace($whitelist, '', $string);
    }
}

if (!function_exists('strong_md5')) {
    function strong_md5()
    {
        $makeitstronger = time() + (7 * 24 * 60 * 60);
        $makeitstronger = md5(md5($makeitstronger));
        return $makeitstronger;
    }
}

function array2csv($array, &$title, &$data)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $title .= $key . ",";
            $data .= "" . ",";
            array2csv($value, $title, $data);
        } else {
            $title .= $key . ",";
            $data .= '"' . $value . '",';
        }
    }
}


if (!function_exists('escape')) {
    function escape($string)
    {
        return textile_sanitize($string);
    }
}

if (!function_exists('array2Html')) {
    function array2Html($data)
    {
        $return = '';
        foreach ($data as $key => $value) {
            $return .= "<tr><td>" . $key . "</td>";
            if (is_array($value) || is_object($value)) {
                $return .= "<td>" . array2Html($value) . "  </td>";
            } else {
                $return .= "<td>" . $value . "</td></tr>";
            }
        }
        return $return;
    }
}


if (!function_exists('aws_upload')) {
    function aws_upload()
    {
        require 'vendor/autoload.php';

        $bucket = 'development.centroavante.com.br';
        $keyname = 'HU/9qkioyZpRXeDFr/ugrGChGAHeBV4/fH8M2ul/';

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => 'us-east-1',
        ]);

        try {
            // Upload data.
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key' => $keyname,
                'Body' => 'Hello, world!',
                'ACL' => 'public-read',
            ]);

            // Print the URL to the object.
            echo $result['ObjectURL'] . PHP_EOL;
        } catch (S3Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}

function is_data_passed($date)
{
    $now = date("Y/m/d H:i:s");
    if (strtotime($date) > strtotime($now)) {
        return true;
    }
}

function slugify($text, string $divider = '-')
{
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}




function parse($string, $start = "", $end = ""){
    if (strpos($string, $start)) { // required if $start not exist in $string
        $startCharCount = strpos($string, $start) + strlen($start);
        $firstSubStr = substr($string, $startCharCount, strlen($string));
        $endCharCount = strpos($firstSubStr, $end);
        if ($endCharCount == 0) {
            $endCharCount = strlen($firstSubStr);
        }
        return substr($firstSubStr, 0, $endCharCount);
    } else {
        return '';
    }
}