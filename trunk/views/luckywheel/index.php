<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- BEGIN SIDEBAR -->
    <?php include ROOT_PATH."/views/sidebar.php"; ?>
    <!-- END SIDEBAR -->
    <!-- BEGIN MODALS -->
    <?php include ROOT_PATH."/views/modals.php"; ?>
    <!-- END MODALS -->
    <div id="global-content">
        <script src="/templates/kertas/assets/jspages/lucky_wheel.js"></script>
        <script type="text/javascript" src="http://jscrollpane.kelvinluck.com/script/jquery.mousewheel.js"></script>
        <script type="text/javascript" src="http://jscrollpane.kelvinluck.com/script/jquery.jscrollpane.min.js"></script>

        <script>
            SPIN_PRICE = <?php echo $this->price; ?>;
        </script>

        <aside class="right-side">
        <!-- BEGIN CONTENT HEADER -->
        <section class="content-header">
            <i class="fa fa-cog"></i>
            <span><?php echo _s("MASTER_ACCOUNT_CAP"); ?></span>
            <ol class="breadcrumb">
                <li><?php echo _s("MASTER_ACCOUNT_CAP"); ?></a></li>
                <li class="active"><?php echo _s("MASTER_ACCOUNT_CAP"); ?></li>
            </ol>
        </section>
        <!-- END CONTENT HEADER -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="grid">
                        <div class="grid-header">
                            <div class="grid-title"><i class="icon-certificate"></i><?php echo _s("LUCKY_WHEEL"); ?></div>
                        </div>
                        <div class="grid-body">
                            <div id="lucky-wheel">
                                <div class="wheel-load" style="display: none;">
                                    <div class="wheel-loading-gears"></div>
                                    <div class="wheel-loading"></div>
                                </div>
                                <div class="wheel-popup">
                                    <div class="win-popup" hidden="">
                                        <div class="win-img"><img src="http://cp.l2e-global.com//templates/e-global/images/wheel/win.png" alt=""></div>
                                        <div class="win-item-img"></div>
                                        <div class="win-item-name"><?php echo _s("ITEM_NAME"); ?></div>
                                                                            <div class="wheel-btn">
                                                <button class="wheel-start-btn" onclick="ContinueGame()"><?php echo _s("CONTINUE_GAME"); ?></button>
                                            </div>
                                                                    </div>

                                    <div class="no-money-popup" hidden="">
                                        <div class="no-money-text">
                                            <?php echo _s("NO_MONEY_TO_CONTINUE"); ?>                                </div>
                                    </div>

                                    <div class="error-popup" hidden="">
                                        <div class="error-popup-smile">=`(</div>
                                        <div class="error-popup-text">
                                            <div class="error-popup-text-head"><?php echo _s("YPS"); ?></div>
                                            <div><?php echo _s("WENT_WRONG_RELOAD"); ?></div>
                                        </div>
                                    </div>

                                    <div class="start-popup">
                                        <div class="wheel-title"><?php echo _s("LUCKY_WHEEL"); ?></div>
                                        <div class="wheel-info">
                                            <?php echo _s("TRY_YOUR_LUCK"); ?>
                                            <br><br><br><br>
                                            <?php echo _s("LEAVE_COMMENT"); ?>                                   <br><br><br><br>
                                            <br><br>
                                            <span class="global-blue"><?php echo _s("ONE_PLAY_EQUALS"); ?></span>
                                            <br><br>
                                            <?php echo _s("WHICH_PLAYER_PLAYS"); ?>
                                            <br><br>
                                            <span class="global-red"><?php echo _s("ATTN_OFF_LINE_PL"); ?></span>
                                            <br><br>
                                        </div>
                                        <select id="char_name" onchange="ShowWheelBtn()">
                                                <option><?php echo _s("CHOOSE_CHAR"); ?></option>
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
                                        <br><br><br><br>
                                        <div class="wheel-btn">
                                                                                    <button class="wheel-start-btn" onclick="WheelStart()"><?php echo _s("START_GAME"); ?></button>
                                                                            </div>
                                                                    </div>
                                </div>
                                <div class="wheel-text" hidden="">
                                    <div class="wheel-char"></div>
                                    <div class="wheel-balance"><?php echo _s("BALANCE"); ?>: <span><?php echo $this->balance; ?></span> <?php echo _s("CURRENCY"); ?></div>
                                </div>
                                <div class="wheel-count"></div>
                                <div class="wheel-buttonoff" hidden=""></div>
                                                            <div class="wheel-button" onclick="spin()"></div>
                                                        <div class="wheel-main"></div>
                                <div class="wheel-border"></div>
                                <div class="wheel-bg"></div>

                                <div class="wheel-gear gospin3">
                                    <?php $idx = 1; foreach ($this->active_prizes as $prize): ?>
                                    <div class="gear-item gear-item<?php echo $idx++; ?>">
                                        <div class="wheel-glow-effect" hidden=""></div>
                                        <div class="wheel-prize-name"><?php echo $prize['name']; ?></div>
                                        <div class="wheel-prize"><img src="<?php echo $prize['icon']; ?>" alt=""></div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div id="prize-block" class="jspScrollable" tabindex="0">
                                <?php foreach ($this->prizes as $prize): ?>
                                <div class="prize-item">
                                    <div class="prize-item_img"><img src="<?php echo $prize['icon']; ?>" alt=""></div>
                                    <div class="prize-item_name"><?php echo $prize['name']; ?></div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="grid well-total-info">
                            <div class="grid-header">
                                <div class="grid-title" ><?php echo _s("STATISTICS"); ?></div>
                            </div>
                            <div class="grid-body">
                                <div><?php echo _s("MY_SPINS"); ?>: <span class="totalSpinning"><?php echo $this->user_spins; ?></span></div>
                                <div><?php echo _s("TOTAL_SPINS"); ?>: <span class="totalSpinning"><?php echo $this->total_spins; ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
        </div>

    </div>
</div>