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
            <section class="content tickets-cont createTicket">
                <div class="row">
                    <div class="col-md-12">
                        <div class="grid">
                            <div class="grid-header">
                                <div class="grid-title">
                                    <div class="col-md-6"><a class="btn btn-block btn-success" href="/ticket/create"><?php echo _s("NEW_TICKET"); ?></a></div>
                                    <div class="col-md-6"><a class="btn btn-block btn-primary" href="/ticket/index" ><?php echo _s("ALL_TICKETS"); ?></a></div>
                                </div>
                            </div>
                            <div class="grid-body">
                                <div class="row">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="title"><?php echo _s("THREAD"); ?></label>
                                                <input type="text" name="ticket_title" id="title" value=""/>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="server_id"><?php echo _s("SERVER"); ?></label>
                                                <select name="server_id" id="server_id" onchange="refreshChars();">
                                                <?php foreach ($this->servers as $server_id => $server): ?>
                                                    <option style="color: black;" value="<?php echo $server_id; ?>" <?php $server_id == $this->ticket_server_id ? 'selected' : ''; ?>><?php echo $server['name']; ?></option>
                                                <?php endforeach; ?>
                                                </select>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label" for=""><?php echo _s("ACCOUNT"); ?></label>
                                                <?php include ROOT_PATH."/views/ticket/ajax/account_select.php"; ?>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for=""><?php echo _s("CHA_NAME"); ?></label>
                                                <?php include ROOT_PATH."/views/ticket/ajax/character_select.php"; ?>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h5 class="create-tickets-help">
                                                <?php echo _s("SYSTEM_TICK_MSG"); ?>
                                            </h5>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
        										<div class="btn-add-massage-tickets-row">
        											<input type="submit" class="btn btn-success btn-add-massage-tickets" value="<?php echo _s("NEW_TICKET"); ?>">
        										</div>
                                                <textarea name="ticket_content" id="content_text"></textarea>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN MAIN CONTENT -->
            </section>
        </aside>
        <script type="text/javascript" src="/templates/kertas//ckeditor/ckeditor.js"></script>
        <script type="application/javascript">

        	CKEDITOR.replace('content_text',{
        		customConfig: '/templates/kertas/ckeditor/custom_config.js'
        	});


            function refreshChars()
            {
                $.ajax(
                    {
                        type: "POST",
                        url: "/ticket/ajaxGetAccChar",
                        data: {server_id: $(server_id).val(), login: $('.acc-selectlist').val()},
                        success: function(msg){
                            var desc = JSON.parse(msg);
                            $(".acc-selectlist").html(desc.acc);
                            $(".char-selectlist").html(desc.chars);
                        }

                    }
                );
            }
        </script>
    </div>
    <!-- BEGIN SCROLL TO TOP -->
    <div class="scroll-to-top"></div>
    <!-- END SCROLL TO TOP -->
</div>
