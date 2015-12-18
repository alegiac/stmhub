{extends "../../_common/base.tpl"}

{block name="custom_css"}

{/block}

{block name="main"}
	<section id="content">
	    <div class="container">
	    	<div class="col-sm-9">
            	<!-- Welcome area -->
            	<div class="card">
            		<img src="/static/assets/img/TRAINtoACTION.png" style="margin-top: 5px; margin-left: 5px;"/>
	        		<div class="card-header">
	        			<div class="row">
	        				<center>
	        					<h3>{$message}</h3>
	        					<hr>
	        					<h4>Hai accumulato {$points} punti, continua a seguirci!</h4>
	                   		</center>
	                   		<br><br>
	                   		<center><a href="/exam/challenges" class="btn btn-lg btn-primary">RACCOGLI SFIDA</a></center>
	        			</div>
	        		</div>
				</div>
			</div>
			<div class="col-sm-3">
				<!-- Profile view -->
                <div class="card profile-view">
                	<div class="pv-header">
                   	</div>
                    <div class="pv-body">
                        <h2>{$firstName} {$lastName}</h2>
                         <br>
                        <div class="row" style="background-color: #64c8ff;">
							<br>
							<div class="row">
								<div class="col-xs-12 col-md-6">
									<small style="color: white;">Punti</small><h4 style="color: white;">{$points}</h4>
								</div>
								<div class="col-xs-12 col-md-6">
									<a href="#myModal" role="button" data-toggle="modal">
									<small style="color: white;">Classifica</small><h4 style="color: white;">{$position}°</h4></a>
								</div>
							</div>
							<br>
						</div>
                    </div>
                </div>
            </div>
        </section>
{/block}

{block name="custom_js"}
	<script type="text/javascript">
		
		{literal}
			$(document).ready(function() {
				function disableBack() {window.history.forward()}
				window.onload = disableBack();
				window.onpageshow = function (evt) {if (evt.persisted) disableBack()}
			});
		{/literal}
	</script>
{/block}