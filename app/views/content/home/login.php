<div class="container">
    <?php
    if (!empty($error)) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-dismissible alert-danger">
                    <?=$error?>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="card border-secondary mb-3" style="margin-top: 3rem;">
        <div class="card-header">Please Log In</div>
        <div class="card-body">
            <form action="/login" method="POST">
                <div class="form-group row">
                    <label for="email" class="col-md-3 col-form-label text-right">Email Address</label>
                    <input type="email" class="form-control col-md-6" name="email" id="email">
                </div>
                <div class="form-group row">
                    <label for="password" class="col-md-3 col-form-label text-right">Password</label>
                    <input type="password" class="form-control col-md-6" name="password" id="password">
                </div>
                <div class="form-group row">
                    <div class="col-md-3 offset-md-3">
                        <button class="btn btn-primary">Log In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>