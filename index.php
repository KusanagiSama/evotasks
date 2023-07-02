<?php
	include "functions.php";

	isset($_GET["m"]) ? $m = $_GET["m"] : $m = 1;
	isset($_GET["adm"]) ? $adm = $_GET["adm"] : $adm = "";

	$titulo = "";
	$texto = "";
	$lista = "";
	$botoes_adm = "";

	$hoje = date("Y-m-d");
	$dia = date("j", strtotime($hoje));
	$mes = date("n", strtotime($hoje));
	$ano = date("Y", strtotime($hoje));
	$semana = date("N", strtotime($hoje));
	if ( $semana == 1 ) {
		$semana_dia = $dia;
		$semana_mes = $mes;
		$semana_ano = $ano;
	} else {
		$diferenca = $dia - ( $semana - 1 );
		if ( ( $diferenca < $dia ) && ( $diferenca >= 0 ) ) {
			$semana_dia = $diferenca;
			$semana_mes = $mes;
			$semana_ano = $ano;
		} else {
			$semana_antes =  new DateTime(date("Y-m-d"));
			$semana_antes->sub(new DateInterval("P" . ( $semana - 1 ) . "D"));
			$semana_dia = $semana_antes->format("d");
			$semana_mes = $semana_antes->format("m");
			$semana_ano = $semana_antes->format("Y");
		}
	}
	$semana_inicio = $semana_ano . "-" . substr("0" . $semana_mes, -2) . "-" . substr("0" . $semana_dia, -2);
	$semana_fim = date_create($semana_inicio);
	date_add($semana_fim,date_interval_create_from_date_string("6 days"));
	$semana_fim = date_format($semana_fim,"Y-m-d");

	$conexao = new mysql_conexao();
	$conexao->conectar();

	switch ( $m ) {
		case 2:
			$titulo = "Suas tarefas diárias!";
			$texto = "Confira suas atividades diárias e clique para informar se a completou ou não durante o dia.";
			$tabela = $conexao->tabela("SELECT * FROM ET_ATIVIDADES A, ET_ATIVIDADES_DIAS D WHERE A.COD_ATIVIDADE = D.COD_ATIVIDADE AND DIA_ATIVIDADE_DIA = '" . $hoje . "' AND STS_ATIVIDADE = 'A' ORDER BY DIA_ATIVIDADE_DIA;");
			while ( $linha = $tabela->fetch_assoc() ) {
				if ( $linha["SIT_ATIVIDADE_DIA"] == "" ) {
					$status = " nova";
					if ( $adm != 1 ) {
						$botao = "<div class='botao' onclick=\"alertar(" . $linha["COD_ATIVIDADE_DIA"] . ");\">Completar</div>";
					} else {
						$botao = "";
					}
					$botoes_adm = "";
				} else {
					if ( $linha["SIT_ATIVIDADE_DIA"] == "S" ) {
						$linha["VAL_ATIVIDADE_DIA"] == "S" ? $status = " verificada" : $status = " completa";
					} else {
						$status = " incompleta";
					}
					if ( $adm == 1 && $linha["VAL_ATIVIDADE_DIA"] == "" ) $botoes_adm = "<div class='botao verde' onclick=\"window.open('validar.php?c=" . $linha["COD_ATIVIDADE_DIA"] . "&s=1&adm=1', '_self');\">Validar</div><div class='botao vermelho' onclick=\"window.open('validar.php?c=" . $linha["COD_ATIVIDADE_DIA"] . "&s=0&adm=1', '_self');\">Invalidar</div>";
					$botao = "";
				}
				$lista .= "<div class='atividade" . $status . "'><div class='titulo'>" . $linha["NOM_ATIVIDADE"] . "</div><p>" . $linha["DSC_ATIVIDADE"] . "</p>" . $botao . $botoes_adm . "</div>";
			}
		break;
		case 3:
			$titulo = "Vídeos para crescer!";
			$texto = "Assista a cada semana um novo vídeo para se conhecer mais e crescer como ser humano.";
			$tabela = $conexao->tabela("SELECT * FROM ET_SEMANAS WHERE DIA_SEMANA BETWEEN '" . $semana_inicio . "' AND '" . $semana_fim . "' AND STS_SEMANA = 'A';");
			if ( $tabela->num_rows > 0 ) {
				$linha = $tabela->fetch_assoc();
				$link_video = substr($linha["VID_LNK_SEMANA"], -11);
				$lista .= "<div class='atividade'>
					<div class='video'>
						<iframe src='https://www.youtube.com/embed/" . $link_video . "' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' allowfullscreen style='position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;'></iframe>
					</div>
					<div class='titulo'>" . $linha["VID_NOM_SEMANA"] . "</div>
					<p>" . $linha["VID_DSC_SEMANA"] . "</p>
					<p><a href='" . $linha["VID_LNK_SEMANA"] . "' target='_blank'>" . $linha["VID_LNK_SEMANA"] . "</a></p>
				</div>";
			}
		break;
		default:
			$titulo = "Suas tarefas na semana!";
			$texto = "Acompanhe seu resultado semanal e busque completar todas as atividades na semana.";
			$tabela = $conexao->tabela("SELECT * FROM ET_ATIVIDADES WHERE STS_ATIVIDADE = 'A' ORDER BY COD_ATIVIDADE;");
			while ( $linha = $tabela->fetch_assoc() ) {
				$lista .= "<div class='atividade'><div class='titulo'>" . $linha["NOM_ATIVIDADE"] . "</div><div class='caixas'>";
				$tabela2 = $conexao->tabela("SELECT * FROM ET_ATIVIDADES_DIAS WHERE COD_ATIVIDADE = " . $linha["COD_ATIVIDADE"] . " AND DIA_ATIVIDADE_DIA BETWEEN '" . $semana_inicio . "' AND '" . $semana_fim . "' AND STS_ATIVIDADE_DIA = 'A' ORDER BY DIA_ATIVIDADE_DIA;");
				while ( $linha2 = $tabela2->fetch_assoc() ) {
					$_dia = date("d/m", strtotime($linha2["DIA_ATIVIDADE_DIA"]));
					$_dia_hoje = date_create(date("Y-m-d", strtotime($hoje)));
					$_dia_atual = date_create(date("Y-m-d", strtotime($linha2["DIA_ATIVIDADE_DIA"])));
					$intervalo = date_diff($_dia_hoje, $_dia_atual);
					$sinal = $intervalo->format("%R");
					if ( $linha2["SIT_ATIVIDADE_DIA"] == "" ) {
						$sinal == "+" ? $status = " nova" : $status = " incompleta";
					} else {
						if ( $linha2["SIT_ATIVIDADE_DIA"] == "S" ) {
							$linha2["VAL_ATIVIDADE_DIA"] == "S" ? $status = " verificada" : $status = " completa";
						} else {
							$status = " incompleta";
						}
					}
					$lista .= "<div class='caixa" . $status . "'><div class='dia'>" . $_dia . "</div></div>";
				}
				$lista .= "</div></div>";
			}
		break;
	}

	$conexao->fechar();
?>
<html>
<head>
	<?php montar_cabecalho("", ""); ?>
</head>
<body onload="iniciar();">

	<?php montar_topo($m, $adm); ?>

	<section class='conteudo limite'>
		<h1><?php echo $titulo; ?></h1>
		<p><?php echo $texto; ?></p>
		<div class='lista'>
			<?php echo $lista; ?>
		</div>
	</section>

	<?php montar_rodape(); ?>

	<div class='total cortina' onclick="alertar_fechar();"></div>
	<div class='alerta transx neonmagenta transicao'>
		<form id='frmatividade' name='frmatividade' action='completar.php' method='post' class='none'>
			<input id='frmatv' type='hidden' name='atividade' value='0'>
			<input id='frmsts' type='hidden' name='status' value='N'>
		</form>
		<p>Você completou esta atividade?</p>
		<div class='botao' onclick="$('#frmsts').val('S'); document.frmatividade.submit();">Sim</div>
		<div class='botao' onclick="$('#frmsts').val('N'); document.frmatividade.submit();">Não</div>
	</div>

</body>
</html>
