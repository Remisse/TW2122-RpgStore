$(document).ready(function() {
    $.ajax({url: "api/cart-api.php?action=whole_cart", dataType: "json", success: function(result) {
            const container = $("main section > ul")

            if (result["cartitems"].length === 0) {
                container.append(`<p class="text-center">Il tuo carrello è vuoto.</p>`)
            } else {
                $(result["cartitems"]).each(function() {
                    const footer = 
                    `
                    <footer class="container-fluid card-footer p-2">
                        <form title="Quantità dell'articolo" action="api/cart-api.php" class="d-flex flex-row flex-lg-column justify-content-around">
                            <ul class="list-inline d-flex align-items-center justify-content-end">
                                <li class="list-inline-item d-flex align-items-center">
                                    <label for="qty" class="me-1">Quantità</label><input type="number" name="qty" id="qty${this["itemid"]}" class="form-control" min="0" max="${this["itemstock"]}" value="${this["cartqty"]}" />
                                </li>
                                <li class="list-inline-item">
                                    <input type="submit" name="submit" id="submitqty${this["itemid"]}" class="btn btn-outline-primary mt-1 p-1" value="Modifica" />
                                </li>
                            </ul>
                            <input type="hidden" name="action" value="set" />
                            <input type="hidden" name="id" value="${this["itemid"]}" />
                            <input type="hidden" name="redirect" />
                        </form>
                    </footer>
                    `
                    const card = itemToCard(this, footer, "row");

                    const li = document.createElement("li")
                    li.className = "list-group-item py-1 px-2"

                    $(li).append(card)
                    $(container).append(li)
                })
            }
        }
    })
})
