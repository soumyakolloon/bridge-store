<?php
/**
 * @project Bridge shoppingcart
 * Admin product page template
 */
$per_page     = 4;
$page_deatils = $paginator->setPagination($per_page, $allusers);
$page         = $page_deatils['page'];
$show_page    = $page_deatils['showPage'];
$total_pages  = $page_deatils['totalPages'];
$start        = $page_deatils['start'];
$end          = $page_deatils['end'];

$total_results = count($allusers);
?>
<div class="row">
    <div class="col-lg-12">
        <h1>Customers <small></small></h1>
        
        <div>
            <ul class="pager">
                <?php
                $reload        = $_SERVER['PHP_SELF'] . "?page=customers&amp;tpages=" . $total_pages;
                if ($total_pages > 1)
                {
                    echo $paginator->paginate($reload, $show_page, $total_pages);
                }
                ?>
            </ul>
        </div>

        <?php
        if ($allusers)
        {
            ?>
            <div class="table-responsive" id="product-list">
                <table class="table table-bordered table-hover table-striped tablesorter">
                    <thead>
                        <tr>
                            <th>User Name <i class="fa fa-sort"></i></th>
                            <th>First Name <i class="fa fa-sort"></i></th>
                            <th>Last Name <i class="fa fa-sort"></i></th>
                            <th>Email <i class="fa fa-sort"></i></th>
                            <th>Status <i class="fa fa-sort"></i></th>
                            <th>Edit <i class="fa"></i></th>
                            <th>Remove <i class="fa"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = $start; $i < $end; $i++)
                        {

                            if ($i == $total_results)
                            {
                                break;
                            }
                            $row = $allusers[$i];
                            ?>
                            <tr>
                                <td><?php echo $row['firstname']." ".$row['lastname']; ?></td>
                                <td><?php echo $row['firstname'] ?></td>
                                <td><?php echo $row['lastname'] ?></td>
                                <td><?php echo $row['email'] ?></td>
                                <td><?php echo ($row['status'] == 1) ? 'Active' : 'Inactive'; ?></td>
                                <td><input type="button" name="view_user" class="btn btn-success" value="Edit" onclick="javascript:displayDetails('<?php echo $row['id']; ?>')"></td>
                                <td><a class="btn btn-danger" id="product-delete" href="index.php?page=customer_delete&id=<?php echo $row['id']; ?>">Delete</a></td>
                            </tr>
                            <tr class="prod_details" id="<?php echo $row['id']; ?>" style="display: none;">
                                <td colspan="7">
                                    <div>
                                       
                                        <form method="post" action="post-handler.php?action=EDIT_USER&page=<?php echo $_GET['pageNo']; ?>">
                                        <table class="table table-bordered table-hover table-striped tablesorter">                                            
                                        <tr>
                                            <td>
                                                <?php echo $row['username'] ?>
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            </td>
                                            <td><?php echo $row['firstname'] ?></td>
                                            <td><?php echo $row['lastname'] ?></td>
                                            <td><?php echo $row['email'] ?></td>
                                            <td>
                                                <select name="status">
                                                    <option value="1" <?php echo ($row['status'] == 1) ? 'selected=selected' : ''; ?>>Active</option>
                                                    <option value="0" <?php echo ($row['status'] == 0) ? 'selected=selected' : ''; ?>>Inactive</option>
                                                </select>
                                            </td>
                                            <td><button  type="submit" name="submit" class="btn btn-primary">Save</button></td>
                                        </tr>
                                        </table>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<!-- Page Specific Plugins -->
<script src="js/tablesorter/jquery.tablesorter.js"></script>
<script src="js/tablesorter/tables.js"></script>
<script>
    function displayDetails(id){
        
        if($('#'+id).css('display') == 'none'){
            $('#'+id).css({'display':'table-row'});
        }
        else{
            $('#'+id).css({'display':'none'});
        }
    }
</script>