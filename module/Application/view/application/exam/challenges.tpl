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
	            				<small>Scadenza</small><br>
	            				<strong style="color:black;">{$expectedEndDate}</strong>
	            				<br>
	            				<p style="color:light-grey;"><small>Punti</small><br><strong style="color:black;">{$points}/{$maxpoints}</strong></p>
	            			</center>
                		</div>
                	</div>
                	<div class="col-xs-4 card" style="height:80px;">
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
                            
                    <div class="pv-body" style="margin-top: 30px;">
                        <h2>{$firstName} {$lastName}</h2>
                        <br>
                        <div class="mini-charts-item bgm-cyan">
							<div class="clearfix">
	                    		<div class="count">
	                        		<small>Punti</small>
	                        		<h2>{$points}</h2>
	                    		</div>
	                		</div>
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