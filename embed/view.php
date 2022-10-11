<div class="wrap">
<h1>King Of App Embed</h1>
<a href="https://kingofapp.com/" target="_blank">
  <img src="https://s3-eu-west-1.amazonaws.com/kingofapp.es/logo.png" style="max-width: 100%" alt="king of app">
</a>
<form method="post" action="options.php">
    <?php settings_fields( 'koa-embed-settings-group' ); ?>
    <?php do_settings_sections( 'koa-embed-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Query param</th>
        <td><input type="text" name="koa_embed_key" required value="<?php echo get_option('koa_embed_key'); ?>" />
            <p class="description">Query string key</p>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Style to add</th>
        <td><textarea rows="4" cols="50" name="koa_embed_style"><?php echo esc_attr( get_option('koa_embed_style') ); ?></textarea>
            <p class="description">Styles to add if the query string var is enabled (key=true) </p>
        </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
</div>