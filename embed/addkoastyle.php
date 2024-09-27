<?php

add_action( 'after_setup_theme', 'allow_embed', 20);

function allow_embed() {
    /* Adding user styles to head  */
    add_action( 'wp_head', 'add_koa_style' );
    add_action( 'wp_enqueue_scripts', 'enqueue_iframe_resizer' );
     /* Allow woocommerce checkout page to be embeded */
    remove_action( 'template_redirect', 'wc_send_frame_options_header' );
    remove_action( 'login_init', 'send_frame_options_header' );
    remove_action( 'admin_init', 'send_frame_options_header' );
    remove_action( 'template_redirect', 'wc_send_frame_options_header' );
    header('X-Frame-Options: ALLOWALL');
}

function add_koa_style() {
   ?>
  <script>

      let koa_embed_key = "<?php echo get_option('koa_embed_key'); ?>";
      var keyWord = koa_embed_key != "" ? koa_embed_key : "kingOfApp";
      const urlParams = new URLSearchParams(window.location.search);
      let myParam = urlParams.get(keyWord);
      const encodedParam = window.location.search.indexOf(keyWord+"%3Dtrue") >= 0;
      myParam = encodedParam ? "true" : myParam;

      if (myParam) {
          localStorage.setItem(keyWord, myParam);
      }
      if (localStorage.getItem(keyWord) && localStorage.getItem(keyWord) === "true") {
          let koa_embed_style = `<?php echo get_option('koa_embed_style'); ?>`;
          var css = koa_embed_style != "" ? koa_embed_style : 'header, footer{ display:none } .elegantshop-products-wrapper{opacity: 1 !important;}';
          addStyle();
          //addEvents();
      }
	  
	  function addEvents(){

        //manage download attribute
		document.addEventListener("click", function(event){
            try{
                let target = event.path.find((e)=> e.tagName==="A" && e.hasAttribute("download"));
                if(target){
                    //do stuf here
                    parent.postMessage({toDownload:target.href, preview: true}, "*");
                }
            
                let link = event.path.find((elem)=> elem.tagName==="A" || elem.tagName==="a");
        
                if(!link ) return;
                if(link.getAttribute("target") != "_blank") return;  
                event.preventDefault();
                parent.postMessage({toOpen: link.href}, "*");
            }catch(e){
                console.error(e);
            }
			
		  
		});
	  }
      
      function addStyle() {
          var head = document.head || document.getElementsByTagName('head')[0];
          var style = document.createElement('style');
          head.appendChild(style);
          style.type = 'text/css';
          if (style.styleSheet) {
              // This is required for IE8 and below.
              style.styleSheet.cssText = css;
          } else {
              style.appendChild(document.createTextNode(css));
          }
      }
  </script>
<?php

} 

function enqueue_iframe_resizer() {
    wp_enqueue_script(
        'iframe-resizer', // Handle name for the script
        'https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/4.2.9/iframeResizer.contentWindow.min.js', // Script source URL
        array(), // Dependencies, if any
        null, // Version number, null will avoid appending a version query string
        true // Load in footer (true) or header (false)
    );
}

//shortcode [replicate_landing] allow duplication of home page
function replicate_landing_shortcode() {
    ob_start();

    // Temporarily disable lazy loading
    add_filter('wp_lazy_loading_enabled', '__return_false');

    // Check if the home page is static or shows latest posts
    if ( get_option('show_on_front') === 'page' ) {
        // Static front page
        $homepage_id = get_option('page_on_front');
        $homepage = get_post($homepage_id);
        echo '<div class="homepage-content">';
        echo apply_filters('the_content', $homepage->post_content);
        echo '</div>';
    } else {
        // Home page is showing latest posts
        $args = array(
            'posts_per_page' => 5, // Adjust this number for how many posts you want to show
        );
        $homepage_query = new WP_Query($args);
        
        if ( $homepage_query->have_posts() ) {
            while ( $homepage_query->have_posts() ) {
                $homepage_query->the_post();
                echo '<div class="post">';
                echo '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
                echo '<div class="post-content">' . get_the_excerpt() . '</div>';
                if ( has_post_thumbnail() ) {
                    echo get_the_post_thumbnail(get_the_ID(), 'full');
                }
                echo '</div>';
            }
            wp_reset_postdata();
        } else {
            echo 'No posts found.';
        }
    }

    // Re-enable lazy loading after content is displayed
    remove_filter('wp_lazy_loading_enabled', '__return_false');

    return ob_get_clean();
}
add_shortcode('replicate_landing', 'replicate_landing_shortcode');