<?php

add_action( 'after_setup_theme', 'allow_embed', 20);

function allow_embed() {
    /* Adding user styles to head  */
    add_action( 'wp_head', 'add_koa_style' );
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
          addEvents();
      }
	  
	  function addEvents(){
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
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/4.2.9/iframeResizer.contentWindow.min.js"></script>
<?php
} 

