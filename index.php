<?php
/*
 * Easy Imagemap Generator - Free Online Imagemapping Tool
 * build with
 *		-	PHP Logic
 *		-	jQuery Maphighlight
 *		-	CSS3 Layout
 *		-	Flash Uploadify
 *		-	HTML5 Upload
 * Date 	2012 for private use and since January 2013 for public
 * Version	v2.0
 * About	http://dariodomi.de
 * Copyright (c) by Dario D. Müller
 * GitHub -> https://github.com/DarioDomiDE/Imagemap-Generator
 */

require_once('config.php');
session_start();

?><!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	
	<!-- Copyright (c) 2014 by Dario D. Müller -->
	<!-- All rights reserved  -->
	<!-- Alle Rechte vorbehalten -->
	<title>Easy Imagemap Generator</title>
	
	<!-- Icon -->
	<link rel="shortcut icon" href="favicon.ico" />
	
	<!-- SEO -->
	<meta name="author" content="Dario D. Müller" />
	<meta name="publisher" content="http://dariodomi.de" />
	<meta name="copyright" content="(c) 2014 Dario D. Müller" />
	<meta name="page-topic" content="imagemap generator, html imagemaps, generator, html, map area generator, area imagemaps, links into images, links in images" />
	<meta name="page-type" content="html, imagemap, area, map, coordinate, generator, links, image" />
	<meta name="description" content="Easy Imagemap Generator for html image mapping. Select an image. Set links and clickable areas to your image. Get HTML code for Imagemaps." />
	<meta name="keywords" content="html, imagemap, generator, map, area, imagemap generator, creating map areas, html infos, newsletter" />
	
	<!-- Author -->
	<link rel="author" href="https://plus.google.com/113304109683958874741/" />
	<meta property="article:author" content="https://plus.google.com/113304109683958874741/" />
	<meta name="Author" content="Dario D. Müller">
	
	<!-- JS -->
	<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="js/jquery.maphilight.min.js"></script>
	<script type="text/javascript" src="js/jquery.uploadify-3.1.min.js"></script>
	<script type="text/javascript" src="js/script2.js"></script>
	
	<!-- CSS -->
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/snippet.css" type="text/css" media="screen" />
	
</head>

<?php

if(!is_dir($uploadDir))
{
	mkdir($uploadDir);
}

// delete old images from server -> 2 = 1 day
$lifetimeInDays = 2;
if ($handle = opendir($uploadDir))
{
	while (false !== ($file = readdir($handle)))
	{
		if($file != '.' && $file != '..')
		{
			$name = explode('_', $file);
			$dateStr = $name[0];
			$year = substr($dateStr, 0, 2);
			$month = substr($dateStr, 2, 2);
			$day = substr($dateStr, 4, 2);
            $date = new DateTime('20'.$year.'-'.$month.'-'.$day);
			$dateNow = new DateTime(date('Y-m-d', time() - 60*60*24*$lifetimeInDays));
			$interval = $date->diff($dateNow);
			$diff = (int)$interval->format('%R%a');
			if($diff >= 0)
			{
				// file is too old -> delete
				unlink($uploadDir.'/'.$file);
			}
		}
	}
	closedir($handle);
}

// Check Session set and Loading previous Image
$uploaded = false;
if(isset($_SESSION['image']) && $_SESSION['image'] != null && !empty($_SESSION['image'][0]) && substr_count($_SESSION['image'][0], '/') >= 1) {
	if(file_exists($_SESSION['image'][0]))
	{
		$uploaded = true;
	}
}

?>

<body>
	<header>
		<div id="header">
			<p><a href="http://imagemap-generator.dariodomi.de/"><img src="images/logo.png" alt="Imagemap Generator - Set links to image - image mapping tool" title="" id="logo" /></a></p>
			<p class="author">Easy Imagemap Generator by <a rel="author" href="https://plus.google.com/113304109683958874741/">Dario D. Müller</a></p>
			<div class="fork"><a href="https://github.com/DarioDomiDE/Imagemap-Generator" target="_blank"></a></div>
		</div>
	</header>
		<?php
			if($uploaded)
				echo '<div id="navi" currentValue="#imagemap4posis">';
			else
				echo '<div id="navi" currentValue="#upload">';
		?>
		<ul>
			<li><a href="#" rel="#upload" class="blue">Imagemap Generator</a></li>
			<li><a href="#" rel="#infos" class="purple">Infos</a></li>
			<li><a href="#" rel="#htmlcode" class="red">HTML Code</a></li>
			<li><a href="#" rel="#htmlinfos2" class="yellow">Applications</a></li>
			<li><a href="#" rel="#aboutinfos" class="green">About</a></li>
		</ul>
	</div>
	
	<div id="upload" class="effect infobox">
		<article>
			<div class="uploadContainer infobox2">
				<div id="uploadUndo"<?php if(!$uploaded) echo ' class="hidden"'; ?>></div>
				<h1>Easy Imagemap Generator</h1>
				<p>Easy Imagemap Generator for html image mapping. Select an image. Set links and clickable areas to your image. Get HTML code for Imagemaps.</p>
				<p>Just select an image from your PC or enter a image URL link, which you would like to map. For getting help, visit <a href="#" rel="#infos">Infos</a> page.</p>
				<p class="headline">Select a local file</p>
				
				<!-- HTML5 Drag End -->
				<form id="uploadForm" method="post" action="upload.php?v2" enctype="multipart/form-data">
					<div id="drop">
						<a>Image Upload <i class="icon icon-upload-2"></i></a>
						<input type="file" name="image" multiple />
					</div>
					<div id="uploadProcess"><!-- The file uploads will be shown here --></div>
				</form>
				
				<!-- Flash Upload Begin -->
				<form action="#" method="post" id="flashUpload" class="hidden">
					<input type="file" name="image" id="uploadify" class="hidden" />
					<script type="text/javascript">
						$(function() {
							<?php $timestamp = time(); ?>
							$('#uploadify').uploadify({
								'formData'      : {
									'timestamp' : '<?php echo $timestamp; ?>',
									'<?php echo session_name();?>' : '<?php echo session_id();?>'
								},
								'fileObjName'   : 'image',
								'fileTypeExts'  : '*.jpg; *.jpeg; *.gif; *.png; *.bmp, *tif, *tiff', 
								'buttonText'	: 'Image Upload (Flash) <i class="icon icon-upload-2"></i>',
								'swf'           : 'uploadify.swf',
								'uploader'      : 'upload.php',
								'width'			: 220,
								'height'		: 35,
								'onUploadSuccess'	: function(file, dataAuth, response) {
									
									var json = $.parseJSON(dataAuth);
									if(json['status'] == 'success') {
									
										// set upload to session
										jQuery.ajax({
											type: 'POST',
											url: 'upload_ident.php', 
											data: {'file': json['file'], 'width': json['width'], 'height': json['height']},
											dataType : 'json'
										});
										// hide upload area and show imagemap generator
										$('#imagemap4posis #mapContainer').find('img').attr('src', json['file']);
										//if(navigator.appName.indexOf("Internet Explorer") != -1 || navigator.userAgent.toLowerCase().indexOf('msie') != -1)
											//$('#imagemap4posis #mapContainer').find('img').attr('width', '').attr('height', '');
										
										removeErrorMessage();
										removeOldMapAndValues();
										$('#navi').attr('currentValue', '#imagemap4posis');
										
										$('#upload').slideUp(400, function() {
											$('#uploadUndo, #uploadUndo2').show();
											$('#imagemap4posis').slideDown(400, function() {
												resizeHtml();
											});
											loadImagemapGenerator(json['width'], json['height']);
										});
									} else if(json['status'] == 'error') {
										alert(json['message']);
									}
								}
								
							});
							if($('#uploadify-button').length == 0)
								$('#flashUploadSwitch').hide();
						});
					</script>
				</form>
				
				<!-- Switch between Flash and HTML5 Upload -->
				<p><a href="#" id="flashUploadSwitch">Switch to <span class="flash">Flash</span><span class="html5 hidden">HTML5</span> Upload</a></p>
					
				<p id="uploadUndo2"<?php if(!$uploaded) echo ' class="hidden"'; ?>>Back to my image <i class="icon icon-back"></i></p>
				<p class="headline">Or insert an image link</p>
				<form action="#" id="linkform">
					<input type="text" name="fileurl" value="" placeholder="http://www..." id="imageurl" class="insetEffect" />
					<a href="#" class="imageurl_submit"></a>
				</form>
				<p><i>After choosing a file, you can generate Imagemap<br />coordinates and HTML code by clicking into the image.</i></p>
			</div>
		</article>
	</div>
	
	<div id="infos" class="effect infobox">
		<article>
			<div class="infobox2">
				<h2>Infos: HTML Imagemaps</h2>
				<p>An imagemap is a HTML <b class="code">&lt;map&gt;</b> element, which can used with a <b class="code">&lt;img&gt;</b> to integrate links directly into an image. On the contrary to an &lt;a&gt;-tag, it allows to set several links into only one image.</p>
				<p>Imagemaps are one of the best ways to add multiple links. Really useful for linking banners, newsletter, e-mails or landingpages. Just specify severel areas. Each area stores some coordinates.</p>
				<p>Coordinates are pixel-values seperated with a comma and have an alignment from the left upper corner of the image. If you want to give the area a rectangle format, mark it as <b class="code">shape="rect"</b> into HTML and set two (left-top and right-bottom) coordinate-pairs. Example:</p>
				<img src="images/imagemap-area-info.png" alt="HTML img map area Infos" />
				<p>For each area you can set links as attribute <b class="code">href="www.google.com"</b>.</p>
				<p>This free image-mapping tool let you create the coordinates directly by clicking into the image - no programming knowledge required.</p>
			</div>
		</article>
	</div>
	
	<div id="htmlcode" class="effect infobox">
		<article>
			<div class="infobox2">
				<h2>HTML Code: &lt;img&gt;, &lt;map&gt; &amp; &lt;area&gt;</h2>
				<p>The clickable area can be rectangles <b class="code">shape="rect"</b>, polygons <b class="code">shape="poly"</b> or circles <b class="code">shape="circle"</b>.</p>
				<p>Shape-Values are coordiate-pairs. Every pair have a X and a Y value (from left/top of an image) separated with a comma. Every pair is as well separated with a comma.</p>
				<ul>
					<li><b>Rectangle:</b> Set four coordinates. <b>x1,y1,x2,y2</b></li>
					<li><b>Polygon:</b> Set as many coordinates as you want (a multiple of two)</li>
					<li><b>Circle:</b> One coordinate-pair and second value a radius. <b>x1,y1,radius</b></li>
				</ul>
				<h3>HTML Imagemap Demo Code with x / y</h3>
				<div class="sh_peachpuff snippet-wrap">
					<div style="display: none;" class="snippet-menu sh_sourceCode">
						<div class="snippet-clipboard" style="position: absolute; left: 0px; top: 0px; width: 0px; height: 0px; z-index: 99;">
							<embed width="0" height="0" align="middle" wmode="transparent" flashvars="id=1&amp;width=0&amp;height=0" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowfullscreen="false" allowscriptaccess="always" name="ZeroClipboardMovie_1" bgcolor="#ffffff" quality="best" menu="false" loop="false" src="snippet-highlighter/ZeroClipboard.swf" id="ZeroClipboardMovie_1">
						</div>
					</div>
					<pre class="context sh_html snippet-formatted sh_sourceCode"><ol class="snippet-num"><li><span class="sh_keyword">&lt;img</span> <span class="sh_type">src</span><span class="sh_symbol">=</span><span class="sh_string">"teaser.jpg"</span> <span class="sh_type">usemap</span><span class="sh_symbol">=</span><span class="sh_string">"#Teaser"</span> <span class="sh_type">alt</span><span class="sh_symbol">=</span><span class="sh_string">""</span> <span class="sh_keyword">/&gt;</span></li><li><span class="sh_keyword">&lt;map</span> <span class="sh_type">name</span><span class="sh_symbol">=</span><span class="sh_string">"Teaser"</span> <span class="sh_type">id</span><span class="sh_symbol">=</span><span class="sh_string">"Teaser"</span><span class="sh_keyword">&gt;</span></li><li>&nbsp;&nbsp;&nbsp;<span class="sh_keyword">&lt;area</span> <span class="sh_type">alt</span><span class="sh_symbol">=</span><span class="sh_string">""</span> <span class="sh_type">href</span><span class="sh_symbol">=</span><span class="sh_string">"#"</span> <span class="sh_type">coords</span><span class="sh_symbol">=</span><span class="sh_string">"x1,y1,x2,y2"</span> <span class="sh_type">shape</span><span class="sh_symbol">=</span><span class="sh_string">"rect"</span> <span class="sh_keyword">/&gt;</span></li><li>&nbsp;&nbsp;&nbsp;<span class="sh_keyword">&lt;area</span> <span class="sh_type">alt</span><span class="sh_symbol">=</span><span class="sh_string">""</span> <span class="sh_type">href</span><span class="sh_symbol">=</span><span class="sh_string">"#"</span> <span class="sh_type">coords</span><span class="sh_symbol">=</span><span class="sh_string">"x1,y1,x2,y2,x3,y3 [...] "</span> <span class="sh_type">shape</span><span class="sh_symbol">=</span><span class="sh_string">"poly"</span> <span class="sh_keyword">/&gt;</span></li><li>&nbsp;&nbsp;&nbsp;<span class="sh_keyword">&lt;area</span> <span class="sh_type">alt</span><span class="sh_symbol">=</span><span class="sh_string">""</span> <span class="sh_type">href</span><span class="sh_symbol">=</span><span class="sh_string">"#"</span> <span class="sh_type">coords</span><span class="sh_symbol">=</span><span class="sh_string">"x1,y1,radius"</span> <span class="sh_type">shape</span><span class="sh_symbol">=</span><span class="sh_string">"circle"</span> <span class="sh_keyword">/&gt;</span></li><li><span class="sh_keyword">&lt;/map&gt;</span></li></ol></pre>
				</div>
				<p>Use the &lt;area&gt; <b>href</b>-attribute to set links. It's also important to link the &lt;img&gt; with the &lt;map&gt; using <b>usemap</b>-attribute within the image. These value of the attribute must be the map's name-attribute.</p>
				<p>For using this software, you just need to click into your uploaded image. So quickly forget the HTML code you've learned before ;)</p>
			</div>
		</article>
	</div>
	
	<div id="htmlinfos2" class="effect infobox">
		<article>
			<div class="infobox2">
				<h2>Maps &amp; Newsletter with Imagemaps</h2>
				<p>Imagemaps are defined with HTML 3.2. Nowadays every Web-Browser and Mail-Client supports the &lt;map&gt;-tag without having problems.</p>
				<p>Popular applications are newsletter and e-mails with large teaser and landingpages, banner or world- / country-maps on websites.</p>
				<img src="images/worldmap.png" alt="" title="" />

				<table cellpadding="0" cellspacing="0">
					<tr>
						<td style="width: 220px;">
							<h3>Browser-Support</h3>
							<ul>
								<li>Internet Explorer 6+</li>
								<li>Mozilla Firefox</li>
								<li>Google Chrome</li>
								<li>Apple Safari</li>
							</ul>
						</td>
						<td valign="top">
							<h3>Mail-Clients</h3>
							<ul>
								<li>Outlook</li>
								<li>Thunderbird</li>
								<li>Apple Mail</li>
							</ul>
						</td>
					</tr>
				</table>
				<p><i>For more infos, visit your best friend Wikipedia: <a href="http://en.wikipedia.org/wiki/Image_map" target="_blank">Wikipedia: Image map</a>.</i></p>
			</div>
		</article>
	</div>
	
	<div id="aboutinfos" class="effect infobox">
		<article>
			<div class="infobox2">
				<h2>About this Imagemap Tool</h2>
				<img src="images/generator-html-thumb.gif" alt="" title="" />
				<p>This Software generates HTML Imagemaps and &lt;area&gt;-tags by clicking in an uploaded image.</p>
				<p>Usage:</p>
				<ul>
					<li>Upload or link an image</li>
					<li>Click into the image to set coordinates</li>
					<li>Copy the Imagemap HTML code</li>
				</ul>
				<h3>About me</h3>
				<p>Hi folks, My name is <a href="https://plus.google.com/113304109683958874741/" target="_blank">Dario</a>, I'm a web developer and freelance programmer in Hamburg, Germany. I build this tool for easily developing Newsletter and Landingpages.</p>
				<p>In some kinds of HTML, for example email templates, you don't have the opportunity to use special CSS hacks. It's better to use many images with a lot of links via image maps.</p>
				<p><b>PS: Thanks for your donations :-)</b></p>
			</div>
		</article>
	</div>
	
	<div id="imagemap4posis">
		<div id="newUpload"><span></span></div>
		<div id="urlMessage"><p class="effect">You can't see an image?<br /><a href="#">Please upload a new one &raquo;</a></p></div>
		<div id="mapContainer" class="effect">
			<?php
				$attr = '';
				if($uploaded && $_SESSION['image'][1] != 0 && $_SESSION['image'][2] != 0)
				{
					//$attr = ' width="'.$_SESSION['image'][1].'" height="'.$_SESSION['image'][2].'"';
				}
			?>
			<img draggable="true" ondrag="imageDragged(event)"src="<?php echo ($uploaded) ? $_SESSION['image'][0] : '#'; ?>"<?php echo $attr; ?> id="main" class="imgmapMainImage" alt="" usemap="#map" />
			<map name="map" id="map"></map>
		</div>
		<div class="form">
			<p>Click into the image to set coordinate values :)</p>
			<div id="clearStyleButtons">
				<div class="effect clearButton"><i class="icon icon-add"></i> Add Area</div>
				<div class="effect clearCurrentButton"><i class="icon icon-clear"></i> Clear Last</div>
				<div class="effect clearAllButton"><i class="icon icon-clear"></i> Clear All</div>
				<div class="effect textareaButton3"><i class="icon icon-upload"></i> Change Image</div>
			</div>
			<input id="coordsText" class="effect" name="" type="text" value="" placeholder="&laquo; Coordinates &raquo;" />
			<textarea name="" id="areaText" class="effect" placeholder="&laquo; HTML-Code &raquo;"></textarea>
		</div>
	</div>
	
	<div id="infotext">
		<address class="author"><b>Easy Imagemap Generator</b><br />
		Uploads are deleted after 1 day<br />
		This Software uses Google Analytics<br />
		Feedback? <span class="email"></span><br />
		Copyright &copy; <?php echo date('Y'); ?> by <a rel="author" href="https://plus.google.com/113304109683958874741/" title="Dario D. Müller">Dario D. Müller</a><br />
		</address>
	</div>
	<div id="info"></div>
	
	<div id="dots"></div>
	
	<div id="social">
		<?php $text = 'Easy%20Imagemap%20Generator%20for%20html%20image%20mapping:%20http://imagemap-generator.dariodomi.de'; ?>
		<!--<a href="https://www.xing.com/app/user?op=share;url=http://imagemap-generator.dariodomi.de" target="_blank" title="Share on Xing" class="xing" /></a>-->
		<!--
		<a href="https://www.facebook.com/sharer/sharer.php?u=http://imagemap-generator.dariodomi.de" target="_blank" title="Share on Facebook" class="facebook"></a>
		<a href="http://twitter.com/home?status=<?php echo $text; ?>" target="_blank" title="Share on Twitter" class="twitter" /></a>
		<a href="https://plus.google.com/share?url=http://imagemap-generator.dariodomi.de" target="_blank" title="Share on Google+" class="gplus" /></a>
		<a href="" title="Give Feedback" class="feedback" /></a>
		-->
		<div class="insetEffect paypal">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="3LJXDYJABWLTA">
				<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal">
				<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
			</form>
			<!--<span id="paypalAmount">3 € ?</span>-->
		</div>
	</div>
	
	<div id="feedbackPopup" class="insetEffect hidden">
		<span></span>
		<!--<a href="#"><i class="icon icon-clear-2"></i></a>-->
		<p>Did you find Imagemap Generator valuable? <!--Give feedback or buy me a beer <b>:-)</b>--></p>
	</div>
	
	<footer>
		<p>Project &copy; <?php echo date("Y"); ?> by <a href="http://dariodomi.de" target="_blank">Dario D. M&uuml;ller</a><!--<span></span><a href="http://dariodomi.de/contact" target="_blank">Feedback &amp; Contact</a>--></p>
	</footer>
	
	<!-- jQuery File Upload -->
	<script src="js/jquery.ui.widget.js"></script>
	<script src="js/jquery.iframe-transport.js"></script>
	<script src="js/jquery.fileupload.js"></script>
	<script src="js/script_upload.js"></script>
	
	<script type="text/javascript">
		/* init */
		$(function() {
			<?php if($uploaded) { ?>
				setTimeout(function() {
					$('#imagemap4posis').slideDown(400, function() {
						resizeHtml();
					});
					loadImagemapGenerator(0,0);
				}, 600);
			<?php } else { ?>
				$('#upload').delay(600).slideDown(400, function() {
					resizeHtml();
				});
				resizeHtml();
			<?php } ?>
		});
		
		/* Google Analytics */	
		var url=document.URL.split('/')[2];
		if(url != 'localhost') {
			var _gaq = _gaq || [];
			var pluginUrl = '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
			_gaq.push(['_require', 'inpage_linkid', pluginUrl]);
			_gaq.push(['_setAccount', 'UA-38069110-1']);
			_gaq.push (['_gat._anonymizeIp']);
			_gaq.push(['_trackPageview']);
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				//ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		}
	</script>
	
</body>
</html>