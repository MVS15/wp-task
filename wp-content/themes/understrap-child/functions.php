<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $the_theme->get( 'Version' ) );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $the_theme->get( 'Version' ), true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );

add_action('init', function() {
	register_post_type('estate', [
		'labels' => [
			'name' => 'Недвижимость',
			'singular_name' => 'Недвижимость'
		],
		'public' => true,
		'supports' => ['title', 'editor', 'thumbnail'],
	]);
	
	register_post_type('city', [
		'labels' => [
			'name' => 'Город',
			'singular_name' => 'Город'
		],
		'public' => true,
		'supports' => ['title', 'editor', 'thumbnail'],
	]);
	
	register_taxonomy('estate-type', 'estate', [
		'hierarchical' => true,
		'labels' => [
			'name' => 'Тип недвижимости',
			'singular_name' => 'Тип недвижимости'
		]
	]);
	
	if (
		current_user_can('manage_options') && 
		isset($_GET['add-estate']) &&
		$_GET['add-estate'] == 1
	) {
		$texts = explode('.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla accumsan ante congue eros interdum, ut consequat massa malesuada. Fusce enim lorem, tristique in dui quis, euismod ornare metus. Praesent dignissim sem eget pharetra lacinia. Sed mauris magna, tincidunt in luctus sed, ultricies a mi. Donec nunc dolor, ullamcorper in ultrices in, fermentum eget ipsum. Aliquam fringilla, felis et aliquet imperdiet, ex nisi maximus leo, ac ultrices urna ligula a ligula. Praesent tristique, ante vel ullamcorper pulvinar, urna magna laoreet mauris, a iaculis lorem odio vitae est. Mauris nulla quam, porta a tortor a, interdum aliquet justo. Nullam et tellus sit amet ex suscipit consequat sit amet eu mauris. Duis eleifend quis leo in semper. Ut dapibus lacinia enim non finibus. Ut aliquam elementum nulla, quis consectetur metus blandit egestas. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Ut at metus eu erat maximus hendrerit. Maecenas finibus sem non posuere facilisis. Cras ut varius metus. Pellentesque ac dolor venenatis, fringilla ipsum id, bibendum velit. Donec augue augue, lobortis vitae laoreet nec, dictum ut dolor. Proin diam lorem, maximus nec faucibus quis, facilisis eget nisl. Sed dictum lacinia velit, vitae bibendum orci porttitor et. Nunc posuere luctus posuere. Aliquam imperdiet, nulla ac pulvinar volutpat, lacus odio sollicitudin diam, at finibus erat ex et mauris. Sed blandit, felis at finibus ultricies, dui sem sollicitudin turpis, eget aliquam tortor nulla non orci. Vivamus malesuada sit amet justo eget interdum. Morbi vel erat sed ante sagittis pharetra. Sed congue purus sit amet mollis congue. Maecenas tempor dapibus massa, et varius mauris volutpat ac. Mauris lacus ex, cursus aliquam massa ac, vulputate aliquet enim. Maecenas eu arcu magna. Nulla tempus dictum quam, tristique posuere eros faucibus ut. In ligula sapien, pellentesque sit amet ornare et, facilisis a magna. Maecenas placerat, arcu quis imperdiet sodales, velit dui ultricies augue, sed molestie metus quam ac sapien. Curabitur maximus, nulla eu posuere ullamcorper, ipsum quam eleifend est, et tempor arcu leo vitae lorem. Aenean pellentesque viverra mi, vitae pharetra erat fringilla a. Vestibulum ac mauris quis diam consectetur lobortis nec ac sapien. Phasellus est nisl, efficitur sit amet commodo a, lacinia ut ipsum. Praesent quam eros, pretium mattis tincidunt sed, malesuada vitae nunc. Proin posuere rhoncus tellus, eu iaculis ipsum vestibulum eget. Sed vulputate sit amet felis vitae euismod. Vivamus eu odio magna. Vestibulum tempus mauris dolor, ac fringilla risus efficitur vehicula. Interdum et malesuada fames ac ante ipsum primis in faucibus. Donec sodales, nisi non congue consectetur, dolor odio tempor tellus, ut gravida purus velit non lorem. Praesent consectetur venenatis tempor.');
		
		$thumb_ids = [128, 129, 130, 131, 132, 133];
		$city_ids = [17, 16, 15, 14];
		$type_ids = [2, 3, 4];
		
		$streets = [
			'ул. Домодедовская',
			'ул. Космонавтов',
			'ул. Гоголя',
			'ул. Сталина',
			'ул. Будапештсткая',
			'ул. Чехова',
			'ул. Ломоносова',
		];
		
		$estates = [];
		
		echo '<table border="1">';
		
		echo '<tr>
			<td>Картинка</td>
			<td>Тип</td>
			<td>Город</td>
			<td>Адрес</td>
			<td>Цена</td>
			<td>Площадь</td>
			<td>Жилая площадь</td>
			<td>Этаж</td>
			<td>Контент</td>
		</tr>';
		
		for ($i = 0; $i < 10; $i++) {
			$content = '';
			
			for ($j = 0; $j < 10; $j++) {
				$text = trim($texts[rand(0, count($texts) - 1)]);
				
				if ($text == '') continue;
				
				$content .= $text.'. ';
			}
			
			$content = trim($content);
			
			$thumb_id = $thumb_ids[rand(0, count($thumb_ids) - 1)];
			
			$type_id = $type_ids[rand(0, count($type_ids) - 1)];
			$type_term = get_term_by('id', $type_id, 'estate-type');
			
			$city_id = $city_ids[rand(0, count($city_ids) - 1)];
			$city_title = get_the_title($city_id);
			
			$address = $streets[rand(0, count($streets) - 1)].', '.rand(1, 100);
			$price = rand(1000000, 9000000);
			$area = rand(21, 70);
			$living_area = $area - rand(3, 5);
			$floor = rand(1, 9);
			
			$title = $type_term->name.' по адресу: г. '.$city_title.', '.$address;
			
			echo '<tr>
				<td>'.wp_get_attachment_thumb_url($thumb_id).'</td>
				<td>'.$type_term->name.'</td>
				<td>'.$city_title.'</td>
				<td>'.$address.'</td>
				<td>'.$price.'</td>
				<td>'.$area.'</td>
				<td>'.$living_area.'</td>
				<td>'.$floor.'</td>
				<td>'.$content.'</td>
			</tr>';
			
			$post_id = wp_insert_post([
				'post_title' => $title,
				'post_status' => 'publish',
				'post_type' => 'estate',
				'post_content' => $content,
			]);
			
			if ($post_id) {
				update_post_meta($post_id, 'city_id', $city_id);

				update_field('address', $address, $post_id);
				update_field('price', $price, $post_id);
				update_field('area', $area, $post_id);
				update_field('living_area', $living_area, $post_id);
				update_field('floor', $floor, $post_id);
				
				wp_set_object_terms($post_id, $type_id, 'estate-type');
				
				set_post_thumbnail($post_id, $thumb_id);
			}
		}
		
		echo '</table>';
		
		exit();
	}
});

add_filter('get_the_excerpt', function($excerpt, $post) {
    $excerpt = wp_strip_all_tags($post->post_content);
    
	return wp_trim_words($excerpt, 10, '...');
}, 10, 2 );

function modify_content($content) {
	if (get_post_type() != 'estate') return $content; 
	
	$type = implode(', ', array_map(function($item) {
		return $item->name;
	}, get_the_terms(0, 'estate-type')));
	
	$city_title = get_post_field('post_title', get_field('city_id'));
	
	$extra_content = '<p class="mt-3"><b>Тип:</b> '.$type.'</p>';
	$extra_content .= '<p><b>Город:</b> '.$city_title.'</p>';
	$extra_content .= '<p><b>Адрес:</b> '.get_field('address').'</p>';
	$extra_content .= '<p><b>Цена:</b> '.get_field('price').'</p>';
	$extra_content .= '<p><b>Площадь:</b> '.get_field('area').'</p>';
	$extra_content .= '<p><b>Жилая площадь:</b> '.get_field('living_area').'</p>';
	$extra_content .= '<p><b>Этаж:</b> '.get_field('floor').'</p>';
	
	return $extra_content.$content;
}

add_filter('the_excerpt', 'modify_content', 99);
add_filter('the_content', 'modify_content', 99);

add_action( 'add_meta_boxes_estate', function() {
    add_meta_box('estate_city_meta_box', 'Город', function($post) {
		$city_id = get_post_meta($post->ID, 'city_id', true);
		
		$cities = get_posts([
			'numberposts' => 0,
			'orderby'     => 'title',
			'order'       => 'asc',
			'post_type'   => 'city',
		]);
		
		echo '<select name="city_id">';
		
		foreach ($cities as $city){
			echo '<option '.($city_id == $city->ID ? 'selected' : '').' value="'.$city->ID.'">'.$city->post_title.'</option>';
		}
		
		echo '</select>';
	}, 'estate', 'side', 'low' );
});

add_action('save_post_estate', function($post_id) {
	if (isset($_POST['city_id'])) {
		update_post_meta($post_id, 'city_id', $_POST['city_id']);
	}
});

add_shortcode('city_list', function($atts) {
	$content = '<h2>Города</h2>';
	
	$cities = get_posts([
		'numberposts' => 0,
		'orderby'     => 'title',
		'order'       => 'asc',
		'post_type'   => 'city',
	]);
	
	$content .= '<ul>';
	
	foreach ($cities as $city){
		$content .= '<li>
			<a href="'.get_the_permalink($city->ID).'">'.$city->post_title.'</a>
		</li>';
	}
	
	$content .= '</ul>';
	
	return $content;
});

add_shortcode('estate_form', function($atts) {
	if (!current_user_can('manage_options')) return '';
	
	$content = '<h2>Добавить объявление</h2>';
	
	$cities = get_posts([
		'numberposts' => 0,
		'orderby'     => 'title',
		'order'       => 'asc',
		'post_type'   => 'city',
	]);

	$types = get_terms('estate-type', [
		'hide_empty' => false,
	]);
	
	$content .= '<form class="estate-form">';
		$content .= '<input type="hidden" name="action" value="add_estate">';
		
		$content .= '<div class="form-group">
			<label>Название</label>
			<input type="text" name="title" class="form-control">
		</div>';
		
		$content .= '<div class="form-group mt-2">';
			$content .= '<label>Тип</label>';
			
			$content .= '<select class="form-control" name="type_id">';
			
			foreach ($types as $type){
				$content .= '<option value="'.$type->term_id.'">'.$type->name.'</option>';
			}
			
			$content .= '</select>';
		$content .= '</div>';
		
		$content .= '<div class="form-group mt-2">';
			$content .= '<label>Город</label>';
			
			$content .= '<select class="form-control" name="city_id">';
			
			foreach ($cities as $city){
				$content .= '<option value="'.$city->ID.'">'.$city->post_title.'</option>';
			}
			
			$content .= '</select>';
		$content .= '</div>';
		
		$content .= '<div class="form-group mt-2">
			<label>Адрес</label>
			<input type="text" name="address" class="form-control">
		</div>
		
		<div class="form-group mt-2">
			<label>Цена</label>
			<input type="text" name="price" class="form-control">
		</div>
		
		<div class="form-group mt-2">
			<label>Площадь</label>
			<input type="text" name="area" class="form-control">
		</div>
		
		<div class="form-group mt-2">
			<label>Жилая площадь</label>
			<input type="text" name="living_area" class="form-control">
		</div>
		
		<div class="form-group mt-2">
			<label>Этаж</label>
			<input type="text" name="floor" class="form-control">
		</div>
		
		<div class="form-group mt-2">
			<label>Описание</label>
			<textarea name="descr" class="form-control"></textarea>
		</div>
		
		<button type="submit" class="btn btn-primary mt-3">Отправить</button>';
	$content .= '</form>';
	
	add_action('wp_footer', function() {
		echo '<script>
			jQuery(".estate-form").submit(function(e) {
				e.preventDefault();
				
				jQuery(this).find("[type=submit]").prop("disabled", true);
				
				jQuery.post("/wp-admin/admin-ajax.php", jQuery(this).serialize(), (s) => {
					if (s != "") alert("Ошибка!");
					
					jQuery(this).get(0).reset();
					jQuery(this).find("[type=submit]").prop("disabled", false);
				});
				
				
			});
		</script>';
	}, 99); 
	
	return $content;
});

function add_estate() {
	global $wpdb;
	
	$title = trim($_POST['title']);
	$type_id = trim($_POST['type_id']);
	$city_id = trim($_POST['city_id']);
	$address = trim($_POST['address']);
	$price = trim($_POST['price']);
	$area = trim($_POST['area']);
	$living_area = trim($_POST['living_area']);
	$floor = trim($_POST['floor']);
	$descr = trim($_POST['descr']);
	
	if (!current_user_can('manage_options')) echo 2;
	
	if (
		$title == '' || $type_id == '' || $city_id == '' || 
		$address == '' || $price == '' || $area == '' || 
		$living_area == '' || $floor == ''
	) {
		echo 1;
		
		exit();
	}
	
	$post_id = wp_insert_post([
		'post_title' => $title,
		'post_status' => 'publish',
		'post_type' => 'estate',
		'post_content' => $descr,
	]);
	
	if ($post_id) {
		update_post_meta($post_id, 'city_id', $city_id);

		update_field('address', $address, $post_id);
		update_field('price', $price, $post_id);
		update_field('area', $area, $post_id);
		update_field('living_area', $living_area, $post_id);
		update_field('floor', $floor, $post_id);
		
		wp_set_object_terms($post_id, intval($type_id), 'estate-type');
	}
	
	exit();
}

add_action('wp_ajax_add_estate', 'add_estate');
add_action('wp_ajax_nopriv_add_estate', 'add_estate');






