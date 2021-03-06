<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1">
    <title>Theodore Kluge</title>
	<link href='https://fonts.googleapis.com/css?family=Roboto:300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/villa7.css">
	<link rel="icon" href="https://avatars0.githubusercontent.com/u/1696813">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<body>

<header class="header">
	<div class="nav">
		<a class="waves-effect waves-light btn btn-flat nav-sub" href="#projects">Projects<link class="rippleJS" /></a>
		<a class="waves-effect waves-light btn btn-flat nav-sub" href="#about">About<link class="rippleJS" /></a>
	</div>
</header>
<main>
<section class="img-align">
	<a href="https://github.com/villa7" class="profpic-link"><img class="profpic" src="https://avatars0.githubusercontent.com/u/1696813" alt="github profile picture" /></a>
</section>
<h1 class="title">Theodore Kluge</h1>
<h4 class="subtitle">villa7_</h4>
<div>
	<section class="projectlist" id="projects">
		<section class="projectlist-content container" id="projectlist-content">
			
		</section>
	</section>
	<section class="about container" id="about">
		<p>
			This is going to be Lipsum because I do not feel like writing about myself. Fusce metus ligula, consequat eu turpis id, consequat ornare ipsum. Aenean id pharetra metus, ut tincidunt sem. Integer posuere diam sed est pharetra cursus. Nullam pharetra ligula eu felis fermentum, ut pellentesque nunc ornare. Etiam venenatis turpis sed turpis laoreet malesuada.<br>Integer tristique enim est, vitae auctor metus vestibulum quis. Vivamus tristique eu ante at ultricies. Curabitur nec malesuada eros. Praesent vel pellentesque augue. In id nulla id diam tempus auctor a a ex. Morbi dignissim fringilla mollis. Aliquam nec lectus risus. Etiam eu tellus quis lorem viverra ullamcorper nec ut purus. Quisque iaculis gravida justo id volutpat. Curabitur auctor metus eget gravida viverra.
		</p>
	</section>
</div>
</main>
<footer class="page-footer">
	<div class="footer-copyright">
	    <div class="container">
		    &copy; <?php echo date("Y") ?> Theodore Kluge
		</div>
    </div>
</footer>
</body>
<script type="text/template" id="project">
<a href="<%= model.get('url') %>">
   	<div class="min-glass projectbox waves-effect" id="project-<%= model.get('num') %>">
   	    <div class="thumbnail project-thumb">
   	        <div class="caption">
   	            <span class="name"><%= model.get('title') %></span>
   	            <p><%= model.get('desc') %></p>
   	        </div>
   	    </div>
    </div>
</a>
<hr class="divider">
</script>

<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
    <script src="js/underscore.min.js"></script>
    <script src="js/backbone.min.js"></script>
    <script type="text/javascript" src="js/ripple.js"></script>
    <script>
    
  	$(document).ready(function(){
  		new projectLoader();
  		Backbone.history.start();
  		$('a.nav-sub').on('click', function(e) {
  			e.preventDefault();
  			console.log('switchto: ' + $(this).attr('href'));
  			$($(this).attr('href')).css('display','block')
  									.siblings().css('display','none');
  		});
	});

    	var stopNum = 0;
    	var winWidth = $(window).width();

		var Data = Backbone.Model.extend({
		    defaults: {
		        title: '',
		        prev: '',
		        desc: '',
		        url: '',
		        num: ''
		    }
		});

		//Source spreadsheet
		var DataList = Backbone.Collection.extend({
		    model: Data,
		    url: 'https://spreadsheets.google.com/feeds/list/1Xg_IU23Pf60PIhwVgf5VLQzO7XYZOWbOuFMZvp4uiR8/1/public/values?alt=json' 
		});

		//JSON reader thingy
		var AppView = Backbone.View.extend({
		    //set templates
		    project: _.template($('#project').html()),
		    initialize: function () {
		    	//$('.loader').fadeIn();
		    	console.info("Loading project list");
		        this.collection.on('reset', this.render, this);
		        c = this.collection;
		        this.collection.fetch({
		            success: function (model, response) {
		            	console.info('Loaded');
		                var feed = response['feed'];
		                var entry = feed['entry'];
		                var arr = [];
		                for (var i=0; i<entry.length; i++) {
		                    obj = {
		                        "title": entry[i].gsx$title.$t,
		                        "prev": entry[i].gsx$preview.$t,
		                        "desc": entry[i].gsx$description.$t,
		                        "url": entry[i].gsx$url.$t,
		                        "num": i
		                    };
		                    if (i == 0) {
		                    	//$('img.background').attr('src', entry[i].gsx$background.$t);
		                    }
		                    if (obj.url.substr(0, 5) == 'https') {
		                    	
		                    } else {
		                    	obj.url = 'http://' + obj.url;
		                    }
		                    arr.push(obj);
		                    stopNum++;
		                }
		                //$('.loader').fadeOut();
		                console.log("Project list can now be displayed");
		                //showProjectList();
		                //checkScroll();
		                c.reset(arr);
		            },
		            error: function () {
		            }
		        });
		    },

		    render: function () {
		        this.collection.each(this.list, this);
		    },

		    list: function (model) {
		        
		            $('#projectlist-content').append(this.project({model: model}));
		        
		    }
		});

		var projectLoader = Backbone.Router.extend({
		    routes: {
		        '': 'start',
		    },
		    start: function () {
		        new AppView({collection: new DataList()});
		    }
		});
		function showProjectList() {
			i = 0;
			if (winWidth > 1600) { //4
				var glass_rows = 4;
			} else if (winWidth <= 1600 && winWidth > 1040) { //3
				var glass_rows = 3;
			} else if (winWidth <= 1040 && winWidth > 600) { //2
				var glass_rows = 2;
			} else { //1
				var glass_rows = 1;
			}

			var	glass_container_padding = 40, /*parseInt($('#page-2').css("padding-left")),*/
				glass_container_width = $('#projectlist-content').width(),
				glass_currentRow = 1,
				glass_left = glass_container_padding,
				glass_top = glass_container_padding,
				glass_padding = 20,
				glass_total_padding = (glass_container_padding * 2) + (glass_padding * (glass_rows - 1)),
				glass_width = (glass_container_width - glass_total_padding) / glass_rows,
				glass_height,
				glass_prevs_top = [],
				glass_prevs_height = [],
				glass_prev_top = glass_top,
				glass_prev_height = glass_height,
				glass_prev_total = glass_container_padding,
				glass_margin = 40;

			console.log("Displaying project list");
			
			projLoadInterval = setInterval(function() {
				console.log("setting project-" + i);
				
				if (glass_currentRow > 1) {
					glass_prev_height = glass_prevs_height[i - glass_rows];
					glass_prev_top = glass_prevs_top[i - glass_rows];
					glass_prev_total = glass_prev_height + glass_prev_top;
					console.info(i + ": got prevHeight [" + (i - glass_rows) + "]: " + glass_prev_total + " from prevTop: " + glass_prev_top + " and prevHeight: " + glass_prev_height);
				}
				
				glass_top = glass_prev_total;

				$('#project-' + i).addClass('fadeInUp')
								  .css({
								  	'visibility': 'visible',
								  	'width': glass_width,
								  	'top': glass_top,
								  	'left': glass_left
								  });

				glass_height = $('#project-' + i).height();

				console.log(i + ": g_top: " + $('#project-' + i).css('top') + ", height: " + $('#project-' + i).height());

				glass_left += (glass_width + glass_padding);

				glass_prevs_top[i] = glass_top;
				glass_prevs_height[i] = glass_height;

				if (((i + 1) % glass_rows == 0 && i != 0) || glass_rows === 1) {
					glass_left = glass_container_padding; 
					glass_currentRow++;
					console.log("row: " + glass_currentRow);
				}

				i++;

				if (i >= stopNum) {
					clearInterval(projLoadInterval);
				}
			}, 100);
		}

    </script>
	
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-76953501-1', 'auto');
  ga('send', 'pageview');
</script>

</html>
