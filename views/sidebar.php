    <aside class="left-side sidebar-offcanvas">
        <section class="sidebar" style="position:fixed;width:250px;">
		<br><br><br><br><br><br>
            <div class="discount-info">
                <?php echo _s("BALANCE"); ?>: <span><?php echo DONATE_CURRENCY_SYMBOL; ?><span id="cur_balance"><?php echo $this->balance; ?></span></span>
            </div>
            <!-- Are we doing this? -->
            <div class="input-group">
                <div class="form-group">
                    <label style='display:none;'  class="col-sm-2 control-label" style="line-height: 3; color: #ffffff; margin-right: 10px"><?php echo _s("SERVER"); ?>: </label>
                    <div style='display:none;' class="col-sm-9">
                        <select id="servers" class="form-control" style="background-color: rgba(255, 255, 255, 0.1);  border: 0 solid rgba(255, 255, 255, 0.1); width: 100%; color: white;">
                            <?php foreach ($this->servers as $server_id => $server): ?>
                                <option style="color: black;" value="<?php echo $server_id; ?>" <?php $server_id == $this->active_server ? 'selected' : ''; ?>><?php echo $server['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="/account/index/">
                        <i class="fa fa-home"></i><span><?php echo _s("MAIN_PAGE"); ?></span>
                    </a>
                </li>
                <li>
                    <a href="https://nationwarriors.com" target="_blank" >
                        <i class="fa fa-th-large"></i><span>Site</span>
                    </a>
                </li>
				<li>
                    <a href="https://community.nationwarriors.com" target="_blank">
                        <i class="fa fa-th-large"></i><span><?php echo _s("FORUM"); ?></span>
                    </a>
                </li>
                <?php if ($this->features['ticket'] === true): ?>
                <?php endif; ?>
                <?php if ($this->features['auction'] === true): ?>
                <li>
                    <a href="/auction/index">
                        <i class="fa fa-gavel"></i><span><?php echo _s("AUCTION"); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($this->features['luckywheel'] === true): ?>
                <li>
                    <a href="/luckywheel/index">
                        <i class="fa fa-cog"></i></i><span><?php echo _s("LUCKY_WHEEL"); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($this->features['vote'] === true): ?>
                <li>
                    <a href="/vote">
                        <i class="fa fa-thumbs-up"></i></i><span><?php echo _s("VOTE"); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($this->features['donate'] === true): ?>
                <li>
                    <a href="/donate/log">
                        <i class="fa fa-forward"></i><span><?php echo _s("TRANSACTION_HIST"); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($this->features['stats'] === true): ?>
                <li>
                    <a href="/account/stat">
                        <i class="fa fa-bar-chart-o"></i><span><?php echo _s("STATISTICS"); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($this->features['ticket'] === true): ?>
		<li>
                    <a href="/ticket">
                        <i class="fa  fa-ticket"></i><span><?php echo _s("TECH_SUPPORT"); ?></span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
			<br>
			<img width="300px" height="80px" src="https://cp.nationwarriors.com/imgs/classic_logo.png"/>
        </section>
    </aside>
