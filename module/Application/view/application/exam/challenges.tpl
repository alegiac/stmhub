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
	        					<h3>RACCOGLI LE SFIDE E MIGLIORA LA TUA POSIZIONE IN CLASSIFICA</h3>
	        				</center>
	                    </div>
	                    <br><br><br><br><br><br>
	                    <center>{$btn}</center>
	                    <br><br><br>
	        		</div>
	        	</div>
	        
				<div id="base_mobile"  class="row visible-xs .visible-xs-block card">
					<div class="pv-card clearfix">
						<div class="col-xs-4">
							<div class="pv-body">
								<p>
									<br>
									<span style="color:light-grey;">{$firstName} {$lastName}</span><br>
									<span style="color:light-grey;">{$courseName}</span><br>
									<span style="color:light-grey;">{$examName}</span><br>
								</p>
							</div>
						</div>
						<div class="col-xs-4" style="text-align:center;">
							<div class="pv-body">
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
								<p>
									<br>
									<small style="color:light-grey;">Tempo</small>: <strong style="color:black;">{$minInSession}</strong><br>
									<small style="color:light-grey;">Punti</small>:  <strong style="color:black;">{$points}</strong><br>
									<small style="color:light-grey;">Classifica</small>:  <strong style="color:black;">{$position}</strong><br>
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
                <div class="card" style="margin-top:-25px;">
                	<div class="pv-body">
                		<center><br><h4>{$courseName}</h4><hr></center>
                		{$examList}
                	</div>
                </div>
            </div>
        </section>
{/block}