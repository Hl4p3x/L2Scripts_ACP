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
                <span><?php echo _s("STATISTICS_CAPS"); ?></span>
                <ol class="breadcrumb">
                    <li><?php echo _s("MASTER_ACCOUNT_CAP"); ?></li>
                    <li class="active"><?php echo _s("STATISTICS_CAPS"); ?></li>
                </ol>
            </section>
            <!-- END CONTENT HEADER -->

            <section class="content">
                <div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#PK" data-toggle="tab"><?php echo _s("PK"); ?></a></li>
                        <li class=""><a href="#Duel" data-toggle="tab"><?php echo _s("PVP"); ?></a></li>
                        <li class=""><a href="#use_time" data-toggle="tab"><?php echo _s("TIME_IN_GAME"); ?></a></li>
                        <li class=""><a href="#Lev" data-toggle="tab"><?php echo _s("LEVEL"); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active"
                             id="PK">
                            <div class="table-responsive" tabindex="3"
                                 style="overflow: hidden; outline: none;">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo _s("CHA_NAME"); ?></th>
                                            <th><?php echo _s("LEVEL"); ?></th>
                                            <th><?php echo _s("CLASS"); ?></th>
                                            <th><?php echo _s("TIME_IN_GAME"); ?></th>
                                            <th><?php echo _s("CLAN"); ?></th>
                                            <th><?php echo _s("PVP"); ?></th>
                                            <th><?php echo _s("PK"); ?></th>
                                            <th><?php echo _s("KARMA"); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($this->stats['pk'] as $index => $player): ?>
                                        <tr>
                                            <th><?php echo $index+1; ?></th>
                                            <td><?php echo $player['char_name']; ?></td>
                                            <td><?php echo $player['level']; ?></td>
                                            <td><?php echo $player['char_name'] != "" ? get_class_name($player['classid']) : ""; ?></td>
                                            <td><?php echo timify2($player['onlinetime']); ?></td>
                                            <td><?php echo $player['clan_name']; ?></td>
                                            <td><?php echo $player['pvpkills']; ?></td>
                                            <td><?php echo $player['pkkills']; ?></td>
                                            <td><?php echo $player['karma']; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane "
                             id="Duel">
                            <div class="table-responsive" tabindex="3"
                                 style="overflow: hidden; outline: none;">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo _s("CHA_NAME"); ?></th>
                                            <th><?php echo _s("LEVEL"); ?></th>
                                            <th><?php echo _s("CLASS"); ?></th>
                                            <th><?php echo _s("TIME_IN_GAME"); ?></th>
                                            <th><?php echo _s("CLAN"); ?></th>
                                            <th><?php echo _s("PVP"); ?></th>
                                            <th><?php echo _s("PK"); ?></th>
                                            <th><?php echo _s("KARMA"); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($this->stats['pvp'] as $index => $player): ?>
                                        <tr>
                                            <th><?php echo $index+1; ?></th>
                                            <td><?php echo $player['char_name']; ?></td>
                                            <td><?php echo $player['level']; ?></td>
                                            <td><?php echo $player['char_name'] != "" ? get_class_name($player['classid']) : ""; ?></td>
                                            <td><?php echo timify2($player['onlinetime']); ?></td>
                                            <td><?php echo $player['clan_name']; ?></td>
                                            <td><?php echo $player['pvpkills']; ?></td>
                                            <td><?php echo $player['pkkills']; ?></td>
                                            <td><?php echo $player['karma']; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane "
                             id="use_time">
                            <div class="table-responsive" tabindex="3"
                                 style="overflow: hidden; outline: none;">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo _s("CHA_NAME"); ?></th>
                                            <th><?php echo _s("LEVEL"); ?></th>
                                            <th><?php echo _s("CLASS"); ?></th>
                                            <th><?php echo _s("TIME_IN_GAME"); ?></th>
                                            <th><?php echo _s("CLAN"); ?></th>
                                            <th><?php echo _s("PVP"); ?></th>
                                            <th><?php echo _s("PK"); ?></th>
                                            <th><?php echo _s("KARMA"); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($this->stats['onlinetime'] as $index => $player): ?>
                                        <tr>
                                            <th><?php echo $index+1; ?></th>
                                            <td><?php echo $player['char_name']; ?></td>
                                            <td><?php echo $player['level']; ?></td>
                                            <td><?php echo $player['char_name'] != "" ? get_class_name($player['classid']) : ""; ?></td>
                                            <td><?php echo timify2($player['onlinetime']); ?></td>
                                            <td><?php echo $player['clan_name']; ?></td>
                                            <td><?php echo $player['pvpkills']; ?></td>
                                            <td><?php echo $player['pkkills']; ?></td>
                                            <td><?php echo $player['karma']; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane "
                             id="Lev">
                            <div class="table-responsive" tabindex="3"
                                 style="overflow: hidden; outline: none;">
                                <table class="table table-hover">
                                   <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo _s("CHA_NAME"); ?></th>
                                            <th><?php echo _s("LEVEL"); ?></th>
                                            <th><?php echo _s("CLASS"); ?></th>
                                            <th><?php echo _s("TIME_IN_GAME"); ?></th>
                                            <th><?php echo _s("CLAN"); ?></th>
                                            <th><?php echo _s("PVP"); ?></th>
                                            <th><?php echo _s("PK"); ?></th>
                                            <th><?php echo _s("KARMA"); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($this->stats['level'] as $index => $player): ?>
                                        <tr>
                                            <th><?php echo $index+1; ?></th>
                                            <td><?php echo $player['char_name']; ?></td>
                                            <td><?php echo $player['level']; ?></td>
                                            <td><?php echo $player['char_name'] != "" ? get_class_name($player['classid']) : ""; ?></td>
                                            <td><?php echo timify2($player['onlinetime']); ?></td>
                                            <td><?php echo $player['clan_name']; ?></td>
                                            <td><?php echo $player['pvpkills']; ?></td>
                                            <td><?php echo $player['pkkills']; ?></td>
                                            <td><?php echo $player['karma']; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>
</div>