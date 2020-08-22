<?php require APPROOT . './views/inc/header.php'; ?>

<?php flash('post_message'); ?>


<h2><?php if (isset($_SESSION['user_name']) && $_SESSION['userloggedin'] == true) {
    echo "Welcome to the member's area, " . $_SESSION['user_name'] . "!";
} ?></h2>

<div class="row">

    <div class="col-md-6">
        <h1>POSTS</h1>
    </div>

    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/posts/add" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Add post<a>
    </div>

</div>
<!-- loop through our recived data from the db  -->
<?php foreach ($data['posts'] as $post) : ?>
    <div class="card card-body mb-3">
        <h4 class="card-title"><?php echo $post->title; ?></h4>

        <div class="bg-light pd-2 mb-3">
            Writen by <?php echo $post->name;  ?> on <?php echo $post->postCreated; ?>
        </div>

        <p class="card-text">
            <?php echo $post->body; ?></p>

        <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-dark">More</a>
    </div>


<?php endforeach; ?>


<?php require APPROOT . '/views/inc/footer.php'; ?>