{extends "../../_common/base.tpl"}

{block name="custom_css"}
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<style type="text/css">
		.foot {
			position : absolute;
			bottom : 0;
			height : 40px;
			margin-top : 40px;
		}
		.scrambled { 
			list-style-type: none; 
			margin: 0; 
			padding: 0; 
			width: 60%; 
		}
		.scrambled li { 
			margin: 0 5px 5px 5px; 
			padding: 5px; 
			font-size: 2em;
			text-transform: uppercase; 
			height: 1.5em; 
		}
		
		html>body .scrambled li { 
			height: 1.5em; line-height: 1.2em; 
		}
		.ui-state-highlight { 
			height: 1.5em; line-height: 1.2em; 
		}
		
		span.position {
		  font-size: 800%;
	      font-weight: bold;
		  color: white;
		  vertical-align: middle;
		
		}
		.competition-podium {
		  height: 320px;
		  max-width: 450px;
		  min-width: 200px;
		  margin:20px;
		  background:#FFFFFF;
		}
		.competition-podium .podium-block {
		  width: 150px;
		  text-align: left;
		  display: inline-block;
		  position: absolute;
		  bottom: 0;
		}
		.competition-podium .podium-block .place {
		  font-size: 1em;
		  font-weight: bold;
		  font-family: Arial, Helvetica, sans-serif;
		  line-height: 15px;
		}
		.competition-podium .podium-block .sum {
		  font-size: 1.2em;
		  font-weight: bold;
		  font-family: Arial, Helvetica, sans-serif;
		  line-height: 70px;
		}
		
		.competition-podium .podium-block.bronze {
		  color: #e6d6bf;
		  left: 0px;
		}
		.competition-podium .podium-block.bronze .podium {
		  background: #e6d6bf;
		  height: 0px;
		}
		.competition-podium .podium-block.bronze .name {
		  color: #856e4e;
		}
		
		.competition-podium .podium-block.gold {
		  color: #E87E04;
		  left: 150px;
		}
		.competition-podium .podium-block.gold .podium {
		  background: #ead679;
		  height: 0px;
		}
		.competition-podium .podium-block.gold .name {
		  color: #856e4e;
		  text-align:center
		}
		
		.competition-podium .podium-block.silver {
		  color: #cbd9de;
		  left: 300px;
		}
		.competition-podium .podium-block.silver .podium {
		  background: #cbd9de;
		  height: 0px;
		}
		.competition-podium .podium-block.silver .name {
		  color: #856e4e;
		  text-align:center
		}
		.podium {
		  display: table;
		  width: 100%;
		  border-top-left-radius: 4px;
		  border-top-right-radius: 4px;
		}
		.podium > * {
		  display: table-cell;
		  vertical-align: middle;
		  text-align: center;
		  color: #4D4D4D;
		  font-size: 1.5em;
		}		
	</style>
{/block}

{block name="main"}
	<section id="content">
		<div class="container">
		
		<!-- Modal RANKING -->
					<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Classifica</h3>
								</div>
								<div class="modal-body">
      								<center>
								    	<div class="competition-podium">
								  			<div class="podium-block bronze">	
												<div class="name">
													<center>
														<img style="max-width:120px;" src="{$bronzePrizeUrl}"/>
														<br>
														<h4>{$bronzePrizeTitle}</h4>
													</center>
												</div>
								                <div class="podium">
								                	<span class="position">3</span>
									                <span>{$bronzeFirstName}<br>{$bronzePoints}</span>
									            </div>
											</div>
											<div class="podium-block gold">	
												<div class="name">
													<center>
														<img style="max-width:120px;" src="{$goldPrizeUrl}"/>
														<br>
														<h4>{$goldPrizeTitle}</h4>
													</center>
												</div>
												<div class="podium">
													<span class="position">1</span>
	                        						<span>{$goldFirstName}<br>{$goldPoints}</span>
                        						</div>
											</div>
											<div class="podium-block silver">	
												<div class="name">
													<center>
														<img style="max-width:120px;" src="{$silverPrizeUrl}"/>
														<br>
														<h4>{$silverPrizeTitle}</h4>
													</center>
												</div>
												<div class="podium">
													<span class="position">2</span>
	                        						<span>{$silverFirstName}<br>{$silverPoints}</span>
	                    						</div>
											</div>
										</div>
      								</center>
      								<hr>
      								<center>
      									<div class="col-xs-10 col-xs-offset-1">
      										{$otherPrices}
      									</div>
  									</center>
  								</div>
								<div class="modal-footer">
								</div>
							</div>
						</div>
					</div>
		
			<div class="col-sm-9">
				<!-- Content of questions -->
				<div class="card">
					<img src="/static/assets/img/TRAINtoACTION.png" style="margin-top: 5px; margin-left: 5px;"/>
					{if $remainingTime lte -1}
					{else}
						<div class="card-header">
							<div class="col-sm-6 pull-left">
							</div>
							<div class="col-sm-6 pull-right">
								<div class="pull-left" id="countmesg">
								</div><br><br>
								<div class="media">
									<div class="media-body">
										<div class="progress">
											<div id="timerbar" class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="{$remainingTime}" style="width: {$remainingTime}%">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					{/if}
					<br>
					<div class="card-body card-padding">
						<div class="row">
							<div class="col-sm-12">
								{$media}
								<hr>
								<center>
									<h2>{$itemQuestion}</h2>
								</center>
								<hr>
							</div>
						</div>
						<div class="row">
							<center>
							{$this->scramble}
						 	{$this->form($form)}
						 	</center>
						</div>
					</div>
				</div>
				<div id="base_mobile"  class="row visible-xs .visible-xs-block card">
					<div class="pv-card clearfix">
						<div class="col-xs-4">
							<div class="pv-body">
								<p>
									<h4>{$firstName} {$lastName}</h4>
									<span style="color:light-grey;">{$courseName}</span><br>
									<span style="color:light-grey;">{$examName}</span><br>
								</p>
							</div>
						</div>
						<div class="col-xs-4" style="text-align:center;">
							<div class="pv-body">
								<br>
								<p>
									<br>
									<small style="color:light-grey;">Sessione</small>:  <strong style="color:black;">{$sessionIndex}</strong><br>
									<small style="color:light-grey;">Domanda</small>:  <strong style="color:black;">{$actualQuestion}/{$totalQuestion}</strong><br>
									<small style="color:light-grey;">Scadenza</small>:  <strong style="color:black;">{$expectedEndDateShort}</strong><br>
								</p>
							</div>
						</div>
						<div class="col-xs-4" style="text-align:right;">
							<div class="pv-body">
								<br>
								<p>
									<br>
									<small style="color:light-grey;">Tempo</small>: <strong style="color:black;">{$minInSession}</strong><br>
									<small style="color:light-grey;">Punti</small>:  <strong style="color:black;">{$points}</strong><br>
									{if $showClassification eq 1}
										<a href="#myModal" role="button" data-toggle="modal">
											<small style="color:light-grey;">Classifica</small>:  <strong style="color:black;">{$position}</strong><br>
										</a>
									{/if}
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3 .visible-xs-block, hidden-xs">
				<!-- Profile view -->
				<div class="card profile-view">
					<div class="pv-header">
					</div>
					<div class="pv-body" style="margin-top: 30px;">
						<h2>{$firstName} {$lastName}</h2>
						<div class="row">
							<div class="col-xs-12 col-md-4"><center><br><small>Scadenza</small><br><h4>{$expectedEndDate}</h4><br></center></div>
							<div class="col-xs-12 col-md-4"><center><br><small>Sessione</small><br><h4>{$sessionIndex}</h4><br></center></div>
							<div class="col-xs-12 col-md-4"><center><br><small>Domanda</small><br><h4>{$actualQuestion}/{$totalQuestion}</h4><br></center></div>
						</div>
						<div class="row" style="background-color: #64c8ff;">
							<br>
							<div class="row">
								<div class="col-xs-12 col-md-4">
									<small style="color: white;">Punti</small><h4 style="color: white;">{$points}</h4>
								</div>
								<div class="col-xs-12 col-md-4">
									{if $showClassification eq 1}
										<a href="#myModal" role="button" data-toggle="modal">
										<small style="color: white;">Classifica</small><h4 style="color: white;">{$position}°</h4></a>
									{/if}
								</div>
								<div class="col-xs-12 col-md-4">
									<small style="color: white;">Tempo</small><h4 style="color: white;">{$minInSession}</h4>
								</div>
							</div>
							<br>
						</div>
					</div>
				</div>
				<div class="card" style="margin-top:-25px;">
				<div class="pv-body">
					<center><br><h4>{$courseName}</h4><hr></center>
						{$examList}
					</div>
				</div>
			</div>
		</div>
	</section>
{/block}

{block name="custom_js"}
	<script src="/static/assets/js/jquery.sortable.min.js"></script>
	<script type="text/javascript">
		
		var selectedOption = "";
		
		{literal}
		
			$(document).ready(function() {
			
				// Handling no-back button
				function disableBack() {window.history.forward()}
				window.onload = disableBack();
				window.onpageshow = function (evt) {if (evt.persisted) disableBack()}
				
				function podiumAnimate() {
        			$('.bronze .podium').animate({
			            "height": "110px"
			        }, 1500);
			        $('.gold .podium').animate({
			            "height": "200px"
			        }, 1500);
			        $('.silver .podium').animate({
			            "height": "140px"
			        }, 1500);
			        $('.competition-container .name').delay(1000).animate({
			            "opacity": "1"
			        }, 500);
			    }
			    podiumAnimate();
				
				// Handling scrambling/reorderable items
				$('.scrambled').sortable({
					placeholder: "ui-state-highlight",
				});
				
				selectedOption = $('select :selected').val();
				if (selectedOption == "") {
					// Disabilitare il pulsante submit
					$("input[type=submit]").attr("disabled", "disabled");
				} else {
					$("input[type=submit]").removeAttr("disabled", "disabled");
				}
					
				// Gestione cambio di valore select
				$('select').change(function() {   
					selectedOption = $('select :selected').val();
					if (selectedOption == "") {
						// Disabilitare il pulsante submit
						$("input[type=submit]").attr("disabled", "disabled");
					} else {
						$("input[type=submit]").removeAttr("disabled", "disabled");
					}
				});
					
				// Gestione submit (pre pre-check)
				$(":submit").click(function()
				{
					var form_id = $(this).closest("form").attr('id');
					var ajax_post_data_value = "";
					var postdata = [];
						
					// Se la domanda  nulla, nessun check. Si va direttamente al submit
					if (form_id == "null_question") {
						ajax_post_data_value = "-1";
					// Se la domanda  di un multisubmit, si deve inviare il submit premuto
					} else if (form_id == "multisubmit_question") {
						ajax_post_data_value = $(this).attr("id");
					// D&D
					} else if (form_id == "dnd_question") {
						var scrambledData = "";
						$(".scrambled li").each(function(i,el) {
							var p = $(el).text().toLowerCase();
							scrambledData+=p+"|";
						});
						scrambledData = scrambledData.substring(0, scrambledData.length - 1);
						ajax_post_data_value = scrambledData;
					// Inserimento manuale
					} else if (form_id == "input_question") {
						ajax_post_data_value = $(this).closest(":input").val();
					// Selezione tendina
					} else {
						ajax_post_data_value = selectedOption;
					}
					$.ajax({
						type: "GET",
						url: "/exam/ajcheckanswer/"+ajax_post_data_value,
						data: ajax_post_data_value,
							success: function(data) {
							var checkResult = jQuery.parseJSON(data).result;
							var earnedPoints = jQuery.parseJSON(data).points;
							var itemAnswer = jQuery.parseJSON(data).answer;
							var tryagain = jQuery.parseJSON(data).tryagain;
							
							switch(checkResult) {
								// Wrong
								case 0:
									if (tryagain == 1) {
										// Wrong but try again possible
										swal({
											title: "",
											text: "Puoi modificare la tua risposta",
											type: "warning",
											html: true,
											showCancelButton: false,
											confirmButtonClass: "btn-lg btn-warning",
											confirmButtonText: "OK",
											closeOnConfirm: true,
											showLoaderOnConfirm: true,
										});
									} else {
										// Wrong, can't try again
										swal({
											title: "",
											text: itemAnswer,
											type: "error",
											html: true,
											showCancelButton: false,
											confirmButtonClass: "btn-lg btn-danger",
											confirmButtonText: "CONTINUA",
											closeOnConfirm: false,
											showLoaderOnConfirm: true,
										},
										function(isConfirm) {
											if (isConfirm) {
												window.location = "/exam/saveanswer/"+ajax_post_data_value;
											}
										});
										break;
									}
									break;
								// Right
								case 1:
									if (itemAnswer.length == 0) {
										window.location = "/exam/saveanswer/"+ajax_post_data_value;
									} else {
										swal({
											title: "",
											text: itemAnswer,
											type: "success",
											html: true,
											showCancelButton: false,
											confirmButtonClass: "btn-lg btn-success",
											confirmButtonText: "CONTINUA",
											closeOnConfirm: false,
											showLoaderOnConfirm: true,
										},
										function(isConfirm) {
											if (isConfirm) {
												window.location = "/exam/saveanswer/"+ajax_post_data_value;
											}
										});
									}
									break;
								// Null question:
								case 2:
									if (itemAnswer.length == 0) {
										window.location = "/exam/timeout";
									} else {
									
										swal({
											title: "",
											text: itemAnswer,
											type: "info",
											showCancelButton: false,
											confirmButtonClass: "btn-lg btn-info",
											confirmButtonText: "CONTINUA",
											closeOnConfirm: false,
											showLoaderOnConfirm: true,
										},
										function(isConfirm) {
											if (isConfirm) {
												window.location = "/exam/timeout";
											}
										});
									}
									break;
								}
							},
						});
					return false;
				});
		
				{/literal}{if $remainingTime > -1}{literal}
			
				var initial = {/literal}{$remainingTime}{literal} ;
				var delay = {/literal}{$remainingTime}{literal} ;
				var url = "/exam/timeout";
					
				function countdown() {
					setTimeout(countdown, 1000) ;
					delay --;
					if (delay < 0) {
						delay = 0;
						initial = 0;
						swal({
							title: "Timeout!",
							text: "<font size=\"5\">Hai esaurito il tempo a disposizione per la risposta</font>",
							type: "info",
							showCancelButton: false,
							confirmButtonClass: "btn-lg btn-danger",
							confirmButtonText: "Ok",
							closeOnConfirm: false,
						},
						function(isConfirm) {
							if (isConfirm) {
								window.location = url;
							}
						});
					}
				
					// On low/very low remaining time, change the bar color
					$('#countmesg').html("<h5>Tempo rimanente: </h5>"+delay+" secondi");
					$('#timerbar').attr('style','width: '+(100*delay)/initial+'%');
					if (delay/initial < 0.10) {
						$('#timerbar').removeClass('progress-bar-warning');
						$('#timerbar').addClass('progress-bar-danger');
					} else if (delay/initial < 0.40) {
						$('#timerbar').removeClass('progress-bar-success');
						$('#timerbar').addClass('progress-bar-warning');
					}
				}
				countdown();
				{/literal}{/if}{literal}
			});
		{/literal}
	</script>
{/block}