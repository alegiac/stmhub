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
	        					<h2>Benvenut{$sexDesc}, {$firstName}</h2>
	        				<br>
	        				<h4>al momento non &egrave; disponibile nessuna sessione d'esame</h4>
	        				<h4>Ti invitiamo ad utilizzare il link ricevuto via email relativo ad ogni sessione d'esame</h4>
	        				<hr>
	        				</center>
	                    </div>
	                    <div class="col-xs-4">
	                    	<img src="{$examImage}" style="max-width:100%;max-height:100%;"/>
	                    </div>
	                    <br><br>
	                    
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
            </div>

        </section>
{/block}