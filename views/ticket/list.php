
                                    <table class="table table-hover table-tickets table-l2eu-center" style="vert-align: middle !important;">
                                        <thead>
                                            <tr>
                                                <th><img src="/media/TicketsFoldersImages/tick_gray.png" alt=""></th>
                                                <th><?php echo _s("ID"); ?></th>
                                                <th><?php echo _s("THREAD"); ?></th>
                                                <th><?php echo _s("STATUS"); ?></th>
                                                <th><?php echo _s("SERVER"); ?></th>
                                                <th><?php echo _s("ACCOUNT"); ?></th>
                                                <th><?php echo _s("DATE_CREATED"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($this->tickets as $ticket): ?>
                                            <tr>
                                                <td><img width="20px" height="20px" style="max-width: none;" src="/media/TicketsFoldersImages/tick_black.png"/></td>
                                                <td nowrap><?php echo $ticket['id']; ?></td>
                                                <td style="min-width: 150px;"><a href="/ticket/view/<?php echo $ticket['id']; ?>"><?php echo $ticket['title']; ?></a></td>
                                                <td><?php echo $ticket['status']; ?></td>
                                                <td><?php echo $ticket['server']; ?></td>
                                                <td><?php echo $ticket['account']; ?></td>
                                                <td><?php echo $ticket['create_date']; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
