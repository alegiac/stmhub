{extends "../../_common/base.tpl"}

{block name="custom_css"}

{/block}

{block name="main"}
	<section id="content">
	    <div class="container">
	    	<div class="col-sm-9">
            	<!-- Welcome area -->
            	<div class="card">
	        		<div class="card-header">
	        			<div class="row">
	        				<center>
	        					<h3>Raccogli la sfida</h3>
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

        </section>
{/block}