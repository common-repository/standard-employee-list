<?php /*
Plugin Name: Standard Employee List
Plugin URI: http://batch24.xyz/demo/plugins
Author: Sujan
Author URI: http://mtmsujan.com 
Version: 1.0 
Description: Standard and Easy to Use Employee List Plugin
*/



class Employee {
	public function __construct(){
		add_action('init', array( $this, 'employee_default_init') );
		add_action('add_meta_boxes', array( $this, 'employee_metabox_callback') );
		add_action('save_post', array( $this, 'employee_metabox_save') );
		add_action('admin_enqueue_scripts', array( $this, 'jquery_ui_tabs') );
		add_action('wp_enqueue_scripts', array( $this, 'employee_custom_css') );
		add_shortcode('employee-list', array( $this, 'employee_list_callback') );



	}

	public function jquery_ui_tabs(){
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('employee_script', PLUGINS_URL('js/custom.js', __FILE__), array('jquery', 'jquery-ui-tabs') );

		wp_enqueue_style( 'employee-custom', PLUGINS_URL('css/custom.css', __FILE__) );
		wp_enqueue_style( 'employee-custom', PLUGINS_URL('css/employee-custom.css', __FILE__) );
	}

	public function employee_custom_css(){
		wp_enqueue_style( 'employee-custom', PLUGINS_URL('css/employee-custom.css', __FILE__) );
		wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' );
	}

	public function employee_default_init(){
		$labels = array(
			'name'               => _x( 'Employee', 'Employee Admin Menu Name', 'your-plugin-textdomain' ),
			'singular_name'      => _x( 'Employee', 'Employee Admin Menu singular name', 'your-plugin-textdomain' ),
			'menu_name'          => _x( 'Employee', 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar'     => _x( 'Employee', 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new'            => _x( 'Add New', 'Employee', 'your-plugin-textdomain' ),
			'add_new_item'       => __( 'Add New Employee', 'your-plugin-textdomain' ),
			'new_item'           => __( 'New Employee', 'your-plugin-textdomain' ),
			'edit_item'          => __( 'Edit Employee', 'your-plugin-textdomain' ),
			'view_item'          => __( 'View Employee', 'your-plugin-textdomain' ),
			'all_items'          => __( 'All Employee', 'your-plugin-textdomain' ),
			'search_items'       => __( 'Search Employee', 'your-plugin-textdomain' ),
			'parent_item_colon'  => __( 'Parent Employee:', 'your-plugin-textdomain' ),
			'not_found'          => __( 'No Employee found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No Employee found in Trash.', 'your-plugin-textdomain' )
		);

		$args = array(
			'labels'             => $labels,
	                'description'        => __( 'Employee list.', 'your-plugin-textdomain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'employee' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon' 		 => 'dashicons-groups',
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);

		register_post_type( 'employee_list', $args );

		// employee types 

		$labels = array(
			'name'              => _x( 'Employee Types', 'taxonomy general name' ),
			'singular_name'     => _x( 'Employee Type', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Employee Types' ),
			'all_items'         => __( 'All Employee Types' ),
			'parent_item'       => __( 'Parent Employee Type' ),
			'parent_item_colon' => __( 'Parent Employee Type:' ),
			'edit_item'         => __( 'Edit Employee Type' ),
			'update_item'       => __( 'Update Employee Type' ),
			'add_new_item'      => __( 'Add New Employee Type' ),
			'new_item_name'     => __( 'New Employee Type Name' ),
			'menu_name'         => __( 'Employee Type' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'type' ),
		);

		register_taxonomy( 'employee_type', array( 'employee_list' ), $args );


	}

	public function employee_metabox_callback(){
		// metabox for employees 
		
		add_meta_box('employee-info', 'Employee Information', array($this, 'employee_information'), 'employee_list', 'normal', 'high');
	}
	public function employee_information(){

		$value = get_post_meta(get_the_id(), 'employee-info', true);

		?>

		
		<?php 
			$father = get_post_meta(get_the_id(), 'employee_father', true);
			$mother = get_post_meta(get_the_id(), 'employee_mother', true);
			$gender = get_post_meta(get_the_id(), 'employee_gender', true);
			$designation = get_post_meta(get_the_id(), 'employee_designation', true);
			$mobile = get_post_meta(get_the_id(), 'employee_mobile', true);
			$email = get_post_meta(get_the_id(), 'employee_email', true);
			$sscyear = get_post_meta(get_the_id(), 'employee_sscyear', true);
			$skills = get_post_meta(get_the_id(), 'employee_skills', true);
		?>


		<div id="tabs">
		  <ul>
		    <li><a href="#personal">Personal Information</a></li>
		    <li><a href="#official">Official Information</a></li>
		    <li><a href="#academic">Academic Information</a></li>
		    <li><a href="#experience">Experience</a></li>
		  </ul>
		  <div id="personal">
		    <p><label for="father">Father's Name</label></p>
		    <p><input type="text" name="father" value="<?php echo $father; ?>" id="father"></p>
		    <p><label for="mother">Mother's Name</label></p>
		    <p><input type="text" name="mother" value="<?php echo $mother; ?>" id="mother"></p>

		    <p>
		    	<input type="radio" name="gender" value="male" id="male" <?php if($gender == 'male'){echo "checked";} ?>>
		    	<label for="male"> Male</label> <br>
		    	<input type="radio" name="gender" value="female" id="female" <?php if($gender == 'female'){echo "checked";} ?>>
		    	<label for="female"> Female</label>
		    </p>
		  </div>


		  <div id="official">
		    <p><label for="designation">Designation</label></p>
		    <p><input type="text" name="designation" value="<?php echo $designation; ?>" id="designation"></p>
		    <p><label for="mobile">Mobile</label></p>
		    <p><input type="number" name="mobile" value="<?php echo $mobile; ?>" id="mobile"></p>
		    <p><label for="email">Email</label></p>
		    <p><input type="email" name="email" value="<?php echo $email; ?>" id="email"></p>
		  </div>


		  <div id="academic">
		    <p><label for="sscyear">SSC Year</label></p>
		    <p><input type="text" name="sscyear" value="<?php echo $sscyear; ?>" id="sscyear"></p>
		  </div>


		  <div id="experience">
		    <p><label for="skills">Skills:</label></p>
		    <p><input type="text" name="skills" value="<?php echo $skills; ?>" id="skills"></p>
		  </div>

		</div>
		<?php 
	}

	public function employee_metabox_save($post_id){

		$father = $_POST['father'];
		$mother = $_POST['mother'];
		$gender = $_POST['gender'];
		$designation = $_POST['designation'];
		$mobile = $_POST['mobile'];
		$email = $_POST['email'];
		$sscyear = $_POST['sscyear'];
		$skills = $_POST['skills'];

		update_post_meta(get_the_id(), 'employee_father', $father);

		update_post_meta(get_the_id(), 'employee_mother', $mother);

		update_post_meta(get_the_id(), 'employee_gender', $gender);

		update_post_meta(get_the_id(), 'employee_designation', $designation);

		update_post_meta(get_the_id(), 'employee_mobile', $mobile);

		update_post_meta(get_the_id(), 'employee_email', $email);

		update_post_meta(get_the_id(), 'employee_sscyear', $sscyear);

		update_post_meta(get_the_id(), 'employee_skills', $skills);

	}


	public function employee_list_callback($attr, $content){
		ob_start();

		$atts = shortcode_atts(array(
			'count' => -1
		), $attr);

		extract($atts);
		?>
			
			<div class="employee-list">
				<?php 

				if( get_query_var('paged') ){
					$current_page = get_query_var('paged');
				}else {
					$current_page = 1;
				}

					$employee = new WP_Query(array(
						'post_type' => 'employee_list',
						'posts_per_page' => $count,
						'paged' => $current_page
					));

				while($employee->have_posts()) : $employee->the_post();
				?>
				<article class="employee">
					<div class="employee-photo">
						<div class="photo">
							<?php the_post_thumbnail(); ?>
							<div class="overlay">
								<a href="mailto:<?php echo get_post_meta( get_the_id(), 'employee_email', true ); ?>"><i class="fa fa-envelope-o"></i></a>
								<a class="right" href="tel:<?php echo get_post_meta( get_the_id(), 'employee_mobile', true ); ?>"><i class="fa fa-phone"></i></a>
							</div>
						</div>
						<h4>Name : <?php the_title(); ?></h4>
						<p><strong>Designation :</strong> <?php echo get_post_meta( get_the_id(), 'employee_designation', true ); ?></p>
					</div>
					<div class="employee-details">

						<p><strong>About The Employee : </strong><?php the_content(); ?></p>

						<div class="employee-details-left">
							<p><strong>Father's Name : </strong><?php echo get_post_meta( get_the_id(), 'employee_father', true ); ?></p>
							
							<p><strong>Mother's Name : </strong><?php echo get_post_meta( get_the_id(), 'employee_mother', true ); ?></p>
							
							<p><strong>Gender : </strong><?php echo get_post_meta( get_the_id(), 'employee_gender', true ); ?></p>
						</div>

						<div class="employee-details-right">
							<p><strong>SSC Year : </strong><?php echo get_post_meta( get_the_id(), 'employee_sscyear', true ); ?></p>
							
							<p><strong>Skills : </strong><?php echo get_post_meta( get_the_id(), 'employee_skills', true ); ?></p>
							
							<p><strong>E-mail : </strong><?php echo get_post_meta( get_the_id(), 'employee_email', true ); ?></p>
						</div>
					</div>
				</article>
				<?php endwhile; ?>
				<div class="paginate">
					<?php 

					

						echo paginate_links(array(
							'current' => $current_page,
							'total' => $employee->max_num_pages,
							'prev_text' => '<i class="fa fa-angle-left"></i>',
							'next_text' => '<i class="fa fa-angle-right"></i>',
							'show_all' => true
						));

						
					?>
				</div>
			</div>

		<?php return ob_get_clean();
	}


	public function visual_composer_support(){
		if(function_exists('vc_map')){
			vc_map(array(
				'name' => 'Employee List',
				'base' => 'employee-list',
				'id' => 'employee-list',
				'params' => array(
					array(
						'heading' => 'How Many Employee to show',
						'param_name' => 'count',
						'type' => 'textfield',
					)
				)
			));
		}
	}
}


$employee = new Employee();


$employee->visual_composer_support();
