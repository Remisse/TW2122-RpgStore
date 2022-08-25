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

                ul.append(li)
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

                ul.append(li)
            })
            $(container).append(ul)
        }
    })
}

function itemToAsideHTML(item) {
    const game = "brandshortname" in item ? `${item["brandshortname"]} ` : ""
    const price = formatItemPrice(item)

    const outVal = 
        `
        <div class="row">
            <div class="col-3 col-lg-2">
                <img src="${item["itemimg"]}" alt="" />
            </div>
            <div class="col-9 col-lg-10">
                <div class="row">
                    <div class="col-12">
                        <p><a href="item-details.php?id=${item["itemid"]}">${game}${item["itemname"]}</a></p>
                        <p class="mb-0">${price}</p>
                    </div>
                </div>
            </div>
        </div>
        `
    return outVal;
}

function populateAside(aside, items) {
    const ul = document.createElement("ul")
    ul.className = "list-group mx-auto"

    $(items).each(function() {
        const li = document.createElement("li")
        li.className = "list-group-item px-3"
        li.innerHTML = itemToAsideHTML(this)
        ul.append(li)
    })
    aside.append(ul)
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
            populateAside($("aside:first-of-type > section"), data);
        }
    })

    // Populate the sidebar with random discounted items
    $.ajax({url: "api/aside-items-api.php?type=aside_sale", dataType: "json", success: function(data) {
            populateAside($("aside:nth-of-type(2) > section"), data);
        }
    })
})
