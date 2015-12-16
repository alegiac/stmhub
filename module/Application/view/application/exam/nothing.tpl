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
				<div id="base_mobile"  class="row visible-xs .visible-xs-block card">
					<div class="pv-card clearfix">
						<div class="col-xs-4">
							<div class="pv-body">
								<strong>{$firstName} {$lastName}</strong>
								<p style="color:light-grey;">{$courseName}</p>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="pv-body">
									<p>
										<small style="color:light-grey;">Punti</small>:  <strong style="color:black;">{$points}</strong><br>
										<small style="color:light-grey;">Posizione</small>:  <strong style="color:black;">{$position}</strong><br>
										{if $hasPrize eq 1}
											<small style="color:light-grey;">Premio</small>: <strong style="color:black;">{$prizeName}</strong><br>
										{/if}
										<small style="color:light-grey;">Scadenza</small>:  <strong style="color:black;">{$expectedEndDateShort}</strong><br>
									</p>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="pv-header"></div>
							<div class="pv-body">
								{$examListShort}
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
						<small>{$email}</small>
					</div>
					<div class="mini-charts-item bgm-cyan">
						<div class="clearfix">
							<div class="count">
								<small>Punti</small><h2>{$points}</h2><hr>
								<small>Posizione</small><h2>{$position}</h2>
								{if $hasPrize eq 1}
									<hr>
									<small>Premio</small><h2>{$prizeName}</h2>
								{/if}
							</div>
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
		</div>
	</section>
{/block}