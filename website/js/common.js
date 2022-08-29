//
// Item formatting
//
function formatItemPrice(item) {
    return item["itemprice"] === item["pricediscount"]
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

function addToCartButton(buttonClass, buttonContents, itemId) {
    const addButton = document.createElement("button")
    addButton.className = buttonClass
    addButton.setAttribute("type", "button")
    $(addButton).append(buttonContents)

    $(addButton).on("click", function() {
        $.ajax({url: `api/cart-api.php?action=add&id=${itemId}`, dataType: "json", success: function(result) {
            $(addButton).html(result["msg"])
            updateCartInNav(result["countAll"])
        }})
    })

    return addButton;
}

function itemToCard(item, footer, rowClass) {
    const article = document.createElement("article")
    article.className = "card h-100"
    $(article).append(
        `
        <div class="card-body p-2">
            <div class="${rowClass}">
                <div class="col-auto pb-1 text-center container-fluid ">
                    <img src="${item["itemimg"]}" class="img-fluid rounded" alt="" />
                </div>
                <div class="col-6 container-fluid flex-column flex-fill flex-grow-1 align-items-start">
                    <h6 class="card-title"><a href="itemdetails.php?id=${item["itemid"]}">${item["brandshortname"]} ${item["itemname"]}</a></h6>
                    <p class="card-text">${formatItemPrice(item)}</p>
                    <p class="card-text mt-auto">${formatItemAvailability(item)}</p>
                </div>
            </div>
        </div>
        `
    )
    $(article).append(footer)

    return article;
}

//
// Misc
//
function getSpinnerElement(htmlClass, size) {
    let sizeClass = "";
    switch (size) {
        case 1:
            sizeClass = "spinner-border-sm";
            break;
        case 3:
            sizeClass = "spinner-border-lg";
            break;
    }

    return `
    <div class="${htmlClass}">
        <div class="spinner-border ${sizeClass}" role="status">
            <span class="visually-hidden">Caricamento...</span>
        </div>
    </div>`
}

//
// Cart
//
function updateCartInNav(count) {
    $("nav > a:first-of-type").children("span")
        .html(count === 0 ? "" : ` (${count})`)
}

//
// nav
//
async function appendCategories(type, container, ulClass, liClass, linkClass) {
    $.ajax({url: `api/categories-api.php?type=${type}`, dataType: "json", success: function(data) {
        const ul = document.createElement("ul")
        ul.className = ulClass

        $(data).each(function() {
            const li = document.createElement("li")
            li.className = liClass
            li.innerHTML = `<a class="${linkClass}" href="items.php?categoryid=${this["categoryid"]}">${this["categoryname"]}</a>`

            $(ul).append(li)
        })
        $(container).append(ul)
    }
})
}

async function appendBrands(container, ulClass, liClass, linkClass) {
    $.ajax({url: "api/brands-api.php", dataType: "json", success: function(data) {
            const ul = document.createElement("ul")
            ul.className = ulClass

            $(data).each(function() {
                const li = document.createElement("li")
                li.className = liClass
                li.innerHTML = `<a class="${linkClass}" href="items.php?brandid=${this["brandid"]}">${this["brandname"]}</a>`

                $(ul).append(li)
            })
            $(container).append(ul)
        }
    })
}

$(document).ready(function() {
    // Populate dropdown menus inside the nav
    appendBrands(
        "header nav:first-of-type > div > div > ul > li:nth-of-type(1)", 
        "dropdown-menu dropdown-menu-dark",
        "dropdown-item",
        ""
    )
    appendCategories(
        "all",
        "header nav:first-of-type > div > div > ul > li:nth-of-type(2)", 
        "dropdown-menu dropdown-menu-dark",
        "dropdown-item",
        ""
    )

    // Show all unread notifications when clicking on the appointed nav button
    const dropdown = $("nav > div > ul");
    const button = $("nav > div > a");

    $(button).on("click", function() {
        $(dropdown).html(getSpinnerElement("text-center", 1));

        $.ajax({url: "api/notifications-api.php", dataType: "json", success: function(data) {
            console.log(data.length)
            if (data.length === 0) {
                $(dropdown).html(`<li class="dropdown-item">Nessuna notifica.</li>`);
            } else {
                $(dropdown).html("");

                $(data).each(function() {
                    $(dropdown).append(
                        `<li class="dropdown-item">
                            ${this["message"]} (#${this["order"]})
                        </li>`
                    );
                });

                $(button).children("span")
                    .html("")
            }
        }});
    });
})
