<?php namespace IFSSE;

final class Server {

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//
	// private
	//
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    private static function last_id(){
        global $wpdb;
        $query = "SELECT ID FROM $wpdb->posts WHERE post_status = 'private' AND post_type = 'if-server-sent-event' ORDER BY ID DESC LIMIT 1";
        return absint($wpdb->get_var($query));
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    private static function penultimo_id(){
        global $wpdb;
        $query = "SELECT ID FROM $wpdb->posts WHERE post_status = 'private' AND post_type = 'if-server-sent-event' ORDER BY ID DESC LIMIT 2";
        return absint($wpdb->get_var($query, 0, 1));
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    private static function last_ids($last_id = 0, $limit = 0){
        global $wpdb;
        $limit = absint($limit);
        if($limit){
            $str = "SELECT ID FROM $wpdb->posts WHERE ID > %d AND post_status = 'private' AND post_type = 'if-server-sent-event' ORDER BY ID ASC LIMIT %d";
            $sql = $wpdb->prepare($str, $last_id, $limit);
        } else {
            $str = "SELECT ID FROM $wpdb->posts WHERE ID > %d AND post_status = 'private' AND post_type = 'if-server-sent-event' ORDER BY ID ASC";
            $sql = $wpdb->prepare($str, $last_id);
        }
        return $wpdb->get_col($sql);
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    private static function push($post_id = 0){
        $data = get_post_meta($post_id, 'ifsse_data', true);
        $event = get_post_meta($post_id, 'ifsse_event', true);
        $retry = 1000;
        echo 'event: ' . $event . PHP_EOL;
		echo 'data: ' . wp_json_encode($data) . PHP_EOL;
		echo 'id: ' . $post_id . PHP_EOL;
		echo 'retry: ' . $retry . PHP_EOL;
		echo PHP_EOL;
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//
	// public
	//
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    public static function init(){
        register_post_type('if-server-sent-event', [
            'labels' => Utilities::post_type_labels('Server-sent event', 'Server-sent events', false),
            'menu_icon ' => 'dashicons-database-add',
            'show_in_admin_bar' => false,
            'show_ui' => true,
            'supports' => ['custom-fields', 'title'],
        ]);
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    public static function load(){
		add_action('init', [__CLASS__, 'init']);
		add_action('parse_request', [__CLASS__, 'parse_request']);
		add_action('wp_enqueue_scripts', [__CLASS__, 'wp_enqueue_scripts']);
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	public static function parse_request(&$wp){
		if('ifsse-server' !== $wp->request){
			return;
		}
		header('Access-Control-Allow-Origin: *');
        header('Cache-Control: no-cache');
        header('Content-Type: text/event-stream');
        if(!empty($_SERVER['HTTP_LAST_EVENT_ID'])){
            $last_event_id = absint($_SERVER['HTTP_LAST_EVENT_ID']);
        } elseif(!empty($_GET['last_event_id'])){
            $last_event_id = absint($_GET['last_event_id']);
        } else {
            $last_event_id = self::penultimo_id();
        }
        $last_id = self::last_id();
        if($last_id > $last_event_id){
            $last_ids = self::last_ids($last_event_id, 10);
            foreach($last_ids as $last_id){
                self::push($last_id);
            }
    	}
        ob_flush();
        flush();
		exit;
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    public static function send($data = [], $event = 'message'){
        $post_id = wp_insert_post([
			'post_type' => 'if-server-sent-event',
        ], true);
        if(is_wp_error($post_id)){
            return $post_id;
        }
        update_post_meta($post_id, 'ifsse_data', $data);
        update_post_meta($post_id, 'ifsse_event', $event);
        return wp_update_post([
			'ID' => $post_id,
            'post_status' => 'private',
            'post_title' => 'ID: ' . $post_id . ' - event: ' . $event,
        ], true);
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    public static function wp_enqueue_scripts(){
        wp_enqueue_script('jquery-sse', plugin_dir_url(Loader::get_file()) . 'assets/jquery.sse.min.js', ['jquery'], '0.1.4', true);
		wp_localize_script('jquery-sse', 'ifsse', [
			'server_url' => site_url('ifsse-server/'),
		]);
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

}
