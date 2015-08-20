{extends "../../_common/base.tpl"}

{block name="custom_css"}

{/block}

{block name="main"}
	<section id="content">
	    <div class="container">
	    	<div class="col-sm-3">
				<!-- Profile view -->
                <div class="card profile-view">
                	<div class="pv-header">
                    	<img src="/static/assets/img/profile-pics/profile-pic.gif" class="pv-main" alt="">
                   	</div>
                            
                    <div class="pv-body">
                        <h2>{$firstName} {$lastName}</h2>
                        
                        <ul class="pv-contact">
                        </ul>
                        
                        <ul class="pv-follow">
                        </ul>
                        
                        <a href="/exam/stats" class="pv-follow-btn">Statistiche</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
            	<!-- Welcome area -->
            	<div class="card">
	        		<div class="card-header">
	        			<div class="row">
	        				<h2>Bentornat{$sexDesc}, {$firstName}</h2>
	        				<br>
	        				<h3>Riprendi la sessione d'esame</h3>
	        				<small>Hai una sessione non completata</small>
	        				<hr>
	        				<div class="col-xs-8">
	        					<h4>Corso: {$courseName}</h4>
	        					{$courseDesc}
	        					<br><br>
	        					<h4>Esame n. {$examNumber}/{$totExams}: {$examName}</h4>
	        					{$examDesc}
	        					<br><br><br><br><br><br>
	                    	</div>
	                    	<div class="col-xs-4">
	                    		<img src="{$examImage}" style="max-width:100%;max-height:100%;"/>
	                    	</div>
	                    	<br><br><br><br><br><br>
	                    	<a href="/exam/participate" class="btn btn-primary btn-lg col-xs-8 col-xs-offset-2">RIPRENDI</a>
	                    	<br><br><br>
	                   	</div>
	        		</div>
	        		<div class="card-body card-padding">
	        			<h5>Informazioni sull'esame</h5>
	        			<div class="row">
	        				<div class="col-xs-12 col-sm-4">
	        					<div class="mini-charts-item bgm-orange">
									<div class="clearfix">
	                    				<div class="chart chart-pie stats-pie"></div>
	                    				<div class="count">
	                        				<small>Numero quesiti</small>
	                        				<h2>{$totalItems}</h2>
	                    				</div>
	                				</div>
								</div>
	        				</div>
	        				<div class="col-xs-12 col-sm-4">
	        					<div class="mini-charts-item bgm-orange">
									<div class="clearfix">
	                    				<div class="chart chart-pie stats-pie"></div>
	                    				<div class="count">
	                        				<small>Punti possibili</small>
	                        				<h2>{$maxPoints}</h2>
	                    				</div>
	                				</div>
								</div>
	        				</div><div class="col-xs-12 col-sm-4">
	        					<div class="mini-charts-item bgm-orange">
									<div class="clearfix">
	                    				<div class="chart chart-pie stats-pie"></div>
	                    				<div class="count">
	                        				<small>Data termine</small>
	                        				<h2>{$endDate}</h2>
	                    				</div>
	                				</div>
								</div>
	        				</div>
	        			</div>
	        		</div>
	        	</div>
			</div>
        </section>
{/block}