<div class="container">
    <div class="masthead">
       <h3 class="text-muted"><?= APP_NAME; ?></h3>

        <nav class="navbar navbar-light bg-faded rounded mb-3 bg-bluesh">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-toggleable-md" id="navbarCollapse">
                <ul class="nav navbar-nav text-md-center justify-content-md-between">
                    <li class="nav-item active">
                        <a class="nav-link" href="./">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <?php if( isset($_SESSION['user']) && $_SESSION['user'] != null ): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./logout.php">Logout</a>
                    </li>
                <?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>
</div>