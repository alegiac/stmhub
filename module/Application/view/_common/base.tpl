    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SmileToMove - Learning platform</title>

        <!-- Vendor CSS -->
        <link href="/static/assets2/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="/static/assets2/vendors/bower_components/sweetalert/dist/sweetalert-override.min.css" rel="stylesheet">
        <link href="/static/assets2/vendors/bower_components/material-design-iconic-font/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="/static/assets2/vendors/socicon/socicon.min.css" rel="stylesheet">
        <link href="/static/assets2/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
        <link href="/static/assets2/vendors/bower_components/nouislider/distribute/jquery.nouislider.min.css" rel="stylesheet">
        <link href="/static/assets2/vendors/bower_components/summernote/dist/summernote.css" rel="stylesheet">
        <link href="/static/assets2/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
        <link href="/static/assets2/vendors/farbtastic/farbtastic.css" rel="stylesheet">
        <link href="/static/assets2/vendors/chosen_v1.4.2/chosen.min.css" rel="stylesheet">
            
        <!-- CSS -->
        <link href="/static/assets2/css/app.min.1.css" rel="stylesheet">
        <link href="/static/assets2/css/app.min.2.css" rel="stylesheet">
        
        <!-- Following CSS are used only for the Demp purposes thus you can remove this anytime. -->
        <style type="text/css">
            .toggle-switch .ts-label {
                min-width: 130px;
            }
        </style>
        {block name="custom_css"}{/block}
        
    </head>
    <body>
   
   		{block name="header"}
  			{include "../../_common/header.tpl"}
      	{/block}
      	
      	{block name="content"}
      	{/block}
      	
  		{block name="custom_js"}
  			<!-- Javascript Libraries -->
			<script src="/static/assets2/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        	<script src="/static/assets2/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        
        	<script src="/static/assets2/vendors/bower_components/jquery.nicescroll/jquery.nicescroll.min.js"></script>
        	<script src="/static/assets2/vendors/bower_components/Waves/dist/waves.min.js"></script>
        	<script src="/static/assets2/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
        	<script src="/static/assets2/vendors/bower_components/sweetalert/dist/sweetalert.min.js"></script>
        
        	<script src="/static/assets2/vendors/bower_components/moment/min/moment.min.js"></script>
        	<script src="/static/assets2/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
        	<script src="/static/assets2/vendors/bower_components/nouislider/distribute/jquery.nouislider.all.min.js"></script>
        	<script src="/static/assets2/vendors/bower_components/summernote/dist/summernote.min.js"></script>
        	<script src="/static/assets2/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
        
        	<!-- Placeholder for IE9 -->
        	<!--[if IE 9 ]>
            	<script src="/static/assets2/vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
        	<![endif]-->
        
        	<script src="/static/assets2/vendors/chosen_v1.4.2/chosen.jquery.min.js"></script>
        	<script src="/static/assets2/vendors/fileinput/fileinput.min.js"></script>
        	<script src="/static/assets2/vendors/input-mask/input-mask.min.js"></script>
        	<script src="/static/assets2/vendors/farbtastic/farbtastic.min.js"></script>
       
        	<script src="/static/assets2/js/functions.js"></script>
        	<script src="/static/assets2/js/demo.js"></script>
    
		{/block}
        
    </body>