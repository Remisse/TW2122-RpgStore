function populateAside(container, apiUrl, title) {
    const aside = document.createElement("aside")
    aside.className = "pb-3"
    $(aside).append(`<h4 class="text-center py-3">${title}</h4>`)
    $(aside).append(getSpinnerElement("text-center", 2))

    const ul = document.createElement("ul")
    ul.className = "list-group px-2"

    // Remove the spinner before appending all the items
    $(container).html("")
    $.ajax({url: apiUrl, dataType: "json", success: function(data) {
            // Remove the spinner
            $(aside).children("div").remove()

            if (data.length === 0) {
                $(aside).append(`<p class="text-center">Nessun risultato.</p>`)
            }

            $(data).each(function() {
                const game = "brandshortname" in this ? `${this["brandshortname"]} ` : ""
        
                const li = document.createElement("li")
                li.className = "list-group-item px-3"
                li.innerHTML =
                    `
                    <div class="row">
                        <div class="col-auto px-1 align-self-center">
                            <img class="img-fluid rounded" src="${this["itemimg"]}" alt="" />
                        </div>
                        <div class="col-6 flex-grow-1">
                            <p class="fw-bold"><a href="itemdetails.php?id=${this["itemid"]}">${game}${this["itemname"]}</a></p>
                            <p class="mb-0">${formatItemPrice(this)}</p>
                            <p class="mt-auto">${formatItemAvailability(this)}</p>
                        </div>
                    </div>
                    `
                $(ul).append(li)
            })
            $(aside).append(ul)
            $(container).append(aside)
        
            $(container).attr("class", "col-12 col-md-4")
        }
    })
}

$(document).ready(function() {
    const asideContainer = $("body > div > div > div:nth-of-type(2)")
    // Populate the sidebar with the latest items
    populateAside(asideContainer, "api/aside-items-api.php?type=aside_latest", "Novità")

    // Populate the sidebar with random discounted items
    populateAside(asideContainer, "api/aside-items-api.php?type=aside_sale", "Articoli in sconto")
})
