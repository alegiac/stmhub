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
	        				<h2>Complimenti {$firstName}!</h2>
	        				<h3>Hai completato questa sessione!</h3>
	        				<hr>
	        				<div class="col-xs-8">
	        					<h4>Hai accumulato {$points} punti, continua a seguirci!</h4>
	                   		</div>
	        			</div>
	        		</div>
				</div>
			</div>
        </section>
{/block}