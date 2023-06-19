var _w = 0;
var _h = 0;
var _t0 = 0;
var _t1 = 0;
var _sts = 0;
var _ini = 0;
var _fim = 0;
var _pre = 0;
var _atu = 0;
var _tot = 0;
var _cnt = 0;
var _vlr = 0;

$(document).ready(function(){
	doResize();
	$(window).on("resize", doResize);
});

function doResize() {
	_w = $(window).width();
	_h = $(window).height();
}

$(window).scroll(function(e){
	stp = $(this).scrollTop();
});

function iniciar() {
	$(".cortina").animate({opacity: 0}, 400, function() { $(".cortina").hide(); } );
}

function mover() {
	$("html,body").animate({scrollTop: 0}, 400);
}

function mover_indice(i) {
	$("html,body").animate({scrollTop: $("#" + i).offset().top - 220}, 400);
}

function alertar(c) {
	$(".cortina").addClass("escura");
	$(".cortina").css({"opacity": "0", "display": "block"});
	$(".cortina").animate({opacity: 1}, 400);
	$("#frmatv").val(c);
	$(".alerta").show();
	setTimeout("$('.alerta').addClass('ativa');", 400);
}

function alertar_fechar() {
	$(".alerta").removeClass("ativa");
	setTimeout("$('.alerta').hide(); $('.cortina').animate({opacity: 0}, 400, function(){ $('.cortina').removeClass('escura'); $('.cortina').hide(); });", 600);
}
