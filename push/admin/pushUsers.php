<?php
$per_page = 10;
$page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
$search = isset($_GET['koa_push_search']) ? trim($_GET['koa_push_search']) : get_option('koa_push_search');

// Pagination calculation
$offset = ($page - 1) * $per_page;

/**
 * ✅ ORDER USERS BY koa_push_code FIRST
 * We use the pre_user_query hook to modify the SQL query before execution
 */
add_action('pre_user_query', 'order_users_by_koa_push_code');
function order_users_by_koa_push_code($query) {
    global $wpdb;

    // Only modify for our specific admin page
    if (isset($_GET['page']) && $_GET['page'] === 'koa-suite' && isset($_GET['tab']) && $_GET['tab'] === 'push') {
        $query->query_from .= " LEFT JOIN {$wpdb->usermeta} AS umeta 
                                ON ({$wpdb->users}.ID = umeta.user_id 
                                AND umeta.meta_key = 'koa_push_code')";
        $query->query_orderby = "ORDER BY (umeta.meta_value IS NOT NULL AND umeta.meta_value != '') DESC, {$wpdb->users}.ID ASC";
    }
}

// Arguments for get_users()
$args = [
    'number'  => $per_page,
    'offset'  => $offset,
    'orderby' => 'ID', // Fallback (actual ordering is handled by our SQL hook)
    'order'   => 'ASC',
];

// Apply search filter
if (!empty($search)) {
    $args['search'] = '*' . esc_attr($search) . '*';
    $args['search_columns'] = ['user_login', 'user_nicename', 'display_name', 'user_email'];
}

$users = get_users($args);

// Get total users for pagination (not affected by the ordering hook)
if (!empty($search)) {
    $total_users = count(get_users([
        'search' => '*' . esc_attr($search) . '*',
        'search_columns' => ['user_login', 'user_nicename', 'display_name', 'user_email'],
        'fields' => 'ID' // Only fetch IDs for speed
    ]));
} else {
    $total_users = count_users()['total_users'];
}

$total_pages = ceil($total_users / $per_page);
?>

<form method="get">
    <input type="hidden" name="page" value="koa-suite" />
    <input type="hidden" name="tab" value="push" />
    <div class="searchBar">
        <button type="submit"><i class="fa fa-search"></i></button>
        <input type="text" placeholder="Search.." name="koa_push_search" value="<?php echo esc_attr($search); ?>">
    </div>
</form>

<table>
    <tr>
        <th><input type="checkbox"/></th>
        <th>Name</th>
        <th>Email</th>
        <th>Send push</th>
        <th>Code</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <?php $code = get_user_meta($user->ID, 'koa_push_code', true); ?>
    <tr>
        <td><input type="checkbox"/></td>
        <td><?php echo '<span>' . esc_html($user->display_name) . '</span>'; ?></td>
        <td><?php echo '<span>' . esc_html($user->user_email) . '</span>'; ?></td>
        <td>
            <?php if ($code): ?>
                <button class="btn" type="button" onclick="openPushSender('<?php echo $user->ID; ?>', '<?php echo esc_js($code); ?>')">Send</button>
            <?php else: ?>
                <button type="button" class="btn" disabled>Send</button>
            <?php endif; ?>
        </td>
        <td><textarea><?php echo esc_textarea($code); ?></textarea></td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- Pagination Links -->
<div class="pagination" style="margin-top:20px;">
    <?php if ($page > 1): ?>
        <a href="?page=koa-suite&tab=push&paged=<?php echo $page-1; ?>&koa_push_search=<?php echo urlencode($search); ?>">« Prev</a>
    <?php endif; ?>

    <?php
    if ($total_pages <= 6) {
        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<a href="?page=koa-suite&tab=push&paged=' . $i . '&koa_push_search=' . urlencode($search) . '"';
            if ($i == $page) echo ' style="font-weight:bold;"';
            echo '>' . $i . '</a>';
        }
    } else {
        if ($page <= 3) {
            for ($i = 1; $i <= 3; $i++) {
                echo '<a href="?page=koa-suite&tab=push&paged=' . $i . '&koa_push_search=' . urlencode($search) . '"';
                if ($i == $page) echo ' style="font-weight:bold;"';
                echo '>' . $i . '</a>';
            }
            echo '<span>...</span>';
            for ($i = $total_pages - 2; $i <= $total_pages; $i++) {
                echo '<a href="?page=koa-suite&tab=push&paged=' . $i . '&koa_push_search=' . urlencode($search) . '"';
                if ($i == $page) echo ' style="font-weight:bold;"';
                echo '>' . $i . '</a>';
            }
        } elseif ($page > 3 && $page < $total_pages - 2) {
            for ($i = $page - 2; $i <= $page; $i++) {
                echo '<a href="?page=koa-suite&tab=push&paged=' . $i . '&koa_push_search=' . urlencode($search) . '"';
                if ($i == $page) echo ' style="font-weight:bold;"';
                echo '>' . $i . '</a>';
            }
            echo '<span>...</span>';
            for ($i = $total_pages - 2; $i <= $total_pages; $i++) {
                echo '<a href="?page=koa-suite&tab=push&paged=' . $i . '&koa_push_search=' . urlencode($search) . '"';
                if ($i == $page) echo ' style="font-weight:bold;"';
                echo '>' . $i . '</a>';
            }
        } else {
            for ($i = $total_pages - 5; $i <= $total_pages; $i++) {
                if ($i < 1) continue;
                echo '<a href="?page=koa-suite&tab=push&paged=' . $i . '&koa_push_search=' . urlencode($search) . '"';
                if ($i == $page) echo ' style="font-weight:bold;"';
                echo '>' . $i . '</a>';
            }
        }
    }
    ?>

    <?php if ($page < $total_pages): ?>
        <a href="?page=koa-suite&tab=push&paged=<?php echo $page+1; ?>&koa_push_search=<?php echo urlencode($search); ?>">Next »</a>
    <?php endif; ?>
</div>
