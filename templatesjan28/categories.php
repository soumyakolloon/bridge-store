<?php
/**
 * @project Bridge shoppingcart
 * Admin category page template
 */
$per_page     = 4;
$page_deatils = $paginator->setPagination($per_page, $categories);

$page        = $page_deatils['page'];
$show_page   = $page_deatils['showPage'];
$total_pages = $page_deatils['totalPages'];
$start       = $page_deatils['start'];
$end         = $page_deatils['end'];

$total_results = count($categories);
?>
<div class="row">
    <div class="col-lg-12">
        <h1>Category <small></small></h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Manage category</li>
        </ol>
        <div class="alert alert-success alert-dismissable">
            <a href="index.php?page=new-category" class="btn btn-primary" id="category-new">New category +</a>
        </div>

        <div>
            <ul class="pager">
                <?php
                $reload        = $_SERVER['PHP_SELF'] . "?page=categories&amp;tpages=" . $total_pages;
                if ($total_pages > 1)
                {
                    echo $paginator->paginate($reload, $show_page, $total_pages);
                }
                ?>
            </ul>
        </div>

        <?php
        if ($categories)
        {
            ?>
            <div class="table-responsive" id="category-list">
                <table class="table table-bordered table-hover table-striped tablesorter">
                    <thead>
                        <tr>
                            <th>Name <i class="fa fa-sort"></i></th>
                            <th>Status <i class="fa fa-sort"></i></th>
                            <th>Edit <i class="fa"></i></th>
                            <th>Remove <i class="fa"></i></th>
                            <th></th>
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
                            $row = $categories[$i];
                            ?>
                            <tr>
                                <td><?php echo $row['name'] ?></td>
                                <td><?php echo ($row['status'] == 1) ? 'Enabled' : 'Disabled'; ?></td>
                                <td><a class="btn btn-success"  id="category-edit" href="index.php?page=edit-category&id=<?php echo $row['id']; ?>">Edit</a></td>
                                <td><a class="btn btn-danger category-delete" id="" rel="index.php?page=delete-category&id=<?php echo $row['id']; ?>" href="#">Delete</a></td>
                                <td><a class="btn btn-info" id="category-products" href="index.php?page=products&cat_id=<?php echo $row['id']; ?>">View Products</a></td>
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