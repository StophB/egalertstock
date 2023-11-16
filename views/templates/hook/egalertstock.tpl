
{if $product}
        <button type="button" id="alert-me-btn" class="btn btn-success add-to-cart" data-toggle="modal" data-target="#alert-me-modal">
        {if $customBtn}
            {$customBtn}
        {else}
        M'avertir
        {/if}
        </button>



    <div class="modal fade" id="alert-me-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="alert-me-form" method="POST">
                <input type="text" value="{$product['id_product']}" name="productId" hidden>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Alert Stock</h4>
                    </div>
                    <div class="modal-body">
                    <div class="form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="example@gmail.com">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-default" data-dismiss="modal">
                            Close
                        </a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
{/if}