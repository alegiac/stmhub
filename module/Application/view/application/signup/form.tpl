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
        ul
        {
            list-style-type: none;
            padding: 0;
            color: red;
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
    
    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <iframe src="http://www.traintoaction.com/privacy" style="zoom:1.00" width="99.6%" height="100%" frameborder="0"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
    
    <section id="content">
        <div class="container">
            <div class="col-sm-12">
                <!-- Content of registration -->
                <div class="card">
                    <img src="/static/assets/img/TRAINtoACTION.png" style="margin-top: 5px; margin-left: 5px;"/>
                    <center>
                    <h2>Registrazione studente</h2>
                    {$this->form()->openTag($this->form)}

                    <div class="card-header card-padding">
                        <button type="button" class="btn btn--facebook anim" onclick="location.href='https://www.facebook.com/v2.6/dialog/oauth?client_id=234480890274751&amp;state=cea4d8879578e2c227f2a0be1a76cdd3&amp;response_type=code&amp;sdk=php-sdk-5.1.2&amp;redirect_uri=http%3A%2F%2Fwww.petadviser.it%2Flanding%2Findex&amp;scope=email'">Accedi con Facebook</button>
                    </div>
                    <div class="card-body card-padding">
                        <div class="row">
                            <center>oppure procedi con la registrazione manuale<br><br><br></center>
                            <div class="col-sm-4">
                                {$this->formLabel($this->form->get('firstname'))}
                                {$this->formElement($this->form->get('firstname'))}
                                {$this->formElementErrors($this->form->get('firstname'))}
                            </div>
                            <div class="col-sm-4">
                                {$this->formLabel($this->form->get('lastname'))}
                                {$this->formInput($this->form->get('lastname'))}
                                {$this->formElementErrors($this->form->get('lastname'))}
                            </div>
                            <div class="col-sm-4">
                                {$this->formLabel($this->form->get('email'))}
                                {$this->formElement($this->form->get('email'))}
                                {$this->formElementErrors($this->form->get('email'))}
                            </div>
                        </div>
                        {if $showExtraFields eq 1}
                            <div class="row">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4">
                                    <span class="input-group-addon"></span>
                                    <div class="fg-line">
                                        {$this->formLabel($this->form->get('internal'))}
                                        {$this->formElement($this->form->get('internal'))}
                                        {$this->formElementErrors($this->form->get('internal'))}
                                    </div>
                                
                                </div>
                                <div class="col-sm-4">
                                    <span class="input-group-addon"></span>
                                    <div class="fg-line">
                                        {$this->formLabel($this->form->get('role'))}
                                        {$this->formSelect($this->form->get('role'))}
                                        {$this->formElementErrors($this->form->get('role'))}
                                    </div>
                                </div>
                                    <div class="col-sm-2"></div>
                            </div>
                        {/if}
                        <div class="row">
                            <span>Ho letto e accetto le condizioni riportate <a href="#" id="privacy">QUI</a></span>
                            {$this->formCheckbox($this->form->get('privacy_check'))}                            
                            <br>
                            {$this->formElementErrors($this->form->get('privacy_check'))}
                        </div>
                        <div class="row">
                            {$this->formSubmit($this->form->get('subm'))}                            
                        </div>
                    </div>                               
                </div>
                {$this->form()->closeTag()}     
            </div>
        </div>
    </section>
{/block}

{block name="custom_js"}
    <script src="/static/assets/js/jquery.sortable.min.js"></script>
    <script type="text/javascript">

        {literal}
            $(document).ready(function() {
                var src="http://www.traintoaction.com/privacy";
                
                $('#myModal').on('show.bs.modal', function () {
                    $('.modal-content').css('height',$( window ).height()*0.95);
                    $('.modal-body').css('height',$( window ).height()*0.81);
                });
                //JS script
                $('#myModal').on('show', function () {

                    $('iframe').attr("src",frameSrc);
      
                });
                $("#privacy").on("click", function() {
                    $('#myModal').modal({show:true});
                    //$('#myModal').modal('show').find('.modal-body').load("http://www.traintoaction.com/privacy");
                });
                
            });
        {/literal}
    </script>
{/block}