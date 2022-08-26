//
// Item formatting
//
function formatItemPrice(item) {
    return !("pricediscount" in item)
        ? `${item["itemprice"]}`
        : `<s>${item["itemprice"]}</s> <strong>${item["pricediscount"]}</strong> (-${item["itemdiscount"] * 100}%)`
}

function formatItemAvailability(item) {
    const stock = item["itemstock"]
    return stock === 0
            ? "Non disponibile"
        : stock === 1
            ? "<s>Solo 1 disponibile!</s>"
        : stock < 10
            ? `${stock} disponibili`
        : "Disponibile"
}

//
// nav and aside
//
async function appendMainCategories(container, ulClass, liClass) {
    $.ajax({url: "api/maincategories-api.php", dataType: "json", success: function(data) {
            const ul = document.createElement("ul")
            ul.className = ulClass

            $(data).each(function() {
                const li = document.createElement("li")
                li.className = liClass
                li.innerHTML = `<a href="items.php?categoryid=${this["categoryid"]}">${this["categoryname"]}</a>`

                $(ul).append(li)
            })
            $(container).append(ul)
        }
    })
}

// TODO Extract a base function using elements in common with appendMainCategories().
async function appendBrands(container, ulClass, liClass) {
    $.ajax({url: "api/brands-api.php", dataType: "json", success: function(data) {
            const ul = document.createElement("ul")
            ul.className = ulClass

            $(data).each(function() {
                const li = document.createElement("li")
                li.className = liClass
                li.innerHTML = `<a href="items.php?brandid=${this["brandid"]}">${this["brandname"]}</a>`

                $(ul).append(li)
            })
            $(container).append(ul)
        }
    })
}

function populateAside(aside, items, title) {
    const section = document.createElement("section")
    section.className = "pb-3"
    section.innerHTML = `<h4 class="text-center py-3">${title}</h4>`

    const ul = document.createElement("ul")
    ul.className = "list-group px-2"

    $(items).each(function() {
        const game = "brandshortname" in this ? `${this["brandshortname"]} ` : ""

        const li = document.createElement("li")
        li.className = "list-group-item px-3"
        li.innerHTML =
            `
            <div class="row">
                <div class="col-3 col-lg-2 px-1 align-self-center">
                    <img class="img-fluid rounded" src="${this["itemimg"]}" alt="" />
                </div>
                <div class="col-9 col-lg-10">
                    <div class="row">
                        <div class="col-12">
                            <p><a href="item-details.php?id=${this["itemid"]}">${game}${this["itemname"]}</a></p>
                            <p class="mb-0">${formatItemPrice(this)}</p>
                            <p class="mt-auto">${formatItemAvailability(this)}</p>
                        </div>
                    </div>
                </div>
            </div>
            `
        $(ul).append(li)
    })
    $(section).append(ul)
    $(aside).append(section)
}

function updateCartInNav(count) {
    $("nav > a:first-of-type").contents().filter(function() {
        return this.nodeType == Node.TEXT_NODE
    })[1].textContent = count === 0 ? "" : ` (${count})`
}

$(document).ready(function() {
    // nav
    appendBrands(
        "nav:first-of-type ul > li:nth-of-type(1)", 
        "dropdown-menu dropdown-menu-dark",
        "dropdown-item"
    )
    appendMainCategories(
        "nav:first-of-type ul > li:nth-of-type(2)", 
        "dropdown-menu dropdown-menu-dark",
        "dropdown-item"
    )

    // aside
    // Populate the sidebar with the latest items
    $.ajax({url: "api/aside-items-api.php?type=aside_latest", dataType: "json", success: function(data) {
            populateAside($("aside:first-of-type"), data, "Novità");
        }
    })

    // Populate the sidebar with random discounted items
    $.ajax({url: "api/aside-items-api.php?type=aside_sale", dataType: "json", success: function(data) {
            populateAside($("aside:nth-of-type(2)"), data, "Articoli in sconto");
        }
    })
})
