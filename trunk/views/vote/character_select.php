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
                <div class="col-md-2">
                <div class="grid">
                    <div class="grid-body">
                        <table class="table">
                            <tr><td align=center>Select character to receive rewards:</td></tr>
                            <tr><td><select id="vote_character" class="form-control" >
                                <?php foreach ($this->game_accounts as $account => $characters): ?>
                                <?php if (count($characters) > 0): ?>
                                <optgroup label="<?php echo $account; ?>">
                                <?php foreach ($characters as $char): ?>
                                    <option value="<?php echo $char['char_name']; ?>"><?php echo $char['char_name']; ?></option>
                                <?php endforeach; ?>
                                </optgroup>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </select></td></tr>
                            <tr><td align=center><button type="button" class="btn btn-primary" id="btn_select_char" style="width:100%">Select</button></td></tr>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </section>
    </div>
    <!-- BEGIN SCROLL TO TOP -->
    <div class="scroll-to-top"></div>
    <!-- END SCROLL TO TOP -->
</div>