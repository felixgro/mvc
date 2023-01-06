<?php $this->layout('layouts::app') ?>

<p class="message">
    Demonstration how to sprinkle multiple vue instances across your application.
</p>

<?= $this->vue() ?>
<login-form is-authenticated="<?= $this->auth()->check() ?>"></login-form>
<?= $this->endvue() ?>

<p class="message">
    PHP output here, potentially large HTML chunks
</p>

<?= $this->vue() ?>
<hello-world msg="component"></hello-world>
<?= $this->endvue() ?>

<p class="message">
    Ends with a final php dump.
</p>
