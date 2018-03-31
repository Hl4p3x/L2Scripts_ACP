<div class="outer">
    <div class="middle">
        <div class="inner">
            <div class="row">
                <!-- BEGIN LOGIN BOX -->
                <div class="col-lg-12">
                    <h3 class="text-center login-title"><?php echo _s("AUTHORIZE_TO_CONTINUE"); ?></h3>
                    <div class="account-wall">
                        <!-- BEGIN PROFILE IMAGE -->
                        <img class="profile-img" src="/templates/kertas/assets/img/photo.png" alt="">
                        <!-- END PROFILE IMAGE -->
                        <!-- BEGIN LOGIN FORM -->
                        <form name="login" action="/login" class="form-login"  method="POST">
                            <div id="errorarea"></div>
                            <input type="text" name="login" id="login" class="form-control" placeholder="<?php echo _s("MAIL_OR_ACCOUNT"); ?>" autofocus>
                            <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo _s("PASSWORD"); ?>">
                            <select style="margin-top:10px" id="server_id" name="server_id">
                                <?php foreach ($this->servers as $server_id => $server): ?>
                                    <option style="color: black;" value="<?php echo $server_id; ?>" <?php $server_id == $this->active_server ? 'selected' : ''; ?>><?php echo $server['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p id="errorsMessage" class="error"><?php echo $this->error; ?></p>
                            <div style="margin-top:5px;margin-bottpm:5px;" class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_PUBLIC_KEY; ?>"></div>
                            <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo _s("LOGIN"); ?></button>
                            <label class="checkbox pull-left">
                                <input type="checkbox" name="remember" id="remember" value="0"><?php echo _s("REMEMBER"); ?>
                            </label>
                            <a href="recovery" class="pull-right need-help"><?php echo _s("RECOVER"); ?></a><span class="clearfix"></span>
                            <hr style="margin-bottom: 30px; border: 0; border-top: 1px solid #d0d0d0;">
                            <center><a href="/account/register" class="clearfix" type="submit"><?php echo _s("CREATE_ACCOUNT"); ?></a></center>
                        </form>
                        <!-- END LOGIN FORM -->
                    </div>
                </div>
                <!-- END LOGIN BOX -->
            </div>
        </div>
    </div>
</div>

<?php echo "<script>error(" . $this->error . ");</script>" ?>