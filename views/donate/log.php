<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- BEGIN SIDEBAR -->
    <?php include ROOT_PATH."/views/sidebar.php"; ?>
    <!-- END SIDEBAR -->
    <!-- BEGIN MODALS -->
    <?php include ROOT_PATH."/views/modals.php"; ?>
    <!-- END MODALS -->
    <div id="global-content">
        <aside class="right-side">
        <!-- BEGIN CONTENT HEADER -->
        <section class="content-header">
            <i class="fa fa-bar-chart-o"></i>
            <span><?php echo _s("TRANS_HISTORY_CAPS"); ?></span>
            <ol class="breadcrumb">
                <li><?php echo _s("MASTER_ACCOUNT_CAP"); ?></a></li>
                <li class="active"><?php echo _s("TRANS_HISTORY_CAPS"); ?></li>
            </ol>
        </section>
        <!-- END CONTENT HEADER -->

        <!-- BEGIN MAIN CONTENT -->
        <section class="content">
                <div class="row">
                    <!-- BEGIN RESPONSIVE TABLE -->
                    <div class="col-md-12">
                        <div class="grid no-border" style="zoom: 1;">
                            <div class="grid-header">
                                <i class="fa fa-puzzle-piece"></i>
                                <span class="grid-title"><?php echo _s("TRANSACTION_HIST"); ?></span>
                            </div>
                            <div class="grid-body" style="display: block;">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><?php echo _s("ACTION"); ?></th>
                                                <th><?php echo _s("DATE"); ?></th>
                                                <th><?php echo _s("CHARACTER"); ?></th>
                                                <th><?php echo _s("AMMOUNT"); ?></th>
                                                <th><?php echo _s("BALANCE"); ?></th>
                                                <th><?php echo _s("SERVER"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (count($this->transaction_log) == 0): ?>
                                            <tr><td colspan="6" style="text-align: center"><?php echo _s("NO_TRANSACTIONS"); ?></td></tr>
                                        <?php endif; ?>
                                        <?php foreach ($this->transaction_log as $transaction): ?>
                                            <tr>
                                                <th><?php echo $transaction['action']; ?></th>
                                                <th><?php echo $transaction['date']; ?></th>
                                                <th><?php echo $transaction['character']; ?></th>
                                                <th><?php echo $transaction['amount']; ?></th>
                                                <th><?php echo $transaction['balance']; ?></th>
                                                <th><?php echo $transaction['server']; ?></th>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END RESPONSIVE TABLE -->
                </div>
        </section>
    </div>
    <!-- BEGIN SCROLL TO TOP -->
    <div class="scroll-to-top"></div>
    <!-- END SCROLL TO TOP -->
</div>