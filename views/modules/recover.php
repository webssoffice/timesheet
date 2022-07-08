<h1 class="p-2">Recover your account</h1>

<form method="POST">
    <?php
        $recoverPassword = new MvcController();
        $recoverPassword->recoverPassword();

        if (isset($_GET["action"])) {
            if ($_GET["action"] == 'error') {
                echo '<div class="alert alert-danger" role="alert">Data entered is incorrect!</div>';
            } elseif ($_GET["action"] == 'success') {
                echo '<div class="alert alert-success" role="alert">Action performed successfully!</div>';
            }
        }
    ?>

    <div class="my-2 form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="E-mail" aria-describedby="emailHelp" required>
    </div>

    <div class="my-2 form-group">
        <button type="submit" id="recover" name="recover" class="my-2 btn btn-block btn-primary">Send</button>
    </div>
</form>