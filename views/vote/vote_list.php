<script src="/templates/kertas/assets/jspages/vote.js"></script>
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
            <i class="fa fa-thumbs-o-up"></i>
            <span>Vote</span>
            <ol class="breadcrumb">
                <li><a href="/account/index">Account</a></li>
                <li class="active">Vote</li>
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
                                <i class="fa fa-thumbs-o-up"></i>
                                <span class="grid-title">Vote Reward (To Character: <?php echo $this->character; ?>)</span>
                            </div>
                            <div class="grid-body" style="display: block;">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Vote Site</th>
                                                <th>Last Vote</th>
                                                <th>Vote</th>
                                                <th>Check Vote</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($this->vote_sites as $site_id => $site_data): ?>
                                            <tr data-site-id="<?php echo $site_id; ?>" data-character="<?php echo $this->character; ?>">
                                                <td><?php echo $site_data['name']; ?></td>
                                                <td data-last-vote="<?php echo $site_data['last_vote']; ?>" data-vote-delay="<?php echo $site_data['vote_delay']; ?>" data-vote-rewarded="<?php echo $site_data['rewarded']; ?>"></td>
                                                <td><?php echo $site_data['vote_button']; ?></td>
                                                <td><button type="button" class="btn btn-checkvote btn-primary">Check Vote</button></td>
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