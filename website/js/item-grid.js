function appendFilter(url, params, idField, nameField, filterLabelText, containerSelector) {
    $.ajax({url: url, dataType: "json", success: function(data) {
        const container = $(containerSelector)

        const select = document.createElement("select")
        select.className = "form-select"
        $(select).attr("name", idField)
        $(select).attr("id", idField)

        const label = document.createElement("label")
        label.innerHTML = filterLabelText
        $(label).attr("for", idField)

        $(select).append(
            `
            <option value="all" ${params.get(idField) == null ? "selected" : ""}>Tutto</option>
            `
        )
        $(data).each(function() {
            const selected = params.get(idField) == this[idField] ? "selected" : ""
            $(select).append(
                `
                <option value="${this[idField]}" ${selected}>${this[nameField]}</option>
                `
            )
        })

        $(container).append(label)
        $(container).append(select)
    }})
}

$(document).ready(function() {
    // Add all found items
    const container = $("main > section div:first-of-type")

    $(container).html(getSpinnerElement("text-center", 3))

    // Ignore filters with value "all"
    let query = ""
    location.search.substring(1).split("&").forEach(element => {
        if (!element.includes("all")) {
            query += element
        }
    });
    query = "?" + query;

    $.ajax({url: `api/items-api.php${query}`, dataType: "json", type: "get", success: function(data) {
        if (data.length === 0) {
            $(`<p class="text-center mt-3 border rounded bg-body p-2">Nessun risultato.</p>`).insertBefore($(container))
        } else {
            $(`<p class="text-center mt-3 border rounded bg-body p-2">Numero di risultati: ${data.length}</p>`).insertBefore($(container))
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

    const searchParams = (new URL(document.location)).searchParams

    appendFilter(
        url = `api/categories-api.php?type=all`, 
        params = searchParams, 
        idField = "categoryid", 
        nameField = "categoryname", 
        filterLabelText = "Categoria",
        containerSelector = "main > aside form > div:first-of-type > div:first-of-type"
    )
    appendFilter(
        url = `api/brands-api.php`, 
        params = searchParams, 
        idField = "brandid", 
        nameField = "brandname", 
        filterLabelText = "Gioco",
        containerSelector = "main > aside form > div:first-of-type > div:nth-of-type(2)"
    )
})
