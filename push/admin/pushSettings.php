<div class="wrap">
    <h1>Firebase Push Notifications Settings</h1>

    <form method="POST" enctype="multipart/form-data" >
        <!-- File upload field -->
        <table class="form-table">
            <tr>
                <th scope="row"><label for="firebase_json">Upload Google Service Account JSON</label></th>
                <td>
                    <input type="file" name="firebase_json" id="firebase_json" />
                </td>
            </tr>
        </table>
        <?php submit_button('Upload'); ?>
        <input type="hidden" name="push-tab" value="<?php echo esc_attr($active_tab); ?>">
    </form>
</div>

<?php
// Handle the file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['firebase_json'])) {
    $uploaded_file = $_FILES['firebase_json'];
    if ($uploaded_file['type'] == 'application/json') {
        move_uploaded_file($uploaded_file['tmp_name'], FIREBASE_JSON_CREDENTIALS_PATH);
        echo '<div class="updated"><p>Google JSON credentials uploaded successfully.</p></div>';
    } else {
        echo '<div class="error"><p>Invalid file type. Please upload a JSON file.</p></div>';
    }
}
