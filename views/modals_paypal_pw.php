    <!-- BEGIN ACCOUNT CREATE -->
    <div class="modal fade" id="modalCreateGameAccount" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-wrapper">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" id="create_game_account_form" class="form-horizontal" method="post">
                        <div class="modal-header bg-blue">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <center><h4 class="modal-title"><?php echo _s("CREATE_GAME_ACCOUNT"); ?></h4></center>
                        </div>
                        <div class="modal-body">
                            <div class="alert" style="margin-bottom: 20px;text-align: center;" id="errorCreateGameAccount">

                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo _s("ACCOUNT2"); ?></label>

                                <div class="col-sm-8">
                                    <input type="text" name="login" class="form-control" placeholder="<?php echo _s("ACCOUNT"); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo _s("PASSWORD2"); ?></label>

                                <div class="col-sm-8">
                                    <input type="password" name="password" class="form-control" placeholder="<?php echo _s("PASSWORD"); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo _s("CONFIRM_PASS2"); ?></label>

                                <div class="col-sm-8">
                                    <input type="password" name="password_confirm" class="form-control" placeholder="<?php echo _s("CONFIRM_PASS"); ?>">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _s("CLOSE"); ?></button>
                                    <button type="button" class="btn btn-primary" onclick="createGameAcc(this)"><?php echo _s("CREATE_GAME_ACCOUNT"); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END ACCOUNT CREATE -->
    <!-- BEGIN MA CHANGE PASS -->
    <div class="modal fade" id="modalChangePasswordMA" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-wrapper">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" id="change_password_MA_form" class="form-horizontal" method="post">
                        <div class="modal-header bg-blue">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <center><h4 class="modal-title"><?php echo _s("CHANGE_MA_PASS"); ?></h4></center>
                        </div>
                        <div class="modal-body">
                            <div class="alert" style="margin-bottom: 20px;text-align: center;" id="errorChangePasswordMA">

                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo _s("OLD_PASS2"); ?></label>

                                <div class="col-sm-8">
                                    <input type="password" name="old_password" class="form-control" placeholder="<?php echo _s("OLD_PASS"); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo _s("NEW_PASS2"); ?></label>

                                <div class="col-sm-8">
                                    <input type="password" name="password" class="form-control" placeholder="<?php echo _s("NEW_PASS"); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo _s("CONFIRM_PASS2"); ?></label>

                                <div class="col-sm-8">
                                    <input type="password" name="password_confirm" class="form-control" placeholder="<?php echo _s("CONFIRM_PASS"); ?>">
                                </div>
                            </div>
                            <input type="hidden" name="request" value="change_pass_MA">

                            <div class="alert alert-dismissable alert-danger text-center" id="message_block_MA"
                                 style="display: none"></div>
                            <div class="modal-footer">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _s("CLOSE"); ?></button>
                                    <button type="button" id="send_code" onclick="changePasswordMA(this)" class="btn btn-primary"><?php echo _s("CHANGE_PASS"); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END MA CHANGE PASS -->
    <!-- BEGIN COIN TRANSFER -->
    <div class="modal fade" id="modalCoinTransfer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-wrapper" style="width: 573px;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-blue">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <center><h4 class="modal-title"><i class="fa fa-mail-forward"></i> <?php echo _s("DONATE_COIN"); ?></h4></center>
                    </div>
                    <div class="modal-body">
                        <div class="alert" id="errorCoinTransfer">

                        </div>
                        <form class="form-horizontal" role="form" id="coinsForm">
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo _s("COUNT"); ?>:</label>
                                <div class="col-sm-6" style="width: 360px;">
                                    <input type="text" name="euro" id="euro" class="form-control" placeholder="<?php echo _s("COUNT"); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo _s("CHOOSE_CHAR2"); ?>:</label>
                                <div class="col-sm-6">
                                    <select id="source" name="char" class="form-control" style="margin-bottom: 10px; width: 330px;">
                                        <?php foreach ($this->game_accounts as $account => $characters): ?>
                                        <?php if (count($characters) > 0): ?>
                                        <optgroup label="<?php echo $account; ?>">
                                        <?php foreach ($characters as $char): ?>
                                            <option value="<?php echo $char['char_name']; ?>"><?php echo $char['char_name']; ?></option>
                                        <?php endforeach; ?>
                                        </optgroup>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
							<hr style="border-top: 1px solid #e5e5e5;margin-top: 0px;width: 508px;">
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10" style="margin-left: 360px;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary has-spinner" onclick="sendCoins(this);" style="margin-bottom: -10px;"><?php echo _s("TRANSFER_COINS_1"); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END COIN TRANSFER -->
    <!-- START GAME ACCOUNT PASSWORD -->
    <div class="modal fade" id="modalChangePasswordGA" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-wrapper">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-blue">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <center><h4 class="modal-title"><?php echo _s("CHANGE_PASS_FOR"); ?>: <span class="login_head"></span></h4></center>
                    </div>
                    <div class="modal-body" style="margin-bottom: -15px;">
                        <div class="alert" style="margin-bottom: 20px;text-align: center;" id="errorChangePasswordGA"></div>
                        <form class="form-horizontal" role="form" id="change_pass">
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo _s("OLD_PASS2"); ?></label>

                                <div class="col-sm-8">
                                    <input type="password" name="old_password" class="form-control" placeholder="<?php echo _s("OLD_PASS"); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo _s("NEW_PASS2"); ?></label>

                                <div class="col-sm-8">
                                    <input type="password" name="password" class="form-control" placeholder="<?php echo _s("NEW_PASS"); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo _s("CONFIRM_PASS2"); ?></label>

                                <div class="col-sm-8">
                                    <input type="password" name="password_confirm" class="form-control" placeholder="<?php echo _s("CONFIRM_PASS"); ?>">
                                    <input type="hidden" name="request" value="change_password">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group" style="margin-right: 20px;margin-bottom: 20px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _s("CLOSE"); ?></button>
                            <button type="button" class="btn btn-primary" onclick="changePasswordGA(this)"><?php echo _s("CHANGE_PASS"); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRecoverPasswordGA" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-wrapper">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" id="recover_pass" class="form-horizontal" method="post">
                        <div class="modal-header bg-blue">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <center><h4 class="modal-title"><?php echo _s("RESTORE_PASS_FOR"); ?>: <span class="login_head"></span></h4></center>
                        </div>
                        <div class="modal-body" style="text-align: center; margin-bottom: -15px;">
                            <div class="alert" style="margin-bottom: 20px;text-align: center;" id="errorRecoverPasswordGA"></div>
                            <span style="font-size: 12pt;"><?php echo _s("NEW_TEMP_PASS"); ?>: <b><?php echo $this->email; ?></b>.</span>
                            <h5><?php echo _s("SURE_CONTINUE"); ?></h5>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group" style="margin-right: 20px;margin-bottom: 20px;">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _s("CLOSE"); ?></button>
                                <button type="button" class="btn btn-primary" onclick="recoverPasswordGA(this)"><?php echo _s("RESTORE_PASS"); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function nextpayButton(e) {
            $('#unitpayContainer').hide();
            $('#nextpayContainer').show();
        }
        
        function unitpayButton(e) {
            $('#nextpayContainer').hide();
            $('#unitpayContainer').show();
        }
    </script>
    <!-- END GAME ACCOUNT PASSWORD -->
    <div class="modal fade" id="modalDonate" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-wrapper" style="width: 468px;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-blue">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <center><h4 class="modal-title"><i class="fa fa-bank fa-lg"></i> <?php echo _s("ADD_CURRENCY_MA"); ?></h4></center>
                    </div>
                    <div id="donateDefault" class="modal-body" style="text-align: center">
                        <button type="button" class="btn btn-primary" onclick="paymentwallButton(this)">PaymentWall</button> <button type="button" class="btn btn-primary" onclick="paypalButton(this)">PayPal</button> <button type="button" class="btn btn-primary" onclick="payseraButton(this)">Paysera</button>
                    </div>
                    <div class="modal-body" style="display: none; text-align: center" id="paymentwallContainer">
                        <b>PaymentWall</b>
                        <form action="/donate/pw_widget" method="post"> 
                            <?php echo _s("HOW_MANY_NEED"); ?> <input type="text" value="300" name="amount" style="margin-left: 10px;margin-right: -3px;width: 229px;" />
                            <div class="col-md-12" style="font-size: 10px; margin-left: 75px; margin-top: 2px;margin-bottom: 15px;"><?php echo _s("RECOMMEND_NOT_LESS"); ?></div>
                            <div class="col-md-12" style="font-size: 10px; margin-left: 38px; margin-top: -12px;margin-bottom: 15px;"><?php echo _s("1USD1COIN"); ?></div>
                            </br></br>
                            <hr style="border-top: 1px solid #e5e5e5;margin-top: 13px;width: 395px;">
                            <input type="submit" value="<?php echo _s("ADD"); ?>" style="color: #111;background-color: transparent;border: 1px #111 solid;padding: 6px; margin-left: 300px; width: 95px;" />
                        </form>
                    </div>
                    <div class="modal-body" style="display: none; text-align: center" id="paypalContainer">
                        <b>PayPal</b>
                        <form name="contribution_form" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_paypal"> 
                            <input type="hidden" value="1" name="upload">
                            <input type="hidden" value="pay" name="cancel_return">
                            <input type="hidden" value="<?php echo URL; ?>/account/index" name="return">
                            <input type="hidden" value="1" name="no_note">
                            <input type="hidden" value="1" name="no_shipping">
                            <input type="hidden" value="Coins" name="item_name_1">
                            <input type="hidden" value="pay" name="bn">
                            <input type="hidden" value="<?php echo PAYPAL_RECEIVER_EMAIL; ?>" name="business">
                            <input type="hidden" value="_cart" name="redirect_cmd">
                            <input type="hidden" value="_ext-enter" name="cmd">
                            <input type="hidden" value="<?php echo DONATE_CURRENCY; ?>" name="currency_code">
                            <input type="hidden" name="custom" value="<?php echo $this->uid; ?>">
                            <input type="hidden" name="account" value="<?php echo $this->uid; ?>" />
                            <?php echo _s("HOW_MANY_NEED"); ?> <input type="text" value="300" name="amount_1" style="margin-left: 10px;margin-right: -3px;width: 229px;" />
                            <div class="col-md-12" style="font-size: 10px; margin-left: 75px; margin-top: 2px;margin-bottom: 15px;"><?php echo _s("RECOMMEND_NOT_LESS"); ?></div>
                            <div class="col-md-12" style="font-size: 10px; margin-left: 38px; margin-top: -12px;margin-bottom: 15px;"><?php echo _s("1USD1COIN"); ?></div>
                            </br></br>
                            <hr style="border-top: 1px solid #e5e5e5;margin-top: 13px;width: 395px;">
                            <input type="submit" value="<?php echo _s("ADD"); ?>" style="color: #111;background-color: transparent;border: 1px #111 solid;padding: 6px; margin-left: 300px; width: 95px;" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="display: none;">
        <div class="box-modal" id="modal-edit-char">
            <div class="modal-l2-wp">
                <div class="modal-edit-char-row">
                    <div class="modal-edit-char-label"><?php echo _s("CHANGE_CHA_NAME"); ?></div>
                    <div class="modal-edit-char-btn">
                        <div class="l2-button" onclick="editChar.openNextModal('name');"><?php echo _s("CHANGE"); ?></div>
                    </div>
                </div>

                <div class="modal-edit-char-row modal-edit-gender-row">
                    <div class="modal-edit-char-label"><?php echo _s("CHANGE_CHA_SEX"); ?></div>
                    <div class="modal-edit-char-btn">
                        <div class="l2-button" onclick="editChar.openNextModal('gender');"><?php echo _s("CHANGE"); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-modal" id="modal-edit-name">
            <div class="modal-l2-wp">
                <div class="modal-edit-info">
                    <div class="modal-edit-info-text"><?php echo _s("SET_NEW_CHA_NAME"); ?></div>
                    <div class="modal-edit-info-cost"><?php echo _s("CHA_NAME_COST"); ?></div>

                </div>

                <div class="modal-edit-tools">
                    <input type="text" id="edit-char-name" maxlength="16" placeholder="<?php echo _s("NEW_NAME"); ?>">
                    <div class="edit-input-helper"><?php echo _s("ONLY_LETTER_NUMBERS"); ?></div>
                </div>

                <div class="modal-edit-btn-row">
                    <a href="#" onclick="editChar.editName(); return false;" class="l2-button" ><?php echo _s("CONFIRM"); ?></a>
                    <a href="#" onclick="editChar.closeNextModal(); return false;" class="l2-button"><?php echo _s("CANCEL"); ?></a>
                </div>
            </div>
        </div>

        <div class="box-modal" id="modal-edit-gender">
            <div class="modal-l2-wp">
                <div class="modal-edit-info">
                    <div class="modal-edit-info-text"><?php echo _s("CHANGE_CHA_SEX"); ?></div>
                    <div class="modal-edit-info-cost"><?php echo _s("CHA_SEX_COST"); ?></div>
                </div>

                <div class="modal-edit-tools">
                    <div class="modal-edit-gender-row"><div class="modal-edit-info-text"><?php echo _s("CONFIRM_SEX_CHANGE"); ?></div></div>
                </div>

                <div class="modal-edit-btn-row">
                    <a href="#" onclick="editChar.editGender(); return false;" class="l2-button" ><?php echo _s("CONFIRM"); ?></a>
                    <a href="#" onclick="editChar.closeNextModal(); return false;" class="l2-button"><?php echo _s("CANCEL"); ?></a>
                </div>
            </div>
        </div>
    </div>