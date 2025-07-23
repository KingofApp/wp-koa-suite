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

$params = [];
if ( !empty( $search_query ) ) {
    $query .= " AND (u.display_name LIKE %s OR u.user_email LIKE %s OR n.notification_title LIKE %s)";
    $params[] = '%' . $search_query . '%';
    $params[] = '%' . $search_query . '%';
    $params[] = '%' . $search_query . '%';
}

$query .= " ORDER BY n.notification_send_date DESC LIMIT %d OFFSET %d";
$params[] = $items_per_page;
$params[] = $offset;

$notifications = $wpdb->get_results( $wpdb->prepare( $query, ...$params ) );

// Get device token for user_id = 1
$device_token_user1 = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = 1 AND meta_key = 'koa_push_code'"
    )
);

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
<form method="GET" action="">
    <input type="hidden" name="page" value="koa-suite">
    <input type="hidden" name="tab" value="push">
    <input type="hidden" name="push-tab" value="pushHistory">
    <input type="hidden" name="page_num" value="1">
     <div class="searchBar">
        <button type="submit"><i class="fa fa-search"></i></button>
        <input type="text" placeholder="Search.." name="search" value="<?php echo esc_attr( $search_query ); ?>">
    </div>
</form>

<!-- Notifications table -->
<table class="widefat fixed striped">
    <tr>
        <th><input type="checkbox"/></th>
        <th>User Name</th>
        <th>Email</th>
        <th>Device Token</th>
        <th>Notification Title</th>
        <th>Notification Body</th>
        <th>Send Date</th>
        <th>Status</th>
    </tr>

    <?php if ( ! empty( $notifications ) ) : ?>
        <?php foreach ( $notifications as $notification ) : ?>
            <?php
            // Get device token for this notification
            if ($notification->user_id == 1) {
                $device_token = $notification->device_token;
                $show_user = false;
            } else {
                $device_token = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = %d AND meta_key = 'koa_push_code'",
                        $notification->user_id
                    )
                );
                $show_user = true;
            }
            ?>
            <tr>
                <td><input type="checkbox" /></td>
                <td><?php echo $show_user ? esc_html( $notification->display_name ) : ''; ?></td>
                <td><?php echo $show_user ? esc_html( $notification->user_email ) : ''; ?></td>
                <td>
                    <textarea readonly style="width:100%;height:2em;"><?php echo esc_html( $device_token ); ?></textarea>
                </td>
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
            <td colspan="8">No notifications found.</td>
        </tr>
    <?php endif; ?>
</table>
<!-- Pagination -->
<div class="pagination" style="margin-top:20px;">
    <?php if($page > 1): ?>
        <a href="?page=koa-suite&tab=push&push-tab=pushHistory&page_num=<?php echo $page-1; ?>&search=<?php echo urlencode($search_query); ?>">« Prev</a>
    <?php endif; ?>
    <?php for($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=koa-suite&tab=push&push-tab=pushHistory&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search_query); ?>" <?php if($i == $page) echo 'style="font-weight:bold;"'; ?>><?php echo $i; ?></a>
    <?php endfor; ?>
    <?php if($page < $total_pages): ?>
        <a href="?page=koa-suite&tab=push&push-tab=pushHistory&page_num=<?php echo $page+1; ?>&search=<?php echo urlencode($search_query); ?>">Next »</a>
    <?php endif; ?>
</div>