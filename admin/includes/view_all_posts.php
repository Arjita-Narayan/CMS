<?php

include ("delete_modal.php");



if (isset($_POST['checkBoxArray'])) {

    foreach ($_POST['checkBoxArray'] as $postValueId) {

        $bulk_options = $_POST['bulk_options'];
        switch ($bulk_options) {
            case 'published':
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id ={$postValueId} ";
                $update_to_published_status = mysqli_query($connection, $query);
                confirmQuery($update_to_published_status);
                break;

            case 'draft':
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id ={$postValueId} ";
                $update_to_draft_status = mysqli_query($connection, $query);
                confirmQuery($update_to_draft_status);
                break;

            case 'delete':
                $query = "DELETE FROM posts WHERE post_id ={$postValueId} ";
                $update_to_delete_status = mysqli_query($connection, $query);
                confirmQuery($update_to_delete_status);
                break;

            case 'clone':
                $query = "SELECT * FROM posts WHERE post_id = '{$postValueId}' ";
                $select_post_query = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_array($select_post_query)) {
                    $post_title = $row['post_title'];
                    $post_category_id = $row['post_category_id'];
                    $post_date = $row['post_date'];
                    $post_author = $row['post_author'];
                    $post_user = $row['post_user'];
                    $post_status = $row['post_status'];
                    $post_image = $row['post_image'];
                    $post_tags = $row['post_tags'];
                    $post_content = $row['post_content'];

                    if (!empty($post_tags)) {
                        $post_tags = "No tags";
                    }
                }

                // INSERT INTO query
                $query = "INSERT INTO posts(post_category_id, post_title, post_author,post_user, post_date, post_image, post_content, post_tags, post_status) ";
                $query .= "VALUES({$post_category_id}, '{$post_title}', '{$post_author}', '{$post_user}', now(), '{$post_image}', '{$post_content}', '{$post_tags}', '{$post_status}')";

                // Execute the query
                $copy_query = mysqli_query($connection, $query);

                // Check if query executed successfully
                if (!$copy_query) {
                    die("QUERY FAILED" . mysqli_error($connection));
                }
        }
        break;










    }
}

?>
<form action="" method='post'>

    <table class="table table-bordered table-hover">

        <div id="bulkOptionsContainer" class="col-xs-4">
            <select class="form-control" name="bulk_options" id="">
                <option value="">Select Option</option>
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
            </select>
        </div>

        <div class="col-xs-4">
            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
        </div>

        <thead>
            <tr>
                <th><input id="selectAllBoxes" type="checkbox"></th>
                <th>Id</th>
                <th>User</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Image</th>
                <th>Tags</th>
                <th>Comments</th>
                <th>Date</th>
                <th>View Post</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $query = "SELECT * FROM posts ";
            $select_posts = mysqli_query($connection, $query);
            while ($row = mysqli_fetch_assoc($select_posts)) {
                $post_id = $row['post_id'];
                $post_author = $row['post_author'];
                $post_user = $row['post_user'];
                $post_title = $row['post_title'];
                $post_category_id = $row['post_category_id'];
                $post_status = $row['post_status'];
                $post_image = $row['post_image'];
                $post_tags = $row['post_tags'];
                $post_comment_count = $row['post_comment_count'];
                $post_date = $row['post_date'];
                $post_views_count = $row['post_views_count'];

                echo "<tr>"; ?>

                <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id; ?>'></td>
                <td>
                    <?php echo $post_id; ?>
                </td>
                <td>
                    <?php echo isset($post_user) ? $post_user : $post_author; ?>
                </td>
                <td>
                    <?php echo $post_title; ?>
                </td>

                <?php
                $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id}";
                $select_categories_id = mysqli_query($connection, $query);

                while ($cat_row = mysqli_fetch_assoc($select_categories_id)) {
                    $cat_title = $cat_row['cat_title'];
                    echo "<td>{$cat_title}</td>";
                }
                ?>
                <td>
                    <?php echo $post_status; ?>
                </td>
                <td><img width="100" src="../images/<?php echo $post_image; ?>" alt="image"></td>
                <td>
                    <?php echo $post_tags; ?>
                </td>

                <?php
                $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
                $send_comment_query = mysqli_query($connection, $query);
                $comment_count = mysqli_num_rows($send_comment_query);

                echo "<td><a href='post_comments.php?id={$post_id}'>{$comment_count}</a></td>";

                echo "<td>" . $post_date . "</td>";

                echo "<td><a href='../post.php?p_id={$post_id}'>View Post</a></td>";
                echo " <td><a href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
                echo "<td><a rel='{$post_id}' href='javascript:void(0)' class='delete_link'>Delete</a></td>";
                echo " <td><a href='posts.php?reset={$post_id}'> {$post_views_count}</a></td>";

                echo "</tr>";
            } ?>
        </tbody>
    </table>
</form>

<?php
if (isset($_GET['delete'])) {
    $the_post_id = $_GET['delete'];

    $query = "DELETE FROM posts WHERE post_id = {$the_post_id} ";
    $delete_query = mysqli_query($connection, $query);
    header("Location:posts.php");

}
if (isset($_GET['reset'])) {
    $the_post_id = $_GET['reset'];

    $query = "UPDATE posts SET post_views_count= 0 WHERE post_id =" . mysqli_real_escape_string($connection, $_GET['reset']) . " ";
    $reset_query = mysqli_query($connection, $query);
    header("Location:posts.php");

}
?>
<script>
    $(document).ready(function () {
        $(".delete_link").on('click', function () {
            var id = $(this).attr("rel");
            var delete_url = "posts.php?delete=" + id; // Corrected concatenation

            $(".modal_delete_link").attr("href", delete_url);

            $("#myModal").modal('show');
        });
    });


</script>