{extends "../../_common/base.tpl"}

{block name="custom_css"}

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

            <div class="col-sm-3">
				<!-- Profile view -->
                <div class="card profile-view">
                	<div class="pv-header">
                    	<img src="/static/assets/img/profile-pics/profile-pic.gif" class="pv-main" alt="">
                   	</div>
                            
                    <div class="pv-body">
                        <h2>{$firstName} {$lastName}</h2>
                        <small>{$email}</small>
                    
                        <ul class="pv-contact">
                        </ul>
                        
                        <ul class="pv-follow">
                        </ul>
                        
                        <a href="" class="pv-follow-btn">Logout</a>
                    </div>
                </div>
                <!-- Chart pies -->
                <div class="mini-charts-item bgm-cyan">
					<div class="clearfix">
	                    <div class="chart chart-pie stats-pie"></div>
	                    <div class="count">
	                        <small>Completato sessione</small>
	                        <h2>{$sessionCompleted}/{$sessionTotal} - {$sessionPercentage}</h2>
	                    </div>
	                </div>
				</div>
                <div class="mini-charts-item bgm-cyan">
	                <div class="clearfix">
	                    <div class="chart chart-pie stats-pie"></div>
	                    <div class="count">
	                        <small>Completato esame</small>
	                        <h2>{$examCompleted}/{$examTotal} - {$examPercentage}</h2>
	                    </div>
	                </div>
                </div>
            </div>
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
	        				Corso:&nbsp;&nbsp;<strong>{$courseName}</strong><br>
	        				Esame:&nbsp;&nbsp;<strong>{$examName}</strong><br>
	        				Quesito:&nbsp;&nbsp;<strong>{$itemProgressive}/{$totalItems}</strong>
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
	        		<div class="card-body card-padding">
	        			<div class="row">
	        				{$media}
		        			<hr>
		        			<center>
		        				<h2>{$itemQuestion}</h2>
		        			</center>
		        			<hr>
		        			<br>
		        		</div>
		        		<div class="row">
		        		 	{$this->form($form)}
	        			</div>
	        		</div>
	        	</div>
			</div>
        </section>
{/block}

{block name="custom_js"}
	<script type="text/javascript">
		{if $remainingTime > -1}
			{literal}
				$(document).ready(function() {
			
					var initial = {/literal}{$remainingTime}{literal} ;
					var delay = {/literal}{$remainingTime}{literal} ;
					var url = "/exam/timeout";
					
					function countdown() {
						setTimeout(countdown, 1000) ;
						delay --;
						if (delay < 0) {
							delay = 0;
							initial = 0;
							window.location = url;
						}
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
				});
			{/literal}
		{/if}
</script>
{/block}