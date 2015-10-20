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
	        					<h2>Complimenti {$firstName}!</h2>
	        					<h3>Hai completato questa sessione!</h3>
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
                    </div>
                </div>
            </div>
            
        </section>
{/block}