$(document).ready(function() {
    const container = $("main div:first-of-type")

    $(container).html(getSpinnerElement("text-center", 3))
    $.ajax({url: `api/items-api.php${location.search}`, dataType: "json", type: "get", success: function(data) {
        if (data.length === 0) {
            $(`<p class="text-center">Nessun risultato.</p>`).insertBefore($(container))
        } else {
            $(`<p class="text-center">Numero di risultati: ${data.length}</p>`).insertBefore($(container))
        }
        
        $(container).html("")
        $(data).each(function() {
            const footer = document.createElement("footer")
            const footerDiv = document.createElement("div")
            footerDiv.className = "card-footer bg-transparent text-end p-2"

            const buyButton = addToCartButton("btn btn-primary w-100", `<img src="svg/cart-plus.svg" alt="Aggiungi al carrello" />`, this["itemid"])

            const card = itemToCard(this, footer, "row row-cols-md-1")

            const div = document.createElement("div")
            div.className = "col-12 col-md-4 col-lg-3 px-1 py-1"

            $(footerDiv).append(buyButton)
            $(footer).append(footerDiv)
            $(div).append(card)
            $(container).append(div)
        })
    }})
})
