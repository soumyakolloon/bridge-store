<?php
/**
 * @project Bridge shoppingcart
 * Add new category
 */
?>

<?php
if (isset($category_info))
{
    $category_info = $category_info[0];
}
?>
<div class="row">
    <div class="col-lg-12">
        <h1>Category <small></small></h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Manage category</li>
        </ol>

        <div class="table-responsive" id="category-list">
            <div class="col-lg-6">
                <form role="form" id="new-category-form" action="post-handler.php?action=CATEGORY" method="POST">

                    <div class="form-group">
                        <label>Category Name</label>
                        <?php
                        if (isset($category_info))
                        {
                            ?>
                            <input type="hidden" name="category-id" value="<?php echo $category_info['id']; ?>" />
                            <input class="form-control" id="category-name" name="category-name" value="<?php echo $category_info['name']; ?>">
                            <?php
                        }
                        else
                        {
                            ?>
                            <input class="form-control" id="category-name" name="category-name" placeholder="Enter category name" />
                            <?php
                        }
                        ?>
                        <p class="help-block"></p>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <?php
                        if (isset($category_info))
                        {
                            if ($category_info['status'] == 1)
                            {
                                ?>
                                <label class="radio-inline">
                                    <input name="cat-status" id="cat-status-enabled" value="1" type="radio" checked=""> Enabled
                                </label>
                                <label class="radio-inline">
                                    <input name="cat-status" id="cat-status-disabled" value="0" type="radio"> Disabled
                                </label>
                                <?php
                            }
                            else
                            {
                                ?>
                                <label class="radio-inline">
                                    <input name="cat-status" id="cat-status-enabled" value="1" type="radio" > Enabled
                                </label>
                                <label class="radio-inline">
                                    <input name="cat-status" id="cat-status-disabled" value="0" type="radio" checked=""> Disabled
                                </label>
                                <?php
                            }
                        }
                        else
                        {
                            ?>
                            <label class="radio-inline">
                                <input name="cat-status" id="cat-status-enabled" value="1" type="radio" checked=""> Enabled
                            </label>
                            <label class="radio-inline">
                                <input name="cat-status" id="cat-status-disabled" value="0" type="radio"> Disabled
                            </label>
                            <?php
                        }
                        ?>
                    </div>

                    <button type="submit" class="btn btn-primary" id="new-category-submit">Submit</button>
                    <button type="reset" class="btn btn-default" id="new-category-reset">Reset</button>

                </form>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('#new-category-form').submit(function() {
            catName = $('#category-name').val();
            if (catName == '') {
                $('#category-name').attr('placeholder', 'Category name is required');
            } else {
                $('#new-category-form').submit();
            }
        });
    });
</script>

