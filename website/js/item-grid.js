$(document).ready(function() {
    $.ajax({url: `api/items-api.php${location.search}`, dataType: "json", type: "get", success: function(data) {
        const container = $("main section:first-of-type")

        $(data).each(function() {
            const footer = document.createElement("footer")
            const footerDiv = document.createElement("div")
            footerDiv.className = "card-footer bg-transparent text-end"

            const buyButton = addToCartButton(this)

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
