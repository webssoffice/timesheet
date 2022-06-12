<h1 class="p-2">Login</h1>

<form method="POST">
    <?php
        $readEmployeeData = new MvcController();
        $readEmployeeData->readEmployeeData();

        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'error') {
                echo '<div class="alert alert-danger" role="alert">Data entered is incorrect!</div>';
            }
        }
    ?>

    <div class="my-2 form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="E-mail" aria-describedby="emailHelp" required>
    </div>

    <div class="my-2 form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
    </div>

    <div class="my-2 form-group">
        <button type="submit" id="login" name="login" class="my-2 btn btn-block btn-primary">Send</button>
        <a href="/recover" class="my-2 btn btn-block btn-secondary" role="button">Forgot your password?</a>
    </div>
</form>