<?php 

function university_custom_field()
{
    register_rest_field( "post", "authorName", [
        'get_callback'=> function() {
            return get_the_author();
        }
    ]);
}

add_action( "rest_api_init", 'university_custom_field' );


function pageBanner($args=null)
{
    $args['title'] = isset($args['title']) ==true ?  $args['title'] : get_the_title();
    $args['subTitle'] = isset($args['subTitle']) ==true ?  $args['subTitle'] : get_field('page_banner_subtitle');
    $args['bannerImage'] = isset($args['bannerImage']) ==true ?  $args['bannerImage'] : get_theme_file_uri('images/ocean.jpg');
    if(get_field('page_banner') != null AND !is_archive() AND !is_home()){
        $args['bannerImage']= get_field('page_banner')['sizes']['pageBanner'];
    }
    ?>
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?= $args['bannerImage']; ?>)">
    </div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?=$args['title']; ?></h1>
        <div class="page-banner__intro">
            <p>
                <?=
               $args['subTitle']; ?>
            </p>
        </div>
    </div>
</div>
<?php
}
?>










<?php












function university_files() 
{
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));


    // local
    wp_localize_script( 'main-university-js', "universityData", [
        'root_url' => get_site_url()
    ] );
     
}

add_action("wp_enqueue_scripts","university_files");



function university_feature(){
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size( "professorLandscape", 400, 260, true );
    add_image_size( "professorPortraits", 480, 650, true );
    add_image_size( "pageBanner", 1500, 300, true );
// register_nav_menu( "headerMenu", "Header Menu" );
// register_nav_menu( "footerMenu01", "Footer Explore Menu" );
// register_nav_menu( "footerMenu02", "Footer Learn Menu" );
}


add_action( 'after_setup_theme', "university_feature");


function university_adjust_queries($query)
{

    if(!is_admin() && is_post_type_archive('campus') && is_main_query()){
        $query->set('posts_per_page',-1);
    }

    if(!is_admin() && is_post_type_archive('program') && is_main_query()){
        $query->set('orderby','title');
        $query->set('order','ASC');
        $query->set('posts_per_page',-1);
    }

    if(!is_admin() && is_post_type_archive('event') && is_main_query()){
        $today=date('Ymd');
        $query->set('meta_key','event_date');
        $query->set('orderby','meta_value_num');
        $query->set('order','ASC');
        $query->set('meta_query',[['key'=>'event_date',
        'compare'=>'>=',
        'value'=>$today,
        'type'=>'numeric'
        ]]);
    }
}

add_action( 'pre_get_posts', "university_adjust_queries");