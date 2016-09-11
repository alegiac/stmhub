{extends "../../_common/base_1.tpl"}

{block name="custom_css"}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <style type="text/css">
        @media screen and (min-width: 200px) and (max-width: 1024px) {
            img {
                max-width: 70%;
            }
            h2 {
                font-size: 20px;
            }
        }

        @media screen and (min-width: 1025px) {
            img {
                max-width: 100%;
            }
            h2 {
                font-size: 27px;
            }
        }

        .foot {
            position : absolute;
            bottom : 0;
            height : 40px;
            margin-top : 40px;
        }
        .ui-state-highlight { 
            height: 1.5em; line-height: 1.2em; 
        }
        
    </style>
    <link href="/static/assets/vendors/animate-css/animate.min.css" rel="stylesheet">
        <link href="/static/assets/vendors/noUiSlider/jquery.nouislider.min.css" rel="stylesheet">
        <link href="/static/assets/vendors/farbtastic/farbtastic.css" rel="stylesheet">
        <link href="/static/assets/vendors/summernote/summernote.css" rel="stylesheet">
        <link href="/static/assets/vendors/sweet-alert/sweet-alert.min.css" rel="stylesheet">
        <link href="/static/assets/vendors/material-icons/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="/static/assets/vendors/socicon/socicon.min.css" rel="stylesheet">
        
{/block}

{block name="main"}
    <section id="content">
        <div class="container">
            <div class="col-sm-12">
                <!-- Content of page -->
                <div class="card">
                    <img src="/static/assets/img/TRAINtoACTION.png" style="margin-top: 5px; margin-left: 5px;"/>
                    <center>
                    <h2>Registrazione studente</h2>
                    <div class="card-header card-padding">    
                    </div>
                    <div class="card-body card-padding">
                        <div class="row">
                            <center>
                                Grazie per esserti registrato al corso <strong>{$coursename}</strong> sulla piattaforma TRAINTOACTION!<br>
                                Riceverai via mail il link per partecipare alle sessioni e iniziare quindi il tuo percorso formativo.<br><br>
                                Buon lavoro!
                            </center>
                        </div>
                    </div>                               
                </div>
            </div>
        </div>
    </section>
{/block}

{block name="custom_js"}
    <script type="text/javascript">
	{literal}
            $(document).ready(function() {
            });
	{/literal}
    </script>
{/block}