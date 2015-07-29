    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SmileToMove - Learning platform</title>

        <!-- Vendor CSS -->
        <link href="/static/assets/vendors/fullcalendar/fullcalendar.css" rel="stylesheet">
        <link href="/static/assets/vendors/animate-css/animate.min.css" rel="stylesheet">
        <link href="/static/assets/vendors/sweet-alert/sweet-alert.min.css" rel="stylesheet">
        <link href="/static/assets/vendors/material-icons/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="/static/assets/vendors/socicon/socicon.min.css" rel="stylesheet">
            
        <!-- CSS -->
        <link href="/static/assets/css/app.min.1.css" rel="stylesheet">
        <link href="/static/assets/css/app.min.2.css" rel="stylesheet">
        
        {block name="custom_css"}{/block}
        
    </head>
    <body>
   
   		{block name="header"}
  			{include "../../_common/header.tpl"}
      	{/block}
      	
      	{block name="content"}
      	{/block}
      	
      	<!-- Javascript Libraries -->
        <script src="/static/assets/js/jquery-2.1.1.min.js"></script>
        <script src="/static/assets/js/bootstrap.min.js"></script>
        
        <script src="/static/assets/vendors/flot/jquery.flot.min.js"></script>
        <script src="/static/assets/vendors/flot/jquery.flot.resize.min.js"></script>
        <script src="/static/assets/vendors/flot/plugins/curvedLines.js"></script>
        <script src="/static/assets/vendors/sparklines/jquery.sparkline.min.js"></script>
        <script src="/static/assets/vendors/easypiechart/jquery.easypiechart.min.js"></script>
        
        <script src="/static/assets/vendors/fullcalendar/lib/moment.min.js"></script>
        <script src="/static/assets/vendors/fullcalendar/fullcalendar.min.js"></script>
        <script src="/static/assets/vendors/simpleWeather/jquery.simpleWeather.min.js"></script>
        <script src="/static/assets/vendors/auto-size/jquery.autosize.min.js"></script>
        <script src="/static/assets/vendors/nicescroll/jquery.nicescroll.min.js"></script>
        <script src="/static/assets/vendors/waves/waves.min.js"></script>
        <script src="/static/assets/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
        <script src="/static/assets/vendors/sweet-alert/sweet-alert.min.js"></script>
        
        <script src="/static/assets/js/flot-charts/curved-line-chart.js"></script>
        <script src="/static/assets/js/flot-charts/line-chart.js"></script>
        <script src="/static/assets/js/charts.js"></script>
        
        <script src="/static/assets/js/charts.js"></script>
        <script src="/static/assets/js/functions.js"></script>
        <script src="/static/assets/js/demo.js"></script>

		{block name="custom_js"}
      	{/block}
        
    </body>