{extends "../../_common/base.tpl"}

{block name="custom_css"}
{/block}

{block name="content"}
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
	        					<small>Secondi rimanenti</small>
	        					<br>
	        					<center><h3>100</h3></center>
	        				{/if}
	        			</div>
	        		</div>
	        		<div class="card-body card-padding">
	        				{$media}
	        				<hr>
	        				<center>
	        				<h2>{$itemQuestion}</h2>
	        				<center>
	        				<hr>
	        				<br>
	        				{$formm}
	        		</div>
	        	</div>
			</div>
		</div>
	</section>
{/block}

{block name="custom_js"}
{/block}