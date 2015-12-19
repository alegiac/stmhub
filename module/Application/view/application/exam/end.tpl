{extends "../../_common/base.tpl"}

{block name="custom_css"}
<style type="text/css">
		.foot {
			position : absolute;
			bottom : 0;
			height : 40px;
			margin-top : 40px;
		}
		span.position {
		  font-size: 600%;
	      font-weight: bold;
		  color: white;
		  vertical-align: middle;
		
		}
		.competition-podium {
		  min-width: 200px;
		  max-width: 450px;
		  height: 320px;
		  position: relative;
		  background:#FFFFFF;
		}
		.competition-podium .podium-block {
		  width: 150px;
		  text-align: left;
		  display: inline-block;
		  position: absolute;
		  bottom: 0;
		}
		.competition-podium .podium-block .place {
		  font-size: 1em;
		  font-weight: bold;
		  font-family: Arial, Helvetica, sans-serif;
		  line-height: 15px;
		}
		.competition-podium .podium-block .sum {
		  font-size: 1.2em;
		  font-weight: bold;
		  font-family: Arial, Helvetica, sans-serif;
		  line-height: 70px;
		}
		
		.competition-podium .podium-block.bronze {
		  color: #e6d6bf;
		  left: 0px;
		}
		.competition-podium .podium-block.bronze .podium {
		  background: #e6d6bf;
		  height: 0px;
		}
		.competition-podium .podium-block.bronze .name {
		  color: #856e4e;
		}
		
		.competition-podium .podium-block.gold {
		  color: #E87E04;
		  left: 150px;
		}
		.competition-podium .podium-block.gold .podium {
		  background: #ead679;
		  height: 0px;
		}
		.competition-podium .podium-block.gold .name {
		  color: #856e4e;
		  text-align:center
		}
		
		.competition-podium .podium-block.silver {
		  color: #cbd9de;
		  left: 300px;
		}
		.competition-podium .podium-block.silver .podium {
		  background: #cbd9de;
		  height: 0px;
		}
		.competition-podium .podium-block.silver .name {
		  color: #856e4e;
		  text-align:center
		}
		.podium {
		  display: table;
		  width: 100%;
		  border-top-left-radius: 4px;
		  border-top-right-radius: 4px;
		}
		.podium > * {
		  display: table-cell;
		  vertical-align: middle;
		  text-align: center;
		  color: #4D4D4D;
		  font-size: 1.5em;
		}		
	</style>
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
	        			{if $showClassification eq 1}
	        			<div class="row">
	        				<center>
	        					<br>
	        					<hr>
	        					<br>
	        					<h3>Classifica</h3>
								<div class="competition-podium">
							  		<div class="podium-block bronze">	
										<div class="name">
											<center>
												<img style="max-width:120px;" src="{$bronzePrizeUrl}"/>
												<br>
												<h4>{$bronzePrizeTitle}</h4>
											</center>
										</div>
						                <div class="podium">
						                	<span class="position">3</span>
							                <span>{$bronzeFirstName}<br>{$bronzePoints}</span>
							            </div>
									</div>
									<div class="podium-block gold">	
										<div class="name">
											<center>
												<img style="max-width:120px;" src="{$goldPrizeUrl}"/>
												<br>
												<h4>{$goldPrizeTitle}</h4>
											</center>
										</div>
										<div class="podium">
											<span class="position">1</span>
                    						<span>{$goldFirstName}<br>{$goldPoints}</span>
                						</div>
									</div>
									<div class="podium-block silver">	
										<div class="name">
											<center>
												<img style="max-width:120px;" src="{$silverPrizeUrl}"/>
												<br>
												<h4>{$silverPrizeTitle}</h4>
											</center>
										</div>
										<div class="podium">
											<span class="position">2</span>
                    						<span>{$silverFirstName}<br>{$silverPoints}</span>
                						</div>
									</div>
								</div>
							</center>
      						<hr>
      						<center>
      							<div class="col-xs-10 col-xs-offset-1">{$otherPrices}</div>
      						</center>
	        			</div>
	        			{/if}
	        		</div>
				</div>
				<div id="base_mobile"  class="row visible-xs .visible-xs-block card">
					<div class="pv-card clearfix">
						<div class="col-xs-6">
							<div class="pv-body">
								<p>
									<h4>{$firstName} {$lastName}</h4>
									<span style="color:light-grey;">{$courseName}</span><br>
									<span style="color:light-grey;">{$examName}</span><br>
								</p>
							</div>
						</div>
						<div class="col-xs-6" style="text-align:right;">
							<div class="pv-body">
								<br>
								<p>
									<br>
									<br>
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
					<div class="pv-header"></div>
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
				<div class="card">
					<div class="pv-body">
						<center><br><h4>{$courseName}</h4><hr><br></center>
						{$examList}
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
				
				function podiumAnimate() {
        			$('.bronze .podium').animate({
			            "height": "110px"
			        }, 1500);
			        $('.gold .podium').animate({
			            "height": "200px"
			        }, 1500);
			        $('.silver .podium').animate({
			            "height": "140px"
			        }, 1500);
			        $('.competition-container .name').delay(1000).animate({
			            "opacity": "1"
			        }, 500);
			    }
			    podiumAnimate();
				
			});
		{/literal}
	</script>
{/block}