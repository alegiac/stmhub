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
  		html>body .scrambled li { height: 1.5em; line-height: 1.2em; }
  			.ui-state-highlight { height: 1.5em; line-height: 1.2em; }
  	</style>
{/block}

{block name="main"}
	<section id="content">
	    <div class="container">
	    	{if $enableMessage eq true}
			 	<div class="col-xs-12 card">
					<div class="card-header bgm-purple">
			        	<h2>NOTIFICA</h2>
	        		</div>
	        
			        <div class="card-body bgm-white">
	    		        <p class="lead">{$message}</p>
	        		</div>
	    		</div>
			{/if}

            <div class="col-sm-9">
            	<!-- Content of questions -->
	        	{if $enableMessage eq true}
	        		<div class="card bgm-amber">
						<div class="card-header c-white">
							<h2>Notifica:</h2>
                            <ul class="actions">
                                <li class="dropdown">
                                    <a href="" data-toggle="dropdown" aria-expanded="false">
                                        <i class="md md-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="">Chiudi</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
						</div>
						<div class="card-body card-padding c-white">
                        	{$message} 
						</div>
					</div>
	        	{/if}
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
	            				<strong>{$firstName} {$lastName}</strong>
	            				<p style="color:light-grey;">{$courseName}</p>
	            			</div>
	            		</div>
	            		<div class="col-xs-4">
	            			<div class="pv-body">
									<p>
										<small style="color:light-grey;">Punti</small>:  <strong style="color:black;">{$points}</strong><br>
										<small style="color:light-grey;">Scadenza</small>:  <strong style="color:black;">{$expectedEndDateShort}</strong><br>
										<small style="color:light-grey;">Sessione</small>:  <strong style="color:black;">{$sessionIndex}</strong><br>
										<small style="color:light-grey;">Domanda</small>:  <strong style="color:black;">{$actualQuestion}/{$totalQuestion}</strong><br>
										<small style="color:light-grey;">In sessione da</small>: <strong style="color:black;">{$minInSession}</strong>
									</p>
	                		</div>
	                	</div>
	                	<div class="col-xs-4">
	                		<div class="pv-header"></div>
	            			<div class="pv-body">
	            				{$examListShort}
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
                        <div class="col-sm-4"><center><br><small>Scadenza</small><br><h4>{$expectedEndDate}</h4><br></center></div>
                        <div class="col-sm-4"><center><br><small>Sessione</small><br><h4>{$sessionIndex}</h4><br></center></div>
                        <div class="col-sm-4"><center><br><small>Domanda</small><br><h4>{$actualQuestion}/{$totalQuestion}</h4><br></center></div>
                        </div>
                        <div class="mini-charts-item bgm-cyan">
							<div class="clearfix">
	                    		<div class="count">
									<small>Punti</small><h2>{$points}</h2><hr>
	                        		<small>Posizione</small><h2>{$position}</h2>
	                        		
	                        		{if $hasPrize eq 1}
	                        			<hr>
	                        			<small>Premio</small><h2>{$prizeName}</h2>
	                        		{/if}
	                    		</div>
	                		</div>
						</div>
						In questa sessione da<br>
						{$minInSession}
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
	
		    	function disableBack() {window.history.forward()}

    			window.onload = disableBack();
    			window.onpageshow = function (evt) {if (evt.persisted) disableBack()}
	
				$('.scrambled').sortable(
					{
						placeholder: "ui-state-highlight",
					}
				);
				
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
  								// Sbagliato
  								case 0:
  									if (tryagain == 1) {
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
  								// Corretto:
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
					
						// Gestione cambio di colore sulla riga del tempo rimanente
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
