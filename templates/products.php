<?php
/**
 * @project Bridge shoppingcart
 * Admin product page template
 */
$per_page     = 4;
$page_deatils = $paginator->setPagination($per_page, $products);
$page         = $page_deatils['page'];
$show_page    = $page_deatils['showPage'];
$total_pages  = $page_deatils['totalPages'];
$start        = $page_deatils['start'];
$end          = $page_deatils['end'];

$total_results = count($products);
?>
<div class="row">
    <div class="col-lg-12">
        <h1>Products <small></small></h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Manage products</li>
        </ol>
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 1){ ?>
        <div class="has-success">            
            <span class="control-label input-group-addon">Product Saved Successfully</span><br>
        </div>
        <?php } ?>
        <div class="alert alert-success alert-dismissable">
            <span>
                <!-- <a href="index.php?page=new-product" class="btn btn-primary" id="product-new">New product +</a> -->
                <a onClick="addCatgeory();" class="btn btn-primary" id="product-new">New product +</a>
            </span>
            <span class="category-span">
                <select class="form-control" name="product-cat"  id="group-cat" onchange="<?php echo $_SERVER['PHP_SELF']. '?page=products&cat_id='; ?>">
                <!-- <select class="form-control" name="product-cat"  id="group-cat" onchange="changevalue();">  -->
                    <option value="0">Group By Category</option>
                    <?php
                    foreach ($categories as $cat)
                    {
                        
                        
                     ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo (isset($_GET['cat_id']) && $_GET['cat_id'] == $cat['id']) ? 'selected="selected"' : ''; ?> >
                        <?php echo $cat['name']; ?>
                    </option>
                     
                        
                      
                   <?php  }
                    ?>
                    
                     
                    
                </select>
               
            </span>
        </div>

        <div>
            <ul class="pager">
                <?php
                $reload        = $_SERVER['PHP_SELF'] . "?page=products&amp;tpages=" . $total_pages;
                $reload       .= (isset($_GET['cat_id'])) ? "&cat_id=" . $_GET['cat_id'] : '';
                if ($total_pages > 1)
                {
                    echo $paginator->paginate($reload, $show_page, $total_pages);
                }
                ?>
            </ul>
        </div>

        <?php
        if ($products)
        {
            ?>
            <div class="table-responsive" id="product-list">
                <table class="table table-bordered table-hover table-striped tablesorter">
                    <thead>
                        <tr>
                            <th>Name <i class="fa fa-sort"></i></th>
                            <th>Description <i class="fa fa-sort"></i></th>
                            <th>Price <i class="fa fa-sort"></i></th>
                            <th>Validity <i class="fa fa-sort"></i></th>
                            <th>Image <i class="fa fa-sort"></i></th>
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
                            $row = $products[$i];
                            ?>
                            <tr>
                                <td> <a href="./index.php?page=product-detail&product_id=<?php echo $row['id'];?>"><?php echo $row['name'] ?></a></td>
                                <td><?php echo $row['description'] ?></td>
                                <td><?php echo $row['price'] ?></td>
                                <td><?php echo $row['validity'] ?></td>
                                <td><a href="./index.php?page=product-detail&product_id=<?php echo $row['id']; ?>"><img src="uploads/<?php echo $row['image_path']; ?>" width="100" height="100"></a></td>
                                <td><?php echo ($row['status'] == 1) ? 'Enabled' : 'Disabled'; ?></td>
                                <td><a class="btn btn-success"  id="product-edit" href="index.php?page=edit-product&id=<?php echo $row['id']; ?>&cat_id=<?php echo $_GET['cat_id']; ?>">Edit</a></td>
                                <td><a class="btn btn-danger" id="product-delete" href="index.php?page=delete-product&id=<?php echo $row['id']; ?>&cat_id=<?php echo $_GET['cat_id']; ?>">Delete</a></td>
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

function addCatgeory()
{
    var cat_id = $('#group-cat option:selected').val();

    window.location.href="index.php?page=new-product&cat_id="+cat_id;


}

</script>
