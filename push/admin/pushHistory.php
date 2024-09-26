<?php
global $wpdb;

// Handle the search and pagination logic
$search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
$page = isset($_GET['page_num']) ? absint($_GET['page_num']) : 1;
$items_per_page = 50;
$offset = ($page - 1) * $items_per_page;

// Define the table name
$table_name = $wpdb->prefix . 'koa_push_notifications';
$users_table = $wpdb->prefix . 'users';

// Construct the query to get notifications
$query = "SELECT n.*, u.display_name, u.user_email 
          FROM $table_name AS n
          LEFT JOIN $users_table AS u ON u.ID = n.user_id
          WHERE 1=1";

// Add search conditions if there is a search query
if ( !empty( $search_query ) ) {
    $query .= $wpdb->prepare(
        " AND (u.display_name LIKE %s OR u.user_email LIKE %s OR n.notification_title LIKE %s)",
        '%' . $search_query . '%',
        '%' . $search_query . '%',
        '%' . $search_query . '%'
    );
}

// Add limit and offset for pagination
$query .= " ORDER BY n.notification_send_date DESC LIMIT $items_per_page OFFSET $offset";

// Get the notifications
$notifications = $wpdb->get_results($query);

// Count the total number of records for pagination
$total_query = "SELECT COUNT(*) FROM $table_name AS n
                LEFT JOIN $users_table AS u ON u.ID = n.user_id
                WHERE 1=1";

// Add search condition to the total count query
if ( !empty( $search_query ) ) {
    $total_query .= $wpdb->prepare(
        " AND (u.display_name LIKE %s OR u.user_email LIKE %s OR n.notification_title LIKE %s)",
        '%' . $search_query . '%',
        '%' . $search_query . '%',
        '%' . $search_query . '%'
    );
}
$total_items = $wpdb->get_var($total_query);
$total_pages = ceil($total_items / $items_per_page);
?>

<!-- Search form -->
<form method="GET">
    <input type="text" name="search" value="<?php echo esc_attr( $search_query ); ?>" placeholder="Search by name, email, or title">
    <input type="submit" value="Search">
</form>

<!-- Notifications table -->
<table class="widefat fixed striped">
    <tr>
        <th><input type="checkbox"/></th>
        <th>User Name</th>
        <th>Email</th>
        <th>Notification Title</th>
        <th>Notification Body</th>
        <th>Send Date</th>
        <th>Status</th>
    </tr>

    <?php if ( ! empty( $notifications ) ) : ?>
        <?php foreach ( $notifications as $notification ) : ?>
            <tr>
                <td><input type="checkbox" /></td>
                <td><?php echo esc_html( $notification->display_name ); ?></td>
                <td><?php echo esc_html( $notification->user_email ); ?></td>
                <td><?php echo esc_html( $notification->notification_title ); ?></td>
                <td><?php echo esc_html( $notification->notification_body ); ?></td>
                <td><?php echo esc_html( date( 'Y-m-d H:i:s', strtotime( $notification->notification_send_date ) ) ); ?></td>
                <td>
                    <?php if ( $notification->notification_status === 'success' ) : ?>
                        <span class="status-success">Success</span>
                    <?php else : ?>
                        <span class="status-failed">Failed</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="7">No notifications found.</td>
        </tr>
    <?php endif; ?>
</table>

<!-- Pagination -->
<div class="tablenav">
    <div class="tablenav-pages">
        <?php if ($total_pages > 1) : ?>
            <span class="displaying-num">Displaying <?php echo $page; ?> of <?php echo $total_pages; ?> pages</span>
            <span class="pagination-links">
                <?php if ($page > 1) : ?>
                    <a class="prev-page" href="?page_num=1&search=<?php echo urlencode( $search_query ); ?>">&laquo;</a>
                    <a class="prev-page" href="?page_num=<?php echo $page - 1; ?>&search=<?php echo urlencode( $search_query ); ?>">&lsaquo;</a>
                <?php endif; ?>

                <span class="paging-input">
                    <input class="current-page" type="text" name="page_num" value="<?php echo $page; ?>" size="2"> of
                    <span class="total-pages"><?php echo $total_pages; ?></span>
                </span>

                <?php if ($page < $total_pages) : ?>
                    <a class="next-page" href="?page_num=<?php echo $page + 1; ?>&search=<?php echo urlencode( $search_query ); ?>">&rsaquo;</a>
                    <a class="next-page" href="?page_num=<?php echo $total_pages; ?>&search=<?php echo urlencode( $search_query ); ?>">&raquo;</a>
                <?php endif; ?>
            </span>
        <?php endif; ?>
    </div>
</div>

<?php
// Add styles for success and failure statuses
echo '<style>
    .status-success { color: green; font-weight: bold; }
    .status-failed { color: red; font-weight: bold; }
</style>';
?>