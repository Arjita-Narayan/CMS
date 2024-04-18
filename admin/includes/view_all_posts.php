<?php

include("delete_modal.php");



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
        }
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
                <th><input id="selectAllBox" type="checkbox"></th>
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
                ?>
                <td><a href='post_comments.php?id=<?php echo $post_id; ?>'>
                        <?php echo $comment_count; ?>
                    </a></td>

                <td>
                    <?php echo $post_date; ?>
                </td>
                <td><a href='../post.php?p_id=<?php echo $post_id; ?>'>View Post</a></td>
                <td><a href='posts.php?source=edit_post&p_id=<?php echo $post_id; ?>'>Edit</a></td>
                <!-- <td><a onClick="javascript: return confirm('Are you sure you want to delete?');" -->
                <td><a rel='$post_id' href='' class='delete_link'>Delete</a></td>
                <td><a href='posts.php?reset=<?php echo $post_id; ?>'>
                        <?php echo $post_views_count; ?>
                    </a></td>
                </tr>
            <?php } ?>
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


        $("delete_link").on('click', function () {

            var id = $(this).attr("rel");
            alert(id);

        });

    });
</script>