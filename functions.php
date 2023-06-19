<?php

//session_save_path('/sessions');
//ini_set('session.gc_probability', 1);
session_start();

// Mostrar erros online
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

// Emulate register_globals off
function unregister_GLOBALS()
{
	if (!ini_get('register_globals')) {
		return;
	}

	// Might want to change this perhaps to a nicer error
	if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
		die('GLOBALS overwrite attempt detected');
	}

	// Variables that shouldn't be unset
	$noUnset = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');

	$input = array_merge($_GET, $_POST,  $_COOKIE, $_SERVER,  $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());

	foreach ($input as $k => $v) {
		if (!in_array($k, $noUnset) && isset($GLOBALS[$k])) {
			unset($GLOBALS[$k]);
		}
	}
}

unregister_GLOBALS();

//mysql_query("SET NAMES utf8");
//mb_internal_encoding("UTF-8");

date_default_timezone_set('America/Sao_Paulo');
//setlocale(LC_ALL, 'pt_BR');
//setlocale(LC_ALL, 'portuguese');
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');

// Extends DOMDocument Class
class ExtDOMDocument extends DOMDocument {
	function getElementById($id) {
		//thanks to: http://www.php.net/manual/en/domdocument.getelementbyid.php#96500
		$xpath = new DOMXPath($this);
		return $xpath->query("//*[@id='$id']")->item(0);
	}
	function output() {
		// thanks to: http://www.php.net/manual/en/domdocument.savehtml.php#85165
		$output = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $this->saveHTML()));
		return trim($output);
	}
}

$c_servidor = "elysiumgames0.mysql.dbaas.com.br";
$c_bancodedados = "elysiumgames0";
$c_usuario = "elysiumgames0";
$c_senha = "eG25-Sql03#20";
$c_email = "suporte@evovedigital.com.br";
$c_empresa = "EvoTasks";
$c_dominio = "www.evovedigital.com.br/projetos/evotasks";
$c_descricao = "EvoTasks é um aplicativo para organização e conferência de tarefas diárias e semanais";
$c_palavras = "EvoTasks, tarefas, organização, conferência, diária, semanal, ipatinga, minas gerais, brasil";
$c_responsive = 1;
$c_instagram = "";
$c_facebook = "";
$c_twitter = "";
$c_youtube = "";
$c_gplus = "";
$c_erros = "";
$c_sen_adm = "";
$c_ver_num = "1.0.60-PHP";
$c_ver_dat = "28/11/2016";

class mysql_conexao {

	var $conexao;

	function conectar() {
		global $c_servidor, $c_usuario, $c_senha, $c_bancodedados;
		try {
			if ( $con = mysqli_connect($c_servidor, $c_usuario, $c_senha, $c_bancodedados) ) {
				if ( $con->connect_errno ) {
					erros(1, "[" . $con->connect_errno . "] " . $con->connect_error);
				} else {
					$con->set_charset("utf8");
					$this->conexao = $con;
				}
				return $this->conexao;
			} else {
				throw new Exception("[HY000/2002] Uma tentativa de conexão falhou porque o servidor de banco de dados não respondeu ou está inacessível.", 1);
			}
		} catch (Exception $e) {
			erros(1, $e);
		}
	}

	function fechar() {
		mysqli_close($this->conexao);
	}

	function executar($sql) {
		$conexao = $this->conexao;
		if ( !$conexao->query($sql) ) {
			erros(2, "[" . $conexao->errno . "] " . $conexao->error);
		}
	}

	function tabela($sql) {
		$conexao = $this->conexao;
		$res = $conexao->query($sql);
		if ( !$res ) {
			erros(2, "[" . $conexao->errno . "] " . $conexao->error);
		} else {
			return $res;
		}
	}

	function valor($tab, $col, $cnd) {
		$conexao = $this->conexao;
		if ( strlen($cnd) > 0 ) {
			$res = $conexao->query("SELECT " . strtoupper($col) . " AS CAMPO FROM " . strtoupper($tab) . " WHERE " . strtoupper($cnd) . ";");
		} else {
			$res = $conexao->query("SELECT " . strtoupper($col) . " AS CAMPO FROM " . strtoupper($tab) . ";");
		}
		if ( !$res ) {
			erros(2, "[" . $conexao->errno . "] " . $conexao->error);
		} else {
			if ( $res->num_rows > 0 ) {
				$res->data_seek(0);
				$lin = $res->fetch_assoc();
				return $lin["CAMPO"];
			} else {
				return "";
			}
		}
	}

}

function erros($c, $msqle) {
	switch ( $c ) {
		case 1:
			$erro = "O site não conseguiu estabelecer uma conexão com o banco de dados. Tente acessá-lo dentro de alguns instantes.<br>Código do erro: " . $msqle;
			break;
		case 2:
			$erro = "Ocorreu um erro inesperado em sua solicitação. Tente novamente dentro de alguns instantes.<br>Código do erro: " . $msqle;
			break;
		case 3:
			$erro = "Suas informações estão incorretas ou você não possui acesso ao site.";
			break;
		case 4:
			$erro = "O site encontra-se em manutenção neste momento. Tente acessá-lo dentro de alguns instantes.";
			break;
		case 5:
			$erro = "Ocoreu um erro ao verificar suas informações de acesso ao site.";
			break;
	}
	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
		<script>
			var _html = \"<div style='position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: #f0f2f4;'><div style='position: absolute; top: 30%; left: 50%; max-width: 520px; padding: 10px; background-color: rgba(194, 194, 194, 0.2); -webkit-transform: translateX(-50%); -moz-transform: translateX(-50%); -o-transform: translateX(-50%); -ms-transform: translateX(-50%); transform: translateX(-50%);'><div style='min-height: 100px; background-color: white; background-image: url(img/ico-erros.png); background-repeat: no-repeat; background-position: 20px 20px;'><div style='padding: 20px 20px 20px 104px; font-size: 0.88em; color: #808080; text-transform: uppercase; line-height: 1.4em;'>" . $erro . "</div></div></div></div>\";
			window.onload = function(){ document.body.innerHTML+= _html };
		</script>";
}

function verificar_ie() {
	if(strpos($_SERVER['HTTP_USER_AGENT'],"MSIE")){
		$msie = substr($_SERVER['HTTP_USER_AGENT'],strpos($_SERVER['HTTP_USER_AGENT'],"MSIE")+4,2);
		if (strpos($msie,".") > 0)
			$msie = substr($msie,0,1);
		if (is_numeric($msie)){
			if ((int)$msie < 8)
				echo "<script language='javascript' type='text/javascript'>alert('Seu navegador de internet está desatualizado e pode exibir as páginas de forma incorreta e até mesmo com erros.\nAtualize seu navegador para a versão 8 ou mais novo, ou utilize outro navegador de internet mais atual.'); window.open('http://windows.microsoft.com/pt-BR/internet-explorer/download-ie','_self');</script>";
		}
	}
}

function verificar_login() {
	if ( isset($_REQUEST['_SESSION']) ) {
		header("Location: sair.php");
		exit();
	}
	if ( !isset($_SESSION["cf-codigo"]) ) {
		if ( !isset($_COOKIE["cf-codigo"]) ) {
			header("Location: sair.php");
			exit();
		} else {
			$_SESSION['cf-codigo'] = $_COOKIE["cf-codigo"];
			$_SESSION['cf-usuario'] = $_COOKIE["cf-usuario"];
		}
	}
}

function contar_acessos() {
	global $c_servidor, $c_usuario, $c_senha, $c_bancodedados;
	$datacad = date("Y-m-d");
	$conexao = mysql_connect($c_servidor,$c_usuario,$c_senha) or die(mysql_error());
	$banco = mysql_select_db($c_bancodedados) or die(mysql_error());
	$sql = "INSERT INTO TB_ACESSOS (NUM_ACESSO, DAT_ACESSO) VALUES (1,'" . $datacad . "');";
	$sql2 = "UPDATE TB_ACESSOS SET NUM_ACESSO = NUM_ACESSO+1 WHERE DAT_ACESSO = '" . $datacad . "';";
	$comando = mysql_query("SELECT MAX(DAT_ACESSO) AS DATA FROM TB_ACESSOS;") or die(mysql_error());
	while($tabela = mysql_fetch_array($comando)) {
		if (strlen($tabela['DATA']) > 0) {
			$datatab = date_create($tabela['DATA']);
			$datacad = date_create($datacad);
			$datadif = date_diff($datatab,$datacad);
			if ($datadif->format("%d") == "0") {
				$sql = $sql2;
			}
		}
	}
	mysql_query($sql) or die(mysql_error());
}

function montar_cabecalho($pagina, $link) {
	global $c_empresa, $c_descricao, $c_palavras, $c_dominio, $c_responsive;
	if ( strlen($pagina) > 0 ) {
		$og_tit = $c_empresa . " - " . $pagina;
	} else {
		$og_tit = $c_empresa;
	}
	if ( strlen($link) > 0 ) {
		$og_url = "http://" . $c_dominio . "/" . $link;
	} else {
		$og_url = "http://" . $c_dominio;
	}
	$og_img = "http://" . $c_dominio . "/img/social.jpg";
	$og_dsc = $c_descricao;
	$og_loc = "pt_BR";
	//$font = "Montserrat:wght@400;500;600;700&display=swap";
	$font = "Montserrat:wght@400;600";
	$place = "Ipatinga-MG";
	$region = "MG-BR";
	$robots = "noindex, nofollow";
	$cor = "923ab0";

	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<title>" . $og_tit . "</title>";
	echo "<meta name='Description' content='" . $og_dsc . "'>";
	echo "<meta name='Keywords' content='" . $c_palavras . "'>";
	echo "<meta name='author' content='KusanagiSama'>";
	echo "<meta name='language' content='pt-br'>";
	echo "<meta name='geo.placename' content='" . $place . "'>";
	echo "<meta name='geo.region' content='" . $region . "'>";
	echo "<meta name='robots' content='" . $robots . "'>";
	echo "<meta name='rating' content='General'>";
	//echo "<meta name='revisit-after' content='7 days'>";
	echo "<meta name='theme-color' content='#" . $cor . "'></meta>";
	if ($c_responsive) echo "<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0'>";
	echo "<link rel='canonical' href='" . $og_url . "'>";
	//echo "<base href='" . $og_url . "'>";
	echo "<meta http-equiv='X-UA-Compatible' content='ie=edge'>";
	echo "<meta content='" . $og_tit . "' property='og:title'></meta>";
	echo "<meta content='website' property='og:type'></meta>";
	echo "<meta content='" . $og_img . "' property='og:image'></meta>";
	echo "<meta content='" . $og_url . "' property='og:url'></meta>";
	echo "<meta content='" . $og_tit . "' property='og:site_name'></meta>";
	echo "<meta content='" . $og_loc . "' property='og:region'></meta>";
	//echo "<meta content='' property='fb:admins'></meta>";
	echo "<meta content='" . $og_dsc . "' property='og:description'></meta>";
	echo "<meta content='summary_large_image' name='twitter:card'></meta>";
	echo "<meta content='" . $og_img . "' name='twitter:image'></meta>";
	echo "<meta content='" . $og_tit . "' name='twitter:title'></meta>";
	echo "<meta content='" . $og_dsc . "' name='twitter:description'></meta>";
	echo "<link rel='stylesheet' href='css/reset.css' type='text/css'>";
	//echo "<link rel='stylesheet' href='css/hamburgers.min.css'>"; // Hamburgers mobile menu
	echo "<link rel='stylesheet' href='css/styles.css?v=1' type='text/css'>";
	//echo "<link rel='shortcut icon' href='img/favicon.ico'>";
	echo "<link rel='icon' href='img/ico-16.png' sizes='16x16'>";
	echo "<link rel='icon' href='img/ico-32.png' sizes='32x32'>";
	echo "<link rel='icon' href='img/ico-48.png' sizes='48x48'>";
	echo "<link rel='icon' href='img/ico-64.png' sizes='64x64'>";
	echo "<link rel='icon' href='img/ico-80.png' sizes='80x80'>";
	echo "<link rel='icon' href='img/ico-96.png' sizes='96x96'>";
	echo "<link rel='icon' href='img/ico-128.png' sizes='128x128'>";
	echo "<link rel='icon' href='img/ico-144.png' sizes='144x144'>";
	echo "<link rel='icon' href='img/ico-192.png' sizes='192x192'>";
	echo "<link rel='icon' href='img/ico-256.png' sizes='256x256'>";
	echo "<link rel='icon' href='img/ico-512.png' sizes='512x512'>";
	echo "<link rel='preconnect' href='https://fonts.googleapis.com'>";
	echo "<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>";
	echo "<link href='https://fonts.googleapis.com/css2?family=" . $font . "&display=swap' rel='stylesheet'>";
	echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js' type='text/javascript'></script>";
	echo "<!--[if lt IE 9]><script src='//html5shiv.googlecode.com/svn/trunk/html5.js'></script><![endif]-->";
	echo "<script src='js/funcoes.js?v=1' type='text/javascript'></script>";
}

function montar_topo($m, $adm = "") {
	$mnu = array ("", "", "");
	$mnu[$m-1] = " ativo";
	echo "<header class='limite'>
		<div class='menu" . $mnu[0] . "' onclick=\"window.open('.?m=1&adm=" . $adm . "', '_self');\"><img src='img/ico-semanas.png' alt='Semanas' class='transxy'></div>
		<div class='menu" . $mnu[1] . "' onclick=\"window.open('.?m=2&adm=" . $adm . "', '_self');\"><img src='img/ico-tarefas.png' alt='Tarefas' class='transxy' style='margin-top: -2px;'></div>
		<div class='menu" . $mnu[2] . "' onclick=\"window.open('.?m=3&adm=" . $adm . "', '_self');\"><img src='img/ico-videos.png' alt='Vídeos' class='transxy' style='margin-top: 3px;'></div>
	</header>";
}

function montar_rodape() {
	echo "<footer class='limite transx'>
		&copy; 2023 Evolve Digital
	</footer>";
}

function converter_texto($t) {
	return nl2br($t);
}

function converter_paragrafo($t) {
	return "<p>" . str_replace("\r\n","</p><p>",$t) . "</p>";
}

function verificar_injection($t) {
	$t = strip_tags($t);
	$t = addslashes($t);
	$t = trim($t);
	return $t;
}

function formatar_semana($s) {
	switch ($s) {
		case 1 :
			return "Segunda-feira";
		break;
		case 2 :
			return "Terça-feira";
		break;
		case 3 :
			return "Quarta-feira";
		break;
		case 4 :
			return "Quinta-feira";
		break;
		case 5 :
			return "Sexta-feira";
		break;
		case 6 :
			return "Sábado";
		break;
		case 7 :
			return "Domingo";
		break;
		default :
			return "";
		break;
	}
}

function formatar_mes($m) {
	switch ($m) {
		case 1 :
		case "January" :
			return "Janeiro";
		break;
		case 2 :
		case "February" :
			return "Fevereiro";
		break;
		case 3 :
		case "March" :
			return "Março";
		break;
		case 4 :
		case "April" :
			return "Abril";
		break;
		case 5 :
		case "May" :
			return "Maio";
		break;
		case 6 :
		case "June" :
			return "Junho";
		break;
		case 7 :
		case "July" :
			return "Julho";
		break;
		case 8 :
		case "August" :
			return "Agosto";
		break;
		case 9 :
		case "September" :
			return "Setembro";
		break;
		case 10 :
		case "October" :
			return "Outubro";
		break;
		case 11 :
		case "November" :
			return "Novembro";
		break;
		case 12 :
		case "December" :
			return "Dezembro";
		break;
		default :
			return $m;
		break;
	}
}

function mes_numero($m) {
	switch ($m) {
		case "jan":
			return "01";
		break;
		case "fev":
			return "02";
		break;
		case "mar":
			return "03";
		break;
		case "abr":
			return "04";
		break;
		case "mai":
			return "05";
		break;
		case "jun":
			return "06";
		break;
		case "jul":
			return "07";
		break;
		case "ago":
			return "08";
		break;
		case "set":
			return "09";
		break;
		case "out":
			return "10";
		break;
		case "nov":
			return "11";
		break;
		case "dez":
			return "12";
		break;
		default :
			return $m;
		break;
	}
}

function ano_numero($a) {
	return strlen($a) == 2 ? "20" . $a : $a;
}

function data_mysql($d) {
	$d = implode("-", array_reverse(explode("/", $d)));
	return $d;
}

function data_mysql_php($d) {
	$d = implode("/", array_reverse(explode("-", $d)));
	return $d;
}

function valor_mysql($v) {
	$v = htmlentities($v, null, "utf-8");
	//$v = str_replace("&nbsp;", "", $v);
	//$v = str_replace(" ", "", $v);
	//$v = str_replace("R$", "", $v);
	//$v = str_replace("%", "", $v);
	$v = preg_replace("/[^0-9.,-]/", "", $v);
	$v = str_replace(".", "", $v);
	$v = str_replace(",", ".", $v);
	//$v = html_entity_decode($v);
	return $v;
}

function valor_mysql_php($v) {
	$v = str_replace(".", ",", $v);
	return $v;
}

function valor_brasil($v) {
	$v = number_format($v, 2, ',', '.');
	return $v;
}

function tirarAcentos ($string) {
	return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç)/", "/(Ç)/"), explode(" ", "a A e E i I o O u U n N c C"), $string);
}

function imageCenterCopyResampled($spath, $dpath, $w, $h) {
	$src=imagecreatefromjpeg($spath);
	if (!$src) {
		return false;
	} else {
		$sw=imagesx($src);
		$sh=imagesy($src);
		//list($sw, $sh) = getimagesize($src);

		// Determina a escala e centraliza as coordenadas da imagem
		$scaleX = (float)$sw / $w;
		$scaleY = (float)$sh / $h;
		$scale = min($scaleX, $scaleY);
		$cw = $scale * $w;
		$ch = $scale * $h;

		if ($cw < $sw) {
			$cx=(int)(($sw-$cw)/2);
		} else {
			$cx=0;
		}

		if ($ch < $sh) {
			$cy=(int)(($sh-$ch)/2);
		} else {
			$cy=0;
		}

		// Comente a linha abaixo caso queira centrarlizar a imagem no eixo Y
		$cy=0;

		$thumb=imagecreatetruecolor($w, $h);
		imagecopyresampled($thumb, $src, 0, 0, $cx, $cy, $w, $h, $cw, $ch);
		imagejpeg($thumb, $dpath, 80);
	return true;
	}
}

function imageResizeCopyResampled($spath, $dpath, $max) {
	$src=imagecreatefromjpeg($spath);
	if (!$src) {
		return false;
	} else {
		$srcw=imagesx($src);
		$srch=imagesy($src);
		if ($srcw<$srch){
			$height=$max;
			$width=floor($srcw*$height/$srch);
		} else {
			$width=$max;
			$height=floor($srch*$width/$srcw);
		}
		if ($width>$srcw && $height>$srch){
			$width=$srcw;
			$height=$srch;
		}
		$thumb=imagecreatetruecolor($width, $height);
		imagecopyresampled($thumb, $src, 0, 0, 0, 0, $width, $height, imagesx($src), imagesy($src));
		imagedestroy($thumb);
	}
}

function crawl_page($url, $passos = 5) {
	static $vista = array();
	if ( isset($vista[$url]) || $passos === 0 ) return false;
	$vista[$url] = true;
	$dom = new DOMDocument("1.0");
	@$dom->loadHTMLFile($url);
	$links = $dom->getElementsByTagName("a");
	foreach ($links as $link) {
		$href = $link->getAttribute("href");
		if ( strpos($href, "http") !== 0 ) {
			$caminho = "/" . ltrim($href, "/");
			if ( extension_loaded("http") ) {
				$href = http_build_url($url, array("path" => $caminho));
			} else {
				$partes = parse_url($url);
				$href = $partes["scheme"] . "://";
				if ( isset($partes["user"]) && isset($partes["pass"]) ) {
					$href .= $partes["user"] . ":" . $partes["pass"] . "@";
				}
				$href .= $partes["host"];
				if ( isset($partes["port"]) ) {
					$href .= ":" . $partes["port"];
				}
				$href .= dirname($partes["path"], 1) . $caminho;
			}
		}
		crawl_page($href, $passos - 1);
	}
	//return $dom->saveHTML();
	return $dom;
}

class api_comunicacao {
	public function enviar($cabecalho = array(), $conteudoAEnviar, $url, $tpRequisicao) {
		try {
			//Inicializa cURL para uma URL.
			$ch = curl_init($url);
			//Marca que vai enviar por POST(1=SIM), caso tpRequisicao seja igual a "POST"
			if ( $tpRequisicao == 'POST' ) {
				curl_setopt($ch, CURLOPT_POST, 1);
				//Passa o conteúdo para o campo de envio por POST
				curl_setopt($ch, CURLOPT_POSTFIELDS, $conteudoAEnviar);
			}
			//Se foi passado como parâmetro, adiciona o cabeçalho à requisição
			if ( !empty($cabecalho) ) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, $cabecalho);
			}
			//Marca que vai receber string
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			/*
			Caso você não receba retorno da API, pode estar com problema de SSL.
			Remova o comentário da linha abaixo para desabilitar a verificação.
			*/
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//Inicia a conexão
			$resposta = curl_exec($ch);
			//Fecha a conexão
			curl_close($ch);
			} catch (Exception $e) {
			return $e->getMessage();
		}
		return $resposta;
	}
}

?>