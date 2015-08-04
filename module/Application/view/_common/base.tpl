<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SmileToMove - Learning platform</title>

{if $disableCssMM eq 1}
{else}

        <!-- Vendor CSS -->
        <link href="/static/assets/vendors/animate-css/animate.min.css" rel="stylesheet">
        <link href="/static/assets/vendors/noUiSlider/jquery.nouislider.min.css" rel="stylesheet">
        <link href="/static/assets/vendors/farbtastic/farbtastic.css" rel="stylesheet">
        <link href="/static/assets/vendors/summernote/summernote.css" rel="stylesheet">
        <link href="/static/assets/vendors/sweet-alert/sweet-alert.min.css" rel="stylesheet">
        <link href="/static/assets/vendors/material-icons/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="/static/assets/vendors/socicon/socicon.min.css" rel="stylesheet">
            
        <!-- CSS -->
        <link href="/static/assets/css/app.min.1.css" rel="stylesheet">
        <link href="/static/assets/css/app.min.2.css" rel="stylesheet">
        
        <style type="text/css">
            .toggle-switch .ts-label {
                min-width: 130px;
            }
            form div {
  				padding: 50px 0 50px 0;
  				margin: 50px 0 50px 0;
			}
        </style>
{/if}
		{block name="custom_css"}{/block}

    </head>
    
    <body>
    
    	{block name="header"}
  			{include "../../_common/header.tpl"}
      	{/block}
      	
      	{block name="main"}{/block}
      	
        <!-- Javascript Libraries -->
        <script src="/static/assets/js/jquery-2.1.1.min.js"></script>
        <script src="/static/assets/js/bootstrap.min.js"></script>
        
        <script src="/static/assets/vendors/moment/moment.min.js"></script>
        <script src="/static/assets/vendors/nicescroll/jquery.nicescroll.min.js"></script>
        <script src="/static/assets/vendors/auto-size/jquery.autosize.min.js"></script>
        <script src="/static/assets/vendors/bootstrap-select/bootstrap-select.min.js"></script>
        <script src="/static/assets/vendors/chosen/chosen.jquery.min.js"></script>
        <script src="/static/assets/vendors/noUiSlider/jquery.nouislider.all.min.js"></script>
        <script src="/static/assets/vendors/input-mask/input-mask.min.js"></script>
        <script src="/static/assets/vendors/farbtastic/farbtastic.min.js"></script>
        <script src="/static/assets/vendors/summernote/summernote.min.js"></script>
        <script src="/static/assets/vendors/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
        <script src="/static/assets/vendors/fileinput/fileinput.min.js"></script>
        <script src="/static/assets/vendors/waves/waves.min.js"></script>
        <script src="/static/assets/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
        <script src="/static/assets/vendors/sweet-alert/sweet-alert.min.js"></script>
        
        <script src="/static/assets/js/demo.js"></script>
        
        {block name="custom_js"}{/block}
       
    </body>