<?php
	include "functions.php";

	isset($_GET["adm"]) ? $adm = $_GET["adm"] : $adm = 0;
	isset($_GET["c"]) ? $atividade = $_GET["c"] : $atividade = 0;
	isset($_GET["s"]) ? $status = $_GET["s"] : $status = 0;

	if ( $adm ) {
		$conexao = new mysql_conexao();
		$conexao->conectar();

		if ( $status ) {
			$conexao->executar("UPDATE ET_ATIVIDADES_DIAS SET VAL_ATIVIDADE_DIA = 'S' WHERE COD_ATIVIDADE_DIA = " . $atividade . ";");
		} else {
			$conexao->executar("UPDATE ET_ATIVIDADES_DIAS SET VAL_ATIVIDADE_DIA = 'N', SIT_ATIVIDADE_DIA = 'N' WHERE COD_ATIVIDADE_DIA = " . $atividade . ";");
		}

		$conexao->fechar();
	}

	header("Location: .?m=2&adm=" . $adm);
?>