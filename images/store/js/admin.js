/**
 * @project Bridge shoppingcart
 * JS event handlers for admin panel
 */

$(window).load(function() {
    // Alert before category delete
    $('.category-delete').click(function(e) {
        e.preventDefault();
        ans = confirm("Deleting this category will delete the products under it. Are you sure to continue.?");
        if (ans) {
            document.location.href = $(this).attr('rel');
        }
    });
    
    // group category in product list
    $('#group-cat').change(function(e) {
        var catid = $(this).val();
        document.location.href = $(this).attr('onchange') + catid;
    });
});

