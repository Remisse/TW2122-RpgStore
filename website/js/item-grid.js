$(document).ready(function() {
    $.ajax({url: `api/items-api.php${location.search}`, dataType: "json", type: "get", success: function(data) {
        const container = $("main section:first-of-type")

        $(data).each(function() {
            const footer = document.createElement("footer")
            footer.className = "card-footer bg-transparent text-end"

            const buyButton = document.createElement("button")
            buyButton.className = "btn btn-primary"
            buyButton.setAttribute("type", "button")
            $(buyButton).append(`<img src="svg/cart-plus.svg" alt="Aggiungi al carrello" />`)
            $(buyButton).prop("disabled", this["itemstock"] === 0)

            const itemId = this["itemid"]
            $(buyButton).on("click", function() {
                $.ajax({url: `api/cart-api.php?action=add&id=${itemId}`, dataType: "json", success: function(result) {
                    $(buyButton).html(result["msg"])
                    updateCartInNav(result["countAll"])
                }})
            })
            $(footer).append(buyButton)

            const article = document.createElement("article")
            article.className = "card h-100"
            $(article).append(
                `
                <section class="d-flex card-body">
                    <div class="row">
                        <div class="col-4 col-md-12">
                            <img src="${this["itemimg"]}" class="img-fluid rounded" alt="" />
                        </div>
                        <div class="d-flex justify-content-between col-8 col-md-12">
                            <div class="d-flex flex-column px-0">
                                <h6 class="card-title">${this["brandshortname"]} ${this["itemname"]}</h6>
                                <p class="card-text">${formatItemPrice(this)}</p>
                                <p class="card-text mt-auto">${formatItemAvailability(this)}</p>
                            </div>
                        </div>
                    </div>
                </section>
                `
            )
            $(article).append(footer)

            const div = document.createElement("div")
            div.className = "col-12 col-md-4 col-lg-3 px-2 py-2"
            
            $(div).append(article)
            $(container).append(div)
        })
    }})
})
