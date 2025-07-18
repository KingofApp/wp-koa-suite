<?php
$per_page = 10;
$page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
$search = isset($_GET['koa_push_search']) ? trim($_GET['koa_push_search']) : get_option('koa_push_search');

// Get all users
$all_users = get_users();

// Filter users by search
$filtered_users = [];
if ($search !== "" && $search !== null) {
    foreach ($all_users as $user) {
        if (
            stripos($user->display_name, $search) !== false ||
            stripos($user->user_email, $search) !== false
        ) {
            $filtered_users[] = $user;
        }
    }
} else {
    $filtered_users = $all_users;
}

// Pagination
$total_users = count($filtered_users);
$total_pages = ceil($total_users / $per_page);
$offset = ($page - 1) * $per_page;
$users = array_slice($filtered_users, $offset, $per_page);
?>

<form method="get">
    <input type="hidden" name="page" value="koa-suite" />
    <input type="hidden" name="tab" value="push" /> <!-- Add this line -->
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
        <th>code</th>
    </tr>
    <?php 
    foreach($users as $user){
    ?>
    <tr>
        <td> <input type="checkbox"/> </td>
        <td><?php echo '<span>' . esc_html( $user->display_name  ) . '</span>'; ?></td>
        <td><?php echo '<span>' . esc_html( $user->user_email ) . '</span>'; ?></td>
        <td><?php if (get_user_meta( $user->ID, 'koa_push_code', true  )){ ?>
                     <button class="btn"  type="button"  onclick="openPushSender(' <?php echo $user->ID; ?> ', '<?php echo get_user_meta( $user->ID, 'koa_push_code', true  ); ?>')">Send</button>
                    <?php
                  }else{
                    echo "<button  type='button' class='btn' disabled>Send</button>";
                  }
            ?></td>
        <td><textarea><?php echo get_user_meta( $user->ID, 'koa_push_code', true  ); ?></textarea></td>
    </tr>
    <?php } ?>
</table>

<!-- Pagination Links -->
<div class="pagination" style="margin-top:20px;">
    <?php if($page > 1): ?>
        <a href="?page=koa-suite&tab=push&paged=<?php echo $page-1; ?>&koa_push_search=<?php echo urlencode($search); ?>">« Prev</a>
    <?php endif; ?>
    <?php for($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=koa-suite&tab=push&paged=<?php echo $i; ?>&koa_push_search=<?php echo urlencode($search); ?>" <?php if($i == $page) echo 'style="font-weight:bold;"'; ?>><?php echo $i; ?></a>
    <?php endfor; ?>
    <?php if($page < $total_pages): ?>
        <a href="?page=koa-suite&tab=push&paged=<?php echo $page+1; ?>&koa_push_search=<?php echo urlencode($search); ?>">Next »</a>
    <?php endif; ?>
</div>