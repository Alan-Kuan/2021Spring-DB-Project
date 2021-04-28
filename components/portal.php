<div class="rounded-box">

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="nav-item">
            <button class="nav-link active" role="tab"
                data-bs-toggle="tab" data-bs-target="#register"
                aria-controls="register" aria-selected="true"><?= $TEXT['register']; ?></button>
        </li>
        <li role="presentation" class="nav-item">
            <button class="nav-link" role="tab"
                data-bs-toggle="tab" data-bs-target="#login"
                aria-controls="login" aria-selected="false"><?= $TEXT['login']; ?></button>
        </li>
    </ul>

    <div class="tab-content">

        <div id="register" class="tab-pane show active" role="tabpanel">
            <h2 class="mt-2"><?= $TEXT['register']; ?></h2>
            <form action="register.php" method="post">
                <div class="input-group has-validation w-75 mx-auto mt-2">
                    <span class="input-group-text"><?= $TEXT['username']; ?></span>
                    <input id="username" class="form-control" name="username" type="text" />
                    <div class="invalid-feedback">{{ feedback }}</div>
                </div>
                <div class="input-group has-validation w-75 mx-auto mt-2">
                    <span class="input-group-text"><?= $TEXT['password']; ?></span>
                    <input id="password" class="form-control" name="password" type="password" autocomplete="new-password" />
                    <button class="btn btn-secondary password-peeker" type="button">
                        <i class="bi-eye-slash" aria-hidden="true"></i>
                    </button>
                    <div class="invalid-feedback">{{ feedback }}</div>
                </div>
                <div class="input-group has-validation w-75 mx-auto mt-2">
                    <span class="input-group-text"><?= $TEXT['password_again']; ?></span>
                    <input id="password-retype" class="form-control" name="password-retype" type="password" autocomplete="new-password" />
                    <button class="btn btn-secondary password-peeker" type="button">
                        <i class="bi-eye-slash" aria-hidden="true"></i>
                    </button>
                    <div class="invalid-feedback">{{ feedback }}</div>
                </div>
                <div class="input-group has-validation w-75 mx-auto mt-2">
                    <span class="input-group-text"><?= $TEXT['phone_num']; ?></span>
                    <input id="phone_num" class="form-control" name="phone_num" type="tel" />
                    <div class="invalid-feedback">{{ feedback }}</div>
                </div>
                <div class="w-75 mx-auto my-2">
                    <input class="btn btn-primary register w-100" type="submit" value="<?= $TEXT['submit']; ?>" />
                </div>
            </form>
        </div>

        <div id="login" class="tab-pane" role="tabpanel">
            <h2 class="mt-2"><?= $TEXT['login']; ?></h2>
            <form action="login.php" method="post">
                <div class="input-group has-validation w-75 mx-auto mt-2">
                    <span class="input-group-text"><?= $TEXT['username']; ?></span>
                    <input id="username" class="form-control" name="username" type="text" />
                    <div class="invalid-feedback">{{ feedback }}</div>
                </div>
                <div class="input-group has-validation w-75 mx-auto mt-2">
                    <span class="input-group-text"><?= $TEXT['password']; ?></span>
                    <input id="password" class="form-control" name="password" type="password" />
                    <button class="btn btn-secondary password-peeker" type="button">
                        <i class="bi-eye-slash" aria-hidden="true"></i>
                    </button>
                    <div class="invalid-feedback">{{ feedback }}</div>
                </div>
                <div class="w-75 mx-auto my-2">
                    <input class="btn btn-primary login w-100" type="submit" value="<?= $TEXT['submit']; ?>" />
                </div>
            </form>
        </div>

    </div>  <!-- tab-content -->

</div>  <!-- rounded-box -->
