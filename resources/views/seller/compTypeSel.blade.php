<!DOCTYPE html>
<html>
  <head>
    <title> Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta name="description" content="Blue Moon - Responsive Admin Dashboard" />
    <meta name="keywords" content="Notifications, Admin, Dashboard, Bootstrap3, Sass, transform, CSS3, HTML5, Web design, UI Design, Responsive Dashboard, Responsive Admin, Admin Theme, Best Admin UI, Bootstrap Theme, Wrapbootstrap, Bootstrap, bootstrap.gallery" />
    <meta name="author" content="Bootstrap Gallery" />
    <link rel="shortcut icon" href="img/favicon.ico">
    <link href="/public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/css/new.css" rel="stylesheet"> 
    <link href="/public/css/charts-graphs.css" rel="stylesheet">
    <link href="/public/css/barIndicator.css" rel="stylesheet" />
    <link href="/public/fonts/font-awesome.min.css" rel="stylesheet">
	
	<link href="/public/css/pricing.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" media="all" href="/public/css/daterangepicker.css" />
	<link rel="stylesheet" type="text/css" media="all" href="/public/css/jquery.dataTables.min.css" />
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	
	<script src="/public/js/jquery.js"></script>
    <!-- HTML5 shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="/public/js/html5shiv.js"></script>
      <script src="/public/js/respond.min.js"></script>
    <![endif]-->
   

  </head>

  <body>

    <!-- Header Start -->
    <header>
      
    </header>
    <!-- Header End -->

    <!-- Main Container start -->
    <div class="dashboard-container pullbox_rel">
      <div class="container">

		
        <!-- Dashboard Wrapper Start -->
        <div class="dashboard-wrapper">
        	<div class="row">
    			<div class="col-sm-offset-4 col-sm-4 col-sm-offset-4">
          			<div class="panel panel-default">
			  			<div class="panel-heading">Please select which mode of dashboard you want</div>
						  <div class="panel-body">
							  <form name="" method="post">
							  <input type="hidden" name="_token" value="{{ csrf_token() }}">
							  <div class="col-sm-12">
							  	<div class="form-group">
		                          	<select name="compTypeSel" class="form-control">
								    	<option value="">Please Select Which View you want</option>
								    	<option value="buyer">Buyer</option>
								    	<option value="seller">Seller</option>
								    </select>
		                        </div>
		                      </div>
		                      <div class="col-sm-6">
		                      	<div class="form-group">
		                        	<input type="submit" value="Submit" class="form-control">
							    </div>
						     </div>
						     </form>
						  </div>
					</div>
				</div>
			</div>
        </div>
        <!-- Dashboard Wrapper End -->

        <footer>
          <p>© Geometric</p>
        </footer>

      </div>
    </div>
    <!-- Main Container end -->

    
    <script src="/public/js/bootstrap.min.js"></script>
	<script src="/public/js/jquery-ui-v1.10.3.js"></script>
	
	<script type="text/javascript" src="/public/js/moments.js"></script>
    <script type="text/javascript" src="/public/js/daterangepicker.js"></script>
	<script type="text/javascript" src="/public/js/jquery.dataTables.min.js"></script>
	
    <script src="/public/js/jquery.scrollUp.js"></script>
    
    <!-- Flot Charts -->
    <script src="/public/js/flot/jquery.flot.js"></script>
    <script src="/public/js/flot/jquery.flot.pie.min.js"></script>
    <script src="/public/js/flot/jquery.flot.stack.min.js"></script>
    <script src="/public/js/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/public/js/flot/jquery.flot.orderBar.min.js"></script>
    <script src="/public/js/flot/jquery.flot.resize.min.js"></script>

    <script src="/public/js/flot/custom/index3-pie.js"></script>
    <script src="/public/js/flot/custom/index3-area.js"></script>
    <script src="/public/js/flot/custom/horizontal-index.js"></script>
    <script src="/public/js/flot/custom/realtime-index.js"></script>
    <script src="/public/js/flot/custom/index3-scatter.js"></script>
    
    <!-- Custom JS -->
    <script src="/public/js/menu.js"></script>

    <!-- Sparkline JS -->
    <script src="/public/js/sparkline.js"></script>
    
    <script src="/public/js/jquery.easing.1.3.js"></script>
    <script src="/public/js/jquery-barIndicator.js"></script>
    <script src="/public/js/custom-barIndicator.js"></script>

	
	<!-- Radio Button Js Start -->
	<script type="text/javascript">
		$(':radio').change(function (event) {
			var id = $(this).data('id');
			$('#' + id).addClass('none').siblings().removeClass('none');
		});
	</script>
	<!-- Radio Button Js End -->
	
	<!-- Date Picker Js Start-->
	<script type="text/javascript">
	  $('.date_filter').daterangepicker({
	  "showDropdowns": true,
		"ranges": {
			"Today": [
				"2016-01-01T09:33:46.603Z",
				"2016-01-01T09:33:46.603Z"
			],
			"Yesterday": [
				"2015-12-31T09:33:46.603Z",
				"2015-12-31T09:33:46.603Z"
			],
			"Last 7 Days": [
				"2015-12-26T09:33:46.603Z",
				"2016-01-01T09:33:46.604Z"
			],
			"Last 30 Days": [
				"2015-12-03T09:33:46.604Z",
				"2016-01-01T09:33:46.604Z"
			],
			"This Month": [
				"2015-12-31T18:30:00.000Z",
				"2016-01-31T18:29:59.999Z"
			],
			"Last Month": [
				"2015-11-30T18:30:00.000Z",
				"2015-12-31T18:29:59.999Z"
			]
		},
		"startDate": "12/26/2015",
		"endDate": "01/01/2016"
	}, function(start, end, label) {
	  console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
	});
      </script>
	<!-- Date Picker Js End-->
	
	<!-- Data Table Js Start-->
	<script type="text/javascript">
		// Setup - add a text input to each footer cell
		$('#example thead tr#filterrow th').each( function () {
		var title = $('#example thead th').eq( $(this).index() ).text();
		$(this).html( '<input type="text" onclick="stopPropagation(event);" class="form-control" placeholder="" /><i class="fa fa-search search_icon"></i>');
		} );

		// DataTable
		var table = $('#example').DataTable( {
		
		orderCellsTop: true
			
		} );

		// Apply the filter
		$("#example thead input").on( 'keyup change', function () {
		table
			.column( $(this).parent().index()+':visible' )
			.search( this.value )
			.draw();
		} );

		function stopPropagation(evt) {
		if (evt.stopPropagation !== undefined) {
			evt.stopPropagation();
		} else {
			evt.cancelBubble = true;
		}
		}
	</script>
	<!-- Data Table Js End-->
	
	<!-- Onclick Show Hide Js Start-->
    <script type="text/javascript">
		var select = document.getElementById('test'),
		onChange = function(event) {
		var shown = this.options[this.selectedIndex].value == 1;

		document.getElementById('hidden_div').style.display = shown ? 'block' : 'none';
		};

		if (select.addEventListener) {
		select.addEventListener('change', onChange, false);
		} else {
		select.attachEvent('onchange', function() {
		onChange.apply(select, arguments);
		});
		}
	</script>
	<!-- Onclick Show Hide Js End-->
	
	<!-- Page ScrollUp Js Start-->
    <script type="text/javascript">
      //ScrollUp
      $(function () {
        $.scrollUp({
          scrollName: 'scrollUp', // Element ID
          topDistance: '300', // Distance from top before showing element (px)
          topSpeed: 300, // Speed back to top (ms)
          animation: 'fade', // Fade, slide, none
          animationInSpeed: 400, // Animation in speed (ms)
          animationOutSpeed: 400, // Animation out speed (ms)
          scrollText: 'Top', // Text for element
          activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
        });
      });

      //Tooltip
      $('a').tooltip('hide');
      $('i').tooltip('hide');

      // SparkLine Bar
      $(function () {
        $("#emails").sparkline([3,2,4,2,5,4,3,5,2,4,6,9,12,15,12,11,12,11], {
        type: 'line',
        width: '200',
        height: '70',
        lineColor: '#3693cf',
        fillColor: '#e5f3fc',
        lineWidth: 3,
        spotRadius: 6
        });
      });

    </script>
	<!-- Page ScrollUp Js End-->
	
	
<!-------- BODY TOGGLE JS START------>	
	<script type="text/javascript">
		$('.dropdown-toggle').dropdown();
		$('.pullbtn').click(function(){
		$('.pullbox').toggleClass('active');
		});	
	</script>
<!-------- BODY TOGGLE JS END------>
  </body>
</html>