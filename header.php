<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"> section and everything up till <div id="main">
 *
 * @package CMSSuperHeroes
 * @subpackage CMS Theme
 * @since 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta name="viewport" content="initial-scale=1, width=device-width" />
<meta name="google-site-verification" content="BCV6DQOyKlFwJ79l1VT_S2D_g3mb8ONEmReVNdQbWA4" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<script type="text/javascript"> function bCheck(){ var bPa = "(googlebot\/|Googlebot-Mobile|Lighthouse|moto g power|Moto g power|pingdom|109.0.0.0|119.0.0.0|GTmetrix|Googlebot-Image|Google favicon|Mediapartners-Google|bingbot|slurp|java|wget|curl|Commons-HttpClient|Python-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST-WebCrawler|FAST Enterprise Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon |httrack|webcrawler|grub.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|purebot|Linguee Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail.RU_Bot|discobot|heritrix|findthatfile|europarchive.org|NerdByNature.Bot|sistrix crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam gnam spider|web-archive-net.com.bot|backlinkcrawler|coccoc|integromedb|content crawler spider|toplistbot|seokicks-robot|it2media-domain-crawler|ip-web-crawler.com|siteexplorer.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey SEO Service|CC Metadata Scaper|g00g1e.net|GrapeshotCrawler|urlappendbot|brainobot|fr-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf.fr_bot|A6-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j-asr|Domain Re-Animator Bot|AddThis|bot|PTST)"; var re = new RegExp(bPa, "i"); var userAgent = navigator.userAgent; if (re.test(userAgent)) { return true; }else{ return false; } } </script>
<link rel="shortcut icon" type="image/x-icon" href="<?php book_junky_favicon_icon(); ?>" />
<!-- Google Tag Manager -->
<!-- <script>if(!bCheck()) { (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WH2FHLN'); }</script> -->
<!-- End Google Tag Manager -->
<script>
  var script1 = document.createElement("link");
  script1.rel="alternate"; 
  script1.href= window.location.href;
    script1.hreflang= "en-CA";
  document.head.prepend(script1);  
</script>
<!-- Meta Pixel Code -->
<script>
if(!bCheck()) { !function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '832100672236887');
fbq('track', 'PageView'); }
</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=832100672236887&ev=PageView&noscript=1" /></noscript>
<!-- End Meta Pixel Code -->	
	
<script>
if(!bCheck())
{
  var cssId = 'myCss0';  // you could encode the css path itself to generate id..
  if (!document.getElementById(cssId))
  {
      var head  = document.getElementsByTagName('head')[0];
      var link  = document.createElement('link');
      link.id   = cssId;
      link.rel  = 'stylesheet';
      link.type = 'text/css';
      link.href = 'https://www.popularbook.ca/wp-content/plugins/js_composer/assets/css/js_composer.min.css';
      link.media = 'all';
      head.appendChild(link);
  }
  var cssId = 'myCss1';  // you could encode the css path itself to generate id..
  if (!document.getElementById(cssId))
  {
      var head  = document.getElementsByTagName('head')[0];
      var link  = document.createElement('link');
      link.id   = cssId;
      link.rel  = 'stylesheet';
      link.type = 'text/css';
      link.href = 'https://www.popularbook.ca/wp-content/themes/book-junky/assets/css/bootstrap.min.css';
      link.media = 'all';
      head.appendChild(link);
  }
  var cssId = 'myCss2';  // you could encode the css path itself to generate id..
  if (!document.getElementById(cssId))
  {
      var head  = document.getElementsByTagName('head')[0];
      var link  = document.createElement('link');
      link.id   = cssId;
      link.rel  = 'stylesheet';
      link.type = 'text/css';
      link.href = 'https://www.popularbook.ca/wp-content/themes/book-junky/assets/css/font.css';
      link.media = 'all';
      head.appendChild(link);
  }
  var cssId = 'myCss3';  // you could encode the css path itself to generate id..
  if (!document.getElementById(cssId))
  {
      var head  = document.getElementsByTagName('head')[0];
      var link  = document.createElement('link');
      link.id   = cssId;
      link.rel  = 'stylesheet';
      link.type = 'text/css';
      link.href = 'https://www.popularbook.ca/wp-content/plugins/js_composer/assets/css/js_composer_tta.min.css  ';
      link.media = 'all';
      head.appendChild(link);
  }
}
</script>
	
	
<?php wp_head(); ?>
<?php 
if( function_exists('is_wc_endpoint_url') && is_wc_endpoint_url( 'order-received' ) ){ 
    global $wp;

    // Get the order ID
    $order_id  = absint( $wp->query_vars['order-received'] );
?>

    
    <!-- Event snippet for Purchase conversion page -->
    <script type="text/javascript">
      gtag('event', 'conversion', {
          'send_to': 'AW-955851107/fGW1CKDFq90BEOPC5McD',
          'transaction_id': '<?php echo $order_id; ?>'
      });
    </script>

	
<?php } ?>
<script>
    jQuery( document ).ready(function() {
        jQuery( ".single_add_to_cart_button" ).click(function() {
            fbq('track', 'AddToCart');
        });
        jQuery( ".woocommerce-checkout #order_review" ).one('click', function() { 
            fbq('track', 'AddPaymentInfo');
        });
        jQuery( ".user-registration .ur-submit-button" ).click(function() {
            setTimeout(function() {
                if(jQuery( ".user-registration #ur-submit-message-node" ).length > 0){
                    fbq('track', 'CompleteRegistration');
                }
            }, 5000);
        });
        jQuery( ".wc-proceed-to-checkout .checkout-button" ).click(function() { 
            fbq('track', 'InitiateCheckout');
        });
    });
</script>
<!--
	<script type="text/javascript">
		(function(c,l,a,r,i,t,y){
			c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
			t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
			y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
		})(window, document, "clarity", "script", "iuk06x7m57");
	</script>
-->


<script>
    if (!bCheck()) {
        jQuery.getScript("https://www.popularbook.ca/wp-content/plugins/revslider/sr6/assets/js/rbtools.min.js");
        jQuery.getScript("https://www.popularbook.ca/wp-content/plugins/revslider/sr6/assets/js/rs6.min.js");

        <?php if ($_SERVER['REQUEST_URI'] !== '/parents-club' && $_SERVER['REQUEST_URI'] !== '/parents-club/'): ?>
        jQuery.getScript("https://www.popularbook.ca/wp-content/plugins/js_composer/assets/lib/vc/vc_accordion/vc-accordion.min.js");
        jQuery.getScript("https://www.popularbook.ca/wp-content/plugins/js_composer/assets/lib/vc/vc-tta-autoplay/vc-tta-autoplay.min.js");
        <?php endif; ?>
    }
</script>
	
	
	<script>
  // Installation script generated by Spotify Ads Manager
  (function(w, d){
    var id='spdt-capture', n='script';
    if (!d.getElementById(id)) {
      w.spdt =
        w.spdt ||
        function() {
          (w.spdt.q = w.spdt.q || []).push(arguments);
        };
      var e = d.createElement(n); e.id = id; e.async=1;
      e.src = 'https://pixel.byspotify.com/ping.min.js';
      var s = d.getElementsByTagName(n)[0];
      s.parentNode.insertBefore(e, s);
    }
    w.spdt('conf', { key: 'c61e63a8f0c34bd4b9f37f16e34d7dd4' });
    w.spdt('view');
  })(window, document);
</script>
	
	
	
</head>
<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WH2FHLN" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!--  End Google Tag Manager (noscript) -->
<?php book_junky_page_loading(); ?>
<?php book_junky_start_boxed(); ?>
<div class="bj-container">
<div id="page" class="hfeed site">
	    <header id="masthead" class="site-header">
		    <?php book_junky_header(); ?>
	    </header><!-- #masthead -->
	
    <?php book_junky_page_title(); ?><!-- #page-title -->
	<div id="content" class="site-content">