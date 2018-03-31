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
                <i class="fa fa-ticket"></i>
                <span><?php echo _s("SUPPORT"); ?></span>
                <ol class="breadcrumb">
                    <li><?php echo _s("ACCOUNT"); ?></li>
                    <li class="active"><?php echo _s("SUPPORT"); ?></li>
                </ol>
            </section>
            <!-- END CONTENT HEADER -->

            <!-- BEGIN MAIN CONTENT -->
            <section class="content tickets-cont">
                <div class="row">
                    <div class="col-md-12">
                        <div class="grid">
                           <?php if ($this->admin == 0): ?>
                           <div class="grid-header">
                               <div class="col-md-6"><a class="btn btn-block btn-success" href="/ticket/create"><?php echo _s("NEW_TICKET"); ?></a></div>
                               <div class="col-md-6"><a class="btn btn-block btn-primary" href="/ticket/index"><?php echo _s("ALL_TICKETS"); ?></a></div>
                           </div>
                           <?php else: ?>
                           <div class="grid-header">
                               <div class="col-md-6"><a class="btn btn-block btn-success" href="/ticket/index"><?php echo _s("VIEW_OPEN_TICK"); ?></a></div>
                               <div class="col-md-6"><a class="btn btn-block btn-primary" href="/ticket/closed"><?php echo _s("VIEW_CLOSED_TICK"); ?></a></div>
                           </div>
                           <?php endif; ?>
                            <div class="grid-body">
                                <div class="tickets" style="overflow-x: auto;"><?php if (isset($this->tickets) && count($this->tickets) > 0) { include ROOT_PATH."/views/ticket/list.php"; } else { echo _s("NO_TICKETS"); } ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- BEGIN MAIN CONTENT -->
            </section>
        </aside>
    </div>
    <!-- BEGIN SCROLL TO TOP -->
    <div class="scroll-to-top"></div>
    <!-- END SCROLL TO TOP -->
</div>
