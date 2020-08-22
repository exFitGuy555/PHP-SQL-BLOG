<?php require APPROOT . './views/inc/header.php'; ?>

<a href="<?php echo URLROOT; ?>/posts" class="btn btn-primary">
    <i class="fa fa-backward"> Back</i>
</a>

<h2 class="display-3">

    <?php echo $data['post']->title ?>

</h2>

<div class="bg-secondary text-white p-2 mb-3">

    Writen By <?php echo $data['user']->name ?> on <?php echo $data['post']->created_at ?>

</div>

<p>

    <?php echo $data['post']->body ?>

</p>

<!-- SESSION user_id is the loggen in user, making sure the post belong to the user and he is able to changed it
 -->

<?php if ($data['post']->user_id == $_SESSION['user_id']) : ?>

    <a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->id; ?>" class="btn btn-dark">
        <i class="fa fa-pencil"><span class="ml-2">Edit post</span></i>
    </a>

    <form class="pull-right" method="POST" action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data['post']->id; ?>">

        <input type="submit" value="Delete" class="btn btn-danger ">
    </form>

<?php endif; ?>

<?php require APPROOT . './views/inc/footer.php'; ?>