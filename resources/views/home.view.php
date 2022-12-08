<?php // require_once 'partials/head.php' ?>

<?= '<p class="message">PHP output here, potentially large HTML chunks</p>' ?>

<div class="vue-app">
    <hello-world msg="<?= 'Hello World!' ?>"></hello-world>
</div>

<?= '<p class="message">PHP output here, potentially large HTML chunks</p>' ?>

<div class="vue-app">
    <hello-world msg="component"></hello-world>
</div>

<?= '<p class="message">PHP output here, potentially large HTML chunks</p>' ?>

<?php //require_once 'partials/foot.php' ?>
