<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- BEGIN SIDEBAR -->
    <?php include ROOT_PATH."/views/sidebar.php"; ?>
    <!-- END SIDEBAR -->
    <!-- BEGIN MODALS -->
    <?php include ROOT_PATH."/views/modals.php"; ?>
    <!-- END MODALS -->
    <div id="global-content">
        <script type="text/javascript" src="/templates/kertas//ckeditor/ckeditor.js"></script>
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
                               <div class="col-md-6"><a class="btn btn-block btn-success" href="/ticket/close/<?php echo $this->ticket['id']; ?>"><?php echo _s("CLOSE_TICK"); ?></a></div>
                               <div class="col-md-6"><a class="btn btn-block btn-primary" href="/ticket/index"><?php echo _s("ALL_TICK_GLOBAL"); ?></a></div>
                           </div>
                           <?php endif; ?>
                           <div class="grid-header">
                                <div class="grid-title" style="color: black; text-align: center;"><?php echo $this->ticket['title']; ?></div>
                            </div>
                            <div class="grid-body">
                                <div class="general-info">
                                    <div class="general-info-head"></div>
                                    <div class="general-info-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="general-info-el">
                                                    <div class="general-info_left"><?php echo _s("ID"); ?>:</div>
                                                    <div class="general-info_right"><?php echo $this->ticket['id']; ?></div>
                                                </div>
                                                <div class="general-info-el">
                                                    <div class="general-info_left"><?php echo _s("STATUS"); ?>:</div>
                                                    <div class="general-info_right"><?php echo $this->ticket['status']; ?></div>
                                                </div>

                                                <div class="general-info-el">
                                                    <div class="general-info_left"><?php echo _s("OPENNED_AT"); ?></div>
                                                    <div class="general-info_right"><?php echo $this->ticket['create_date']; ?></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="general-info-el">
                                                    <div class="general-info_left"><?php echo _s("SERVER"); ?>:</div>
                                                    <div class="general-info_right"><?php echo $this->ticket['server']; ?></div>
                                                </div>
                                                <div class="general-info-el">
                                                    <div class="general-info_left"><?php echo _s("ACCOUNT2"); ?>	</div>
                                                    <div class="general-info_right"><?php echo $this->ticket['account']; ?></div>
                                                </div>
                                                <div class="general-info-el">
                                                    <div class="general-info_left"><?php echo _s("CHA_NAME"); ?>:</div>
                                                    <div class="general-info_right"><?php echo $this->ticket['character']; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php foreach ($this->ticket['comments'] as $cidx => $comment): ?>
                                <div class="messege<?php echo ($cidx==0) ? " first-messege" : ""; ?> mid<?php echo $comment['id']; ?>">
                                    <div class="messege-head"><font color="green" style="font-weight: bold;"><?php echo $comment['commenter']; ?></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo formatdate($comment['post_date']); ?></b></div>
                                    <div class="mesegge-body"><?php echo $comment['content']; ?></div>
                                    <div class="mesegge-button">
                                        <button name="quote-mess" class="btn btn-warning btn-quote-mess" onclick="quoteComment(<?php echo $comment['id']; ?>);"><?php echo _s("QUOTE"); ?></button>
                                    </div>
                                </div>
                                <div class="messeges"><br class="bmid<?php echo $comment['id']; ?>"/></div>
                                <?php endforeach; ?>

                                <div class="btn-add-massage-tickets-row">
                                    <button type="submit" class="btn btn-success btn-add-massage-tickets" onclick="CommentSubmit(this);" ><?php echo _s("SEND"); ?></button>
                                </div>
                                <form action="" id="addCommentForm" method="POST" enctype="multipart/form-data">
                                    <textarea name="comment_contents" id="commentContents" class="massage-textarea"></textarea>
                                </form>

                                <script type="application/javascript">
                                    CKEDITOR.replace('commentContents',{
                                        customConfig: '/templates/kertas/ckeditor/custom_config.js'
                                    });

                                    function CommentSubmit(e) {
                                        $(e).buttonLoader('start');
                                        $('#addCommentForm').submit();
                                    }
                                </script>
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
