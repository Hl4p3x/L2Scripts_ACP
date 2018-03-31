<div class="outer">
    <div class="middle">
        <div class="inner">
            <div class="row">
                <!-- BEGIN LOGIN BOX -->
                <div class="col-lg-12">
                    <h3 class="text-center login-title"><?php echo _s("REGISTRATION"); ?></h3>
                    <div class="account-wall">
                        <!-- BEGIN PROFILE IMAGE -->
                        <img class="profile-img" src="/templates/kertas/assets/img/photo.png" alt="">
                        <!-- END PROFILE IMAGE -->
                        <!-- BEGIN LOGIN FORM --><div class="col-md-12" style="font-size: 10px; margin-bottom: -40px; margin-top: -50px; padding: 50px;">
									<b><div style="color: #ff0000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo _s("AFTER_REG1"); ?> &nbsp;&nbsp;&nbsp;<?php echo _s("AFTER_REG2"); ?></div></b>
									</div>
                        <form id="regForm" action="/account/register" class="form-login form-horizontal" method="POST">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" name="login" class="form-control" placeholder="<?php echo _s("ACCOUNT"); ?>"><div class="col-md-12" style="font-size: 10px; margin-left: -15px; margin-top: 2px;">
									<?php echo _s("USE_ONLY_LATIN_SYN"); ?>
									</div>
                                    <input type="password" name="password" class="form-control" placeholder="<?php echo _s("PASSWORD"); ?>">
                                    <input type="password" name="password_confirm" class="form-control" placeholder="<?php echo _s("CONFIRM_PASS"); ?>">
                                    <input type="text" name="email" class="form-control" placeholder="<?php echo _s("MAIL"); ?>">
									<div class="col-md-12" style="font-size: 10px; margin-left: -15px; margin-top: 2px;">
									<?php echo _s("REGISTRATION_SPEC"); ?>
									</div>
                                </div>
                            </div>
                            <select style="margin-top:10px" id="server_id" name="server_id">
                                <?php foreach ($this->servers as $server_id => $server): ?>
                                    <option style="color: black;" value="<?php echo $server_id; ?>"><?php echo $server['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p id="errorsMessage" class="error"><?php echo $this->error; ?></p>
                            <div style="margin-bottom: 10px;" class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_PUBLIC_KEY; ?>"></div>
                            <div class="alert alert-dismissable alert-danger text-center" id="message" style="display: none"></div>
                            <button type="button" class="btn btn-lg btn-primary btn-block has-spinner" id="btn-SCode" onclick="regAccount(this);return false;"><?php echo _s("SIGN_UP"); ?></button>
                        	<hr style="margin-bottom: 20px; border: 0; border-top: 1px solid #d0d0d0;">
                        	<center><a href="/account/login" class="clearfix" type="submit"><?php echo _s("AUTHORIZE"); ?></a></center>
						</form>
                        <!-- END LOGIN FORM -->
                    </div>
                </div>
                <!-- END LOGIN BOX -->
            </div>
        </div>
    </div>
</div>