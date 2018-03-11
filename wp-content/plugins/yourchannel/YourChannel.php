<?php
/**
 * @package YourChannel
 * @version 0.9.3
 */
/*
	Plugin Name: YourChannel
	Plugin URI: http://plugin.builders/yourchannel/?from=plugins
	Description: YouTube channel on your website.
	Author: Plugin Builders
	Version: 0.9.3
	Author URI: http://plugin.builders/?from=plugins
	Text Domain: YourChannel
	Domain Path: languages
*/

if( !defined( 'ABSPATH' ) ) exit;

class WPB_YourChannel{
	static $version = '0.9.3';
	static $version_file = '0.9.3';
	static $terms = array();
	static $instances = 0;
	static $is_pro = false;
	
	function __construct(){
		self::translateTerms();
		
		register_activation_hook(__FILE__, array($this, 'onInstall'));
		$this->onInstall();
		
		add_action('admin_menu', array($this, 'createMenu'));
		add_action('admin_init', array($this, 'deploy'));
		add_action('plugins_loaded', array($this, 'loadTextDomain') );
		
		add_action('admin_enqueue_scripts', array($this, 'loadDashJs'));
		add_action('wp_enqueue_scripts', array($this, 'loadForFront'));
		
		add_action('wp_ajax_yrc_save', array($this, 'save'));
		add_action('wp_ajax_yrc_get', array($this, 'get'));
		add_action('wp_ajax_yrc_delete', array($this, 'delete'));
		add_action('wp_ajax_yrc_get_lang', array($this, 'getLang'));
		add_action('wp_ajax_yrc_save_lang', array($this, 'saveLang'));
		add_action('wp_ajax_yrc_delete_lang', array($this, 'deleteLang'));
		add_action('wp_ajax_yrc_clear_keys', array($this, 'clearKeys'));
		add_action('wp_ajax_yrc_clear_cache', array($this, 'clearCache'));
		
		add_shortcode( 'yourchannel', array($this, 'shortcoode') );
				
		$this->free();
	}
	
	static function dump(){
		echo '<pre>';
			call_user_func('var_dump', func_get_args());
		echo '</pre>';
	}
	
	public function onInstall(){
		update_option('yrc_version', WPB_YourChannel::$version);
	}	
	
	public function clearKeys(){
		$channels = (int)$_POST['yrc_content'];
		delete_option($channels ? 'yrc_keys' : 'yrc_playlist_keys');
		echo 1; die();
	}
	
	public function clearCache(){
		$channel_key = sanitize_text_field($_POST['yrc_chhanel_key']);
		update_option($channel_key.'_refresh', true);
		wp_send_json($channel_key);
	}
	
	public function createMenu(){
		add_submenu_page(
			'options-general.php',
			'YourChannel',
			'YourChannel',
			'manage_options',
			'yourchannel',
			array($this, 'pageTemplate')
		);
	}
	
	public function pageTemplate(){ ?>
		<div class="wrap">
			<div id="icon-themes" class="icon32"></div>
			<h2 class="wpb-inline" id="yrc-icon">Your<span class="wpb-inline">Channel</span></h2>
			<div id="yrc-wrapper" data-version="<?php echo self::$version; ?>">
				<img src="<?php echo site_url('wp-admin/images/spinner.gif'); ?>" id="yrc-init-loader"/>
			</div>
		</div>
		<?php
		$this->templates();
	}
	
	public function templates(){
		do_action('yrc_templates');
		include 'templates/templates.php';
	}
	
	public function deploy(){}
	
	public function loadDashJs($hook){
		if($hook === 'settings_page_yourchannel'){
			wp_register_script('yrc_script', plugins_url('/js/yrc.js?'.self::$version_file, __FILE__), array('jquery', 'underscore'), null, 1);
			wp_enqueue_script('yrc_script');
			
			wp_register_script('yrc_color_picker', plugins_url('/css/colorpicker/spectrum.js?'.self::$version_file, __FILE__), array('yrc_script'), null, 1);
			wp_enqueue_script('yrc_color_picker');
			wp_register_script('yrc_admin_settings', plugins_url('/js/admin.js?'.self::$version_file, __FILE__), array('yrc_color_picker'), null, 1);
			wp_enqueue_script('yrc_admin_settings');
			
			wp_register_style('yrc_color_picker_style', plugins_url('/css/colorpicker/spectrum.css?'.self::$version_file, __FILE__));
			wp_enqueue_style('yrc_color_picker_style');
			wp_register_style('yrc_admin_style', plugins_url('/css/admin.css?'.self::$version_file, __FILE__));
			wp_enqueue_style('yrc_admin_style');
			
			wp_register_style('yrc_style', plugins_url('/css/style.css?'.self::$version_file, __FILE__));
			wp_enqueue_style('yrc_style');
		}
	}
	
	public function loadForFront(){}
	
	public static function nins( $array, $key ){	//nothing if not set
		return isset( $array[$key] ) && $array[$key] ? $array[$key] : '';
	}
	
	public static function outputChannel( $user, $tag, $is_single ){
		$user = strtolower( html_entity_decode($user) );
		$tag = strtolower( html_entity_decode($tag) );
		
		$keys = get_option('yrc_keys');
		$key = '';
		if(sizeof($keys) && is_array($keys)){
			foreach($keys as $k){
				if( ( strtolower( $k['user'] ) === $user ) && ( strtolower( self::nins( $k, 'tag' ) ) === $tag ) ) {
					$key = $k['key']; break;
				}
			}	
						
			if( $is_single && !$key )
				$key = $keys[0]['key'];
		}
					
		return $key ? get_option( $key ) : '';
	}
		
	public function shortcoode($atts){
		$atts = shortcode_atts(
			apply_filters('yourchannel_shortcode_attrs', array(
				'user' => '',
				'tag' => '',
				'video' => ''
			)), $atts);
			
		return self::output( $atts );
	}
	
	static function getYTContent( $api_args ){
		require_once 'stream/stream.php';
		
		$stream = new YourChannelStream( $api_args );
		$stream->checkCache();
		
		$feed = array(); 
		
		if( sizeof($feed) ) return $feed;
				
		if( $api_args['show_uploads'] ){	
			$feed['uploads'] = $stream->requestFeed();
		}
		
		if( $api_args['show_banner'] ){
			$api_args['stream'] = 'banner';
			
			$stream->setSource( $api_args );
			$feed['banner'] = $stream->requestFeed();
		}
		
		if( $api_args['show_playlists'] ){
			$api_args['stream'] = 'playlists';
			$api_args['stream_value'] = $api_args['channel'];
			
			$stream->setSource( $api_args );
			$feed['playlists'] = $stream->requestFeed();
		}
		//echo intval($api_args['cache']);
		set_transient($stream->cache_key, $feed, intval($api_args['cache'])*60);
		
		return $feed;
	}
	
	public static function output( $atts ){ 
		//$channel =  empty( $channel ) ? self::outputChannel( $user, $tag ) : $channel;
		
		$channel =  self::outputChannel( $atts['user'], $atts['tag'], $atts['video'] );
		
		$error_msg = $atts['video']  ? 'Please create a channel in YourChannel page first.' : 'YourChannel: Wrong Shortcode';
		if(!$channel) return '<span id="yrc-wrong-shortcode" style="color:red" data-atts="'.json_encode($atts).'">'.$error_msg.'</span>';
		
		$channel = apply_filters('yrc_shortcode_atts', $channel, $atts);
		
		$uniqid = uniqid();
		$channel['meta']['uid'] = $uniqid;
		
		$refresh_cache = get_option($channel['meta']['key'] . $channel['meta']['tag'].'_refresh');
		if($refresh_cache) delete_option($channel['meta']['key'] . $channel['meta']['tag'].'_refresh');
		
		if( !$atts['video'] ){
			$parameters = array(
				'stream' => 'uploads',
				'stream_value' => $channel['meta']['channel'],
				'channel' => $channel['meta']['channel'],
				'sort_by' => implode('', array('v', 'i', 'e', 'w', 'C', 'o', 'u', 'n', 't')),
				'upload_playlist' => $channel['meta']['channel_uploads'],
				'show_uploads' => $channel['style']['uploads'],
				'show_playlists' => $channel['style']['playlists'],
				'show_banner' => $channel['style']['banner'],
				'cache' => isset($channel['meta']['cache']) ? $channel['meta']['cache'] : 180,
				'limit' => 12*2
			);
			
			if( $channel['meta']['onlyonce'] ){
				$parameters['stream'] = 'playlist';
				$parameters['stream_value'] = $parameters['upload_playlist'];
			}
			
			if( $refresh_cache )
				$parameters['refresh'] = 'REFRESH';
					
			$feed = self::getYTContent( apply_filters('yrc_fetch_parameters', $parameters, $channel) );
		} else {
			$channel['meta']['single'] = true;
			$feed = array('video' => $atts['video']);
		}
								
		$url = plugins_url('/js/yrc.js?'.self::$version_file, __FILE__);
		$css_url = plugins_url('/css/style.css?'.self::$version_file, __FILE__);
		
		self::translateTerms();
		$terms = array(
			'form' => get_option('yrc_lang_terms'),
			'fui' => self::$terms['front_ui']
		);
						
		$terms['form'] = $terms['form'] ? $terms['form'] : self::$terms['form'];		
		$feed = apply_filters('yrc_final_output', $feed, $channel);
		
		$channel['style']['rtl'] = is_rtl();
			
		$output = '<div class="yrc-shell-cover '.($atts['video'] ? 'yrc-single' : '').'" data-yrc-uid="'. $uniqid .'" data-yrc-channel="'. htmlentities( json_encode($channel) ) .'" data-yrc-setup=""></div>
			<script data-cfasync="false" type="text/javascript">
				if( !window.YRC ) var YRC = {Data:{}};
				YRC.Data["'.$uniqid.'"] = '. json_encode($feed) .';
				'. (0 ? '' :
				'(function(){
					if(!YRC.loaded){
						function YRC_Loader(){
							YRC.loaded = true;
							YRC.is_pro = '.json_encode(self::$is_pro) .';
							YRC.lang = '.json_encode( $terms ).';	
							var script = document.createElement("script");
								script.setAttribute("data-cfasync", "false");
								script.setAttribute("type", "text/javascript");
								script.src = "'.$url.'";
								script.id = "yrc-script";
								document.querySelector("head").appendChild(script);
							var style = document.createElement("link");
								style.rel = "stylesheet";
								style.href = "'.$css_url.'";
								style.type = "text/css";
								document.querySelector("head").appendChild(style);
						}
						if(window.jQuery){YRC_Loader();}else { var yrctimer2324 = window.setInterval(function(){
							if(window.jQuery){YRC_Loader(); window.clearInterval(yrctimer2324); }
						}, 250);}
					} else {if(YRC.EM)YRC.EM.trigger("yrc.newchannel");}
				}());'
				).
			'</script>';
				
		self::$instances++;
		return $output;
	}
	
	
	/**
	
		Input
		
	**/
	
	
	public function save(){
		$down = $this->validate( $_POST['yrc_channel'] );
		
		if(!$down['meta']['channel'] || !$down['meta']['apikey']) {echo 0; die();}
		
		$re = null;
		$key = $down['meta']['key'];
		$down['meta']['user'] = stripslashes( $down['meta']['user'] );
		$down['meta']['tag'] = stripslashes( $down['meta']['tag'] );
		if(isset( $down['css'] )) $down['css'] = stripslashes( $down['css'] );
				
		if($key === 'nw'){
			$re = get_option('yrc_keys');
			$re = $re ? $re : array();
			$key = 'yrc_'.time();
			$re[] = array('key'=>$key, 'user'=>$down['meta']['user'], 'tag'=>$down['meta']['tag']);
			$re = update_option('yrc_keys', $re);
			$down['meta']['key'] = $key;
			$re = update_option($key, $down);
			$re = $re ? $key : $re;
		} else {
			$re = get_option('yrc_keys');
			forEach($re as &$r){
				$tag = true;
				if(isset($r['tag']) && !empty($r['tag'])) $tag = ($r['tag'] === $down['meta']['tag']);
				if($r['user'] !== $down['meta']['user']) $tag = true;
				if( ($r['key'] === $down['meta']['key']) && $tag ) {
					$r['user'] = $down['meta']['user'];
					$r['tag'] = $down['meta']['tag'];
					update_option('yrc_keys', $re);
					$re = update_option($down['meta']['key'], $down);
					break;
				}
			}
			$re = $key ? $key : $re;
		}
		wp_send_json($re);
	}
		
	public function get(){
		$keys = get_option('yrc_keys');
		$re = array();
		if($keys){
			forEach($keys as $key){
				$re[] = get_option($key['key']);
			}
		}
		wp_send_json($re);
	}
	
	public function delete(){
		$key = sanitize_text_field( $_POST['yrc_key'] );
		$keys = get_option('yrc_keys');
		$re = false;
		forEach($keys as $i=>$k){
			if($k['key'] === $key) {
				unset($keys[$i]);
				update_option('yrc_keys', $keys);
				$re = delete_option( $key );
				break;
			}
		}	
		echo $re;
		die();
	}
	
	public function getLang(){
		wp_send_json( get_option('yrc_lang_terms') );
	}
	
	public function saveLang(){
		$lang = $_POST['yrc_lang'];
		echo update_option('yrc_lang_terms', $lang);
		die();
	}
	
	public function deleteLang(){
		delete_option('yrc_lang_terms');
		echo 1;
		die();
	}
		
	/**
	
		Sanitizing
		
	**/
	
	public $fields = array();
	
	public function validate($ins){
		$rins = $this->validation( $ins );
		return $rins;
	}
	
	public function validation( $ins ){
		$rins = array();
		foreach($ins as $key=>$value){
			$rins[$key] = $this->validateField( $key, $value );
		}
		return $rins;
	}
	
	public function validateField( $k, $val ){
		if(is_array($val)){
			$clean_val = $this->validation( $val );
		} else {
			$clean_val = $this->cleanse(
				( array_key_exists($k, $this->fields) ? $this->fields[$k] : 'string' ),
			$val);
		}
		return $clean_val;
	}
	
	public function cleanse($type, $value){
		switch($type){
			case 'int':
				return intval($value);
				break;
			case 'url':
				return esc_url($value);
				break;
			default:
				return sanitize_text_field($value);
				break;
		} 
	}
	
	public function loadTextDomain(){
		load_plugin_textdomain( 'YourChannel', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	
	public static function translateTerms(){ 
		self::$terms['front_ui'] = array(
			'sort_by'  => __('Sort by', 'YourChannel'),
			'relevant'  => __('Relevant', 'YourChannel'),
			'latest'  => __('Latest', 'YourChannel'),
			'liked'  => __('Liked', 'YourChannel'),
			'title'  => __('Title', 'YourChannel'),
			'views'  => __('Views', 'YourChannel'),
			'duration'  => __('Duration', 'YourChannel'),
			'any'  => __('Any', 'YourChannel'),
			'_short'  => __('Short', 'YourChannel'),
			'medium'  => __('Medium', 'YourChannel'),
			'_long'  => __('Long', 'YourChannel'),
			'uploaded'  => __('Uploaded', 'YourChannel'),
			'all_time'  => __('All time', 'YourChannel'),
			'today'  => __('Today', 'YourChannel'),
			'ago'  => __('ago', 'YourChannel'),
			'last'  => __('Last', 'YourChannel'),
			'day'  => __('day', 'YourChannel'),
			'days'  => __('days', 'YourChannel'),
			'week'  => __('week', 'YourChannel'),
			'weeks'  => __('weeks', 'YourChannel'),
			'month'  => __('month', 'YourChannel'),
			'months'  => __('months', 'YourChannel'),
			'year'  => __('year', 'YourChannel'),
			'years'  => __('years', 'YourChannel'),
			'older'  => __('Older', 'YourChannel'),
			'million'  => 'M',
			'thousand'  => 'K',
			'wplocale' => get_locale()
		);
		
		self::$terms['form'] = array(
			'Videos'  => __('Видео', 'YourChannel'),
			'Playlists'  => __('Плейлисты', 'YourChannel'),
			'Search'  => __('Поиск', 'YourChannel'),
			'Loading'  => __('Загрузка', 'YourChannel'),
			'more'  => __('еще', 'YourChannel'),
			'Nothing found'  => __('Ничего не найдено', 'YourChannel'),
			'Previous' => __('Предыдущее', 'YourChannel'),
			'Next' => __('Следующее', 'YourChannel')
		);
		
		self::$terms = apply_filters('yourchannel_front_ui_terms', self::$terms);
	}
	
	
	/**		Free Version Specific	**/
	
	public function free(){
		add_action('admin_notices', array($this, 'showProFeature'));
		add_action('wp_ajax_yrc_upgrade_nag_dismiss', array($this, 'upgradeNagDismiss'));
	}
	
	public $nags = 13;
	public $max_nags = 4;
	public $nag_key = 'yrc_upgrade_nag_dismisses';
	
	public function upgradeNagDismisses( $add = false ){
		$nags = get_option($this->nag_key);
		$nags = $nags ? array((int)$nags[0], (int)$nags[1]) : array(0, 0);
		$nags[1] += ($nags[0] >= $this->nags) ? 1 : 0;
		$nags[0] = $nags[0] >= $this->nags ? 0 : (($add || $nags[0]) ? ($nags[0]+1) : $nags[0]);
		update_option($this->nag_key, $nags);
		return $nags;
	}
	
	public function upgradeNagDismiss(){
		$this->upgradeNagDismisses( true );
		wp_send_json(1);
	}
	
	public function showProFeature(){
		if( get_admin_page_title() === 'YourChannel' ) return false;
		
		$nags = $this->upgradeNagDismisses();
		if(($nags[0] && ($nags[0] <= $this->nags)) || ($nags[1] > $this->max_nags)) return false;
				
		$notice = $this->proFeatures( true ); ?>
			<div class="updated yrc-nag">
				<p>
					<span style="display:inline-block;width:90%;">
						<b>YourChannel Pro Feature: </b>
						<a href="http://plugin.builders/yourchannel?notice=<?php echo WPB_YourChannel::$version; ?>" target="_blank">
							<?php echo $notice; ?>
						</a>
					</span><span style="text-align:right;display:inline-block;width:10%;">
						<a href="#dismiss" id="yrc-later" style="color:#E68B8B;">X</a>
					</span>
				</p>
			</div>
			<script type="text/javascript">
				jQuery('body').on('click', '#yrc-later', function(e){
					e.preventDefault();
					jQuery('.yrc-nag p').html('Ok, we\'ll ask you again.');
					window.setTimeout(function(){
						jQuery('.yrc-nag').slideUp();
					}, 1000);
					jQuery.post('admin-ajax.php', {'action':'yrc_upgrade_nag_dismiss'}, function(re){
						console.log(re);
					});
				});
			</script>
		<?php	
		
		if($nags[1] === $this->max_nags){
			update_option( $this->nag_key, array((int)$nags[0], (int)$nags[1]+1) );
			echo '<div class="updated yrc-nag"><p>We won\'t ask you to upgrade anymore. Thanks for using <a href="http://plugin.builders/yourchannel?notice">YourChannel</a></p></div>';
		}	
	}
	
	public function proFeatures( $random = false ){
		$features = array(
			'Multiple channels.',		
			'List videos from a certain playlist in the <i>Videos</i> section.',
			'Let users search YouTube - can be restricted to your channel.',
			'Search bar below banner.',
			'Show videos by a search term.',
			'Custom playlists (make playlists in YourChannel by entering video IDs). <b>New</b>',
			'Change colors to match with your site.',
			'Show video stats/ratings (2 styles).',
			'Limit number of videos on page.',
			'Sort uploads (latest, most liked, most viewed).',
			'Autoplay next video.',
			'Preload any or first video.',
			'Custom CSS input.',
			'Show a subscribe button (multiple styles).',
			'Show other social media links in banner.',
			'Specify grid column numbers. <b>New</b>',
			'Blacklist videos. <b>New</b>',
			'Blacklist playlists. <b>New</b>',
			'Pagination with Previous / Next buttons. <b>New</b>',
			'Widget.',
			'More themes: Slider, Carousel. <b>New</b>',
			'Different theme for Playlists',
			'Customize single video (Show video title, description, stats). <b>New</b>',
			'<a href="http://plugin.builders/yourchannel/?from=wp&v='.WPB_YourChannel::$version.'&compare=1#compare" target="_blank">See Free vs PRO comparison table</a>'
		);
		if($random) return $features[ rand(0, sizeof($features)-1) ];
		
		foreach($features as $f){
			echo '<li>'. $f .'</li>';
		}
	}	
	
	function checkIfHasLicense(){
		$params = array(
			'pbr_action' => 'get_name',
			'pbr_license' => get_option('yrc_license_key'),
			'pbr_check_if_pro' => true
		);
		
		if( !$params['pbr_license'] || self::$is_pro ) return false;
		if( $notice = get_option('yrc_wrong_version_notice') ) { echo $notice; return false; }
		
		$request = wp_remote_post( 'http://plugin.builders/', array( 'timeout' => 15, 'sslverify' => false, 'body' => $params ) );
		if ( ! is_wp_error( $request ) ) {
			$request = wp_remote_retrieve_body( $request );
			update_option('yrc_wrong_version_notice', $request);
			echo $request;
		}
	}
}


new WPB_YourChannel();
?>
