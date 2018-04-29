<div class="outer">
    <div class="middle">
        <div class="inner">
            <div class="row">
                <!-- BEGIN LOGIN BOX -->
                <div class="col-lg-12">
                    <h3 class="text-center login-title"><?php echo _s("PASS_RECOVERY_FOR_MA"); ?></h3>
                    <div class="account-wall">
                        <!-- BEGIN PROFILE IMAGE -->
                        <img class="profile-img" src="/templates/kertas/assets/img/photo.png" alt="">
                        <!-- END PROFILE IMAGE -->
                        <!-- BEGIN LOGIN FORM -->
                        <form name="recover" action="/account/recovery" class="form-login"  method="POST">
                            <div id="errorarea"></div>
                            <input type="text" name="email" id="email" class="form-control" placeholder="<?php echo _s("MAIL"); ?>" autofocus>
                            <p id="errorsMessage" class="sendemail"><?php echo $this->message; ?></p>
                            <div style="margin-top:5px;margin-bottpm:5px;" class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_PUBLIC_KEY; ?>"></div>
                            <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo _s("SEND_INSTRUCTION"); ?></button>
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