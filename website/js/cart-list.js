$(document).ready(function() {
    $.ajax({url: "api/cart-api.php?action=whole_cart", dataType: "json", success: function(result) {
        $(result["cartitems"]).each(function() {
                const element = 
                    `
                    <li class="list-group-item">
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <div class="row">
                                    <div class="col-3 col-lg-2 px-1 align-self-center">
                                        <img class="img-fluid rounded" src="${this["itemimg"]}" alt="" />
                                    </div>
                                    <div class="col-9 col-lg-10">
                                        <div class="row">
                                            <div class="col-12">
                                                <p><a href="item-details.php?id=${this["itemid"]}">${"brandshortname" in this ? `${this["brandshortname"]} ` : ""}${this["itemname"]}</a></p>
                                                <p class="mb-0">${formatItemPrice(this)}</p>
                                                <p class="mt-auto">${formatItemAvailability(this)}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-inline-item">
                                <form title="Quantità dell'articolo" action="api/cart-api.php">
                                    <label for="qty" class="form-label">Quantità</label>
                                    <input type="number" name="qty" class="form-control" min="0" max="${this["itemstock"]}" value="${this["itemid"]}" />
                                    <input type="submit" name="countsubmit" class="btn btn-primary" value="Modifica quantità" />
                                    
                                    <input type="hidden" name="action" value="set" />
                                    <input type="hidden" name="id" value="${this["itemid"]}" />
                                    <input type="hidden" name="redirect" value="" />
                                </form>
                            </li>
                        </ul>
                    </li>
                    `
                $("section > ul").append(element)
            }
        )}
    })
})
