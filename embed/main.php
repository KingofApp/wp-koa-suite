<?php

add_action( 'admin_init', 'register_koa_embed_settings' );

/* Seting the form fields */
function register_koa_embed_settings() {
    $koa_embed_key_default = array(
        'type'              => 'string',
        'show_in_rest'      => false,
        'default'           => "kingOfApp"
    );
    $koa_embed_style_default = array(
        'type'              => 'string',
        'show_in_rest'      => false,
        'default'           => "header, footer{ display: none !important}"
    );
    //register our settings
    register_setting( 'koa-embed-settings-group', 'koa_embed_key',  $koa_embed_key_default);
    register_setting( 'koa-embed-settings-group', 'koa_embed_style', $koa_embed_style_default );
}

//shortcode [replicate_landing] allow duplication of home page
function replicate_landing_shortcode() {
    // Output a div where the fetch result will be displayed
    ob_start();
    ?>
    <div id="custom-section-result" class="custom-section">
        <!-- The fetched content will be inserted here -->
        Loading content...
    </div>
    
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            fetch('<?php echo get_site_url(); ?>')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                // Insert the fetched HTML content into the div
                document.getElementById('custom-section-result').innerHTML = data;
            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById('custom-section-result').innerHTML = "An error occurred while fetching content.";
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('replicate_landing', 'replicate_landing_shortcode');