<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- BEGIN SIDEBAR -->
    <?php include ROOT_PATH."/views/sidebar.php"; ?>
    <!-- END SIDEBAR -->
    <!-- BEGIN MODALS -->
    <?php include ROOT_PATH."/views/modals.php"; ?>
    <!-- END MODALS -->
    <div id="global-content">
        <!-- BEGIN CONTENT -->
        <aside class="right-side">
            <!-- BEGIN CONTENT HEADER -->
            <section class="content-header">
                <i class="fa fa-home"></i>
                <span><?php echo _s("MASTER_ACCOUNT_CAP"); ?></span>
                <ol class="breadcrumb">
                    <li><?php echo _s("MAIN_PAGE_CAP"); ?></li>
                </ol>
            </section>
            <!-- END CONTENT HEADER -->z
            <!-- BEGIN MAIN CONTENT -->
            <section class="content">
                <center><div class="row" style="min-width: 850px;max-width: 850px;">
                    <div class="col-md-4" style="margin-bottom: 25px;">
					<div class="modal-content" style="min-width: 395px;max-width: 395px;padding: 20px; background-color: rgb(0, 0, 0) !important;">
                            <div class="grid-body"  style="text-align: left";>
                                <h4 style="margin-top: -5px; color: #b3b3b3;"><?php echo _s("MASTER_ACCOUNT"); ?>:	
                                    </br><small style="color: #fff;"><?php echo $this->email; ?></small>
                                </h4>
								<hr style="margin-top: 15px;margin-bottom: 10px; border-color: #b3b3b3;">
                                    <div class="row">
                                        <div style="margin-left: 15px; color: #b3b3b3;"><?php echo _s("LAST_LOGIN"); ?><font style="margin-left: 85px; color: #fff;"><?php echo $this->lasttime; ?></font></div>
                                    </div>
                                    <div class="row">
                                        <div style="margin-left: 15px; color: #b3b3b3;"><?php echo _s("LAST_IP"); ?><font style="margin-left: 100px; color: #fff;"><?php echo $this->lastip; ?></font></div>
                                    </div>
									<div class="row">
                                        <div style="margin-left: 15px;margin-bottom: 10px; color: #b3b3b3;"><?php echo _s("GAME_ACCOUNTS"); ?>: <font style="margin-left: 103px; color: #fff;"><?php echo count($this->game_accounts); ?> <?php echo _s("FROM"); ?> <?php echo $this->max_accounts; ?></font></div>  
                                    </div>
									<hr style="border-color: #b3b3b3;margin-top: 2px;margin-bottom: 6px;">
                                    <div class="row" style="text-align: center;">
                                        <div style="margin-top: 15px;margin-bottom: 17px;"><a class="link" data-toggle="modal" data-target="#modalCreateGameAccount" href="#"><b style="color: #fff;background-color: rgb(33, 150, 243);border: 1px #dcdcdc solid;font-weight: 500;padding: 2px 3px; padding-left: 10px; padding-right: 10px;"><?php echo _s("CREATE_GAME_ACCOUNT_CAP"); ?></b></a></div>
                                    </div>
                                    <div class="row" style="text-align: center;">
                                        <div><a class="link" data-toggle="modal" data-target="#modalChangePasswordMA" href="#"><b style="color: #fff;background-color: rgb(33, 150, 243);border: 1px #dcdcdc solid;font-weight: 500;padding: 2px 3px; padding-left: 27px; padding-right: 26px;"><?php echo _s("CHANGE_MA_PASS_CAP"); ?></b></a>
                                        </div>
                                    </div>
                            </div>
                </div>
                    </div>
					 <div class="col-md-4" style="text-align: left;">
					<div class="modal-content" style="min-width: 395px;max-width: 395px; padding: 10px;height: 252px;">
                    <b><?php echo _s("GAME_ACCOUNTS"); ?></b> <?php echo _s("GOLD_DESCR1"); ?>
	                 </br><?php echo _s("GOLD_DESCR2"); ?>

                </div>
                    </div>
					<div class="col-md-4">
				<center><div class="modal-content" style="min-width: 820px;max-width: 395px;margin-left: -425px;margin-top: 25px;margin-bottom: 25px;padding: 20px;font-size: 25px;background-color: #000;color: #b3b3b3;"> 
				<div style="font-size: 13px; text-align:left;"><b style="color: red;"><?php echo _s("ATTN_CAPS"); ?></b>
				<br><?php echo _s("ATTN_DESC1"); ?>
				<?php echo _s("ATTN_DESC2"); ?>
				<?php echo _s("ATTN_DESC3"); ?> <br><?php echo _s("ATTN_DESC4"); ?> <b style="color: #ffa416;"><?php echo _s("DONATE_COIN"); ?></b>.
				<br><b style="color: #e2e2e2;"><?php echo _s("ATTN_DESC5"); ?></b></div>
				</div>
				</center>
                    </div>
					</div></center>
				<div class="row" style="min-width: 850px;"><div class="col-md-12">
                <div class="row" style="min-width: 850px;">
                    <div class="col-md-12">
                        <?php foreach ($this->game_accounts as $account => $characters): ?>
                        <div class="grid" style="margin-top: 10px;">
                            <div class="grid-header">
                                <span class="grid-title login_data"><?php echo _s("GAME_ACCOUNT"); ?>: <b><?php echo $account; ?></b></span>
                                <div class="actions pull-right">
                                    <button class="btn btn-success waves-effect btn-xs" data-toggle="modal" data-target="#modalChangePasswordGA" onclick="setLogin(this)"><?php echo _s("CHANGE_PASS"); ?></button>
                                    <button type="button" class="btn btn-primary btn-xs waves-effect" data-toggle="modal" data-target="#modalRecoverPasswordGA" onclick="setLogin(this)"><?php echo _s("RESTORE_PASS"); ?></button>
                                </div>
                            </div>
                            <div class="grid-body table-responsive" tabindex="0" style="overflow: hidden; outline: none;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;"><?php echo _s("CHA_NAME"); ?></th>
                                            <th style="text-align: center;"><?php echo _s("LEVEL"); ?></th>
                                            <th style="text-align: center;"><?php echo _s("CLASS"); ?></th>
                                            <th style="text-align: center;"><?php echo _s("CLAN"); ?></th>
                                            <th style="text-align: center;"><?php echo _s("TIME_IN_GAME"); ?></th>
                                            <th style="text-align: center;"><?php echo _s("PVP"); ?></th>
                                            <th style="text-align: center;"><?php echo _s("PK"); ?></th>
                                            <th style="text-align: center;"><?php echo _s("KARMA"); ?></th>
                                            <th style="text-align: center;"><?php echo _s("STATUS"); ?></th>
                                            <th><?php echo _s("INVENTORY"); ?></th>
											<th><?php echo _s("EDITCHAR"); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($characters as $char): ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $char['char_name']; ?></td>
                                            <td style="text-align: center;"><?php echo $char['level']; ?></td>
                                            <td style="text-align: center;"><?php echo get_class_name($char['classid']); ?></td>
                                            <td style="text-align: center;"><?php echo $char['clan_name'] != "" ? $char['clan_name'] : _s("NO"); ?></td>
                                            <td style="text-align: center;"><?php echo timify($char['onlinetime']); ?></td>
                                            <td style="text-align: center;"><?php echo $char['pvpkills']; ?></td>
                                            <td style="text-align: center;"><?php echo $char['pkkills']; ?></td>
                                            <td style="text-align: center;"><?php echo $char['karma']; ?></td>
                                            <td style="text-align: center;"><?php echo $char['online'] == 1 ? _s("ONLINE") : _s("OFFLINE") ?></td>
                                            <td>
                                                <a href="/character/inventory/<?php echo $char['char_name']; ?>" class="btn btn-info">
                                                    <i class="md md-today"></i> <?php echo _s("INVENTORY"); ?>
                                                </a>
                                            </td>
											<td>
                                                <button class="btn btn-inverse" onclick="editChar.openMainModal('<?php echo $char['char_name']; ?>');"><?php echo _s("EDIT"); ?></button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- END STATS -->
            </section>
        </aside>
    </div>
</div>