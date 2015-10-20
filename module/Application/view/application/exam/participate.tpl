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
	        		<div class="card-header">
	        			<div class="col-sm-6 pull-left">
	        			</div>
	        			<div class="col-sm-6 pull-right">
	        				{if $remainingTime eq -1}
	        				{else}
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
	        				{/if}
	        			</div>
	        		</div>
	        		<br>
	        		<div class="card-body card-padding">
	        			<div class="row">
	        				<div class="col-sm-12">
	        					{$media}
		        				<hr>
		        				<br>
		        				<br>
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
	        	<div class="visible-xs .visible-xs-block">
	        		<div class="col-xs-4 card" style="height:80px;">
	        			<div class="pv-body">
            				<h4>{$firstName} {$lastName}</h4>
            				<p style="color:light-grey;">{$courseName}<br>{$examName}</p>
            			</div>
            		</div>
            		<div class="col-xs-4 card" style="height:80px;">
            			<div class="pv-body">
            				<center>
	            				<small>Data termine sessione </small><br>
	            				<strong style="color:black;">{$expectedEndDate}</strong>
	            				<br>
	            				<p style="color:light-grey;"><small>Punteggio accumulato</small><br><strong style="color:black;">{$points}/{$maxpoints}</strong></p>
	            			</center>
                		</div>
                	</div>
                	<div class="col-xs-4 card" style="height:80px;">
            			<div class="pv-header">&nbsp;</div>
            			<div class="pv-body">
            				{$examListShort}
            			</div>
                	</div>
	        	</div>
			</div>
			<div class="col-sm-3 .visible-xs-block, hidden-xs">
				<!-- Profile view -->
                <div class="card profile-view">
                	<div class="pv-header">
                   	</div>
                            
                    <div class="pv-body">
                        <h2>{$firstName} {$lastName}</h2>
                        <small>{$email}</small>
                    </div>
                </div>
                
                <div class="card">
                	<div class="pv-body">
                		<center><br><h4>{$courseName}</h4><hr><br></center>
                		{$examList}
                	</div>
                </div>
                
                <div class="card">
                	<div class="pv-body">
                		<center><br><small>Data termine sessione</small><br><h4>{$expectedEndDate}</h4><br></center>
                	</div>
                </div>
                
                <div class="mini-charts-item bgm-cyan">
					<div class="clearfix">
	                    <div class="chart chart-pie stats-pie"></div>
	                    <div class="count">
	                        <small>Punteggio accumulato</small>
	                        <h2>{$points}/{$maxpoints}</h2>
	                    </div>
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
			
				$('.scrambled').sortable();
				
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
  											title: "\n",
  											text: "Puoi modificare la tua risposta",
  											type: "warning",
  											html: true,
  											showCancelButton: false,
  											confirmButtonClass: "btn-lg btn-warning",
  											confirmButtonText: "OK",
  											closeOnConfirm: true,
  										});
  									} else {
  										swal({
 											title: "\n",
 											text: itemAnswer,
  											type: "error",
  											html: true,
  											showCancelButton: false,
  											confirmButtonClass: "btn-lg btn-danger",
  											confirmButtonText: "CONTINUA",
  											closeOnConfirm: false,
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
  									if (itemAnswer == "") {
  										window.location = "/exam/saveanswer/"+ajax_post_data_value;
  									} else {
  										swal({
  											title: "\n",
  											text: itemAnswer,
  											type: "success",
  											html: true,
  											showCancelButton: false,
  											confirmButtonClass: "btn-lg btn-success",
  											confirmButtonText: "CONTINUA",
  											closeOnConfirm: false,
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
  									swal({
  										title: "\n",
  										text: itemAnswer,
  										type: "info",
  										showCancelButton: false,
  										confirmButtonClass: "btn-lg btn-info",
  										confirmButtonText: "CONTINUA",
  										closeOnConfirm: false,
  									},
  									function(isConfirm) {
  										if (isConfirm) {
  											window.location = "/exam/timeout";
  										}
  									});
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