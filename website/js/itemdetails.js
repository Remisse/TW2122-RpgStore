$(document).ready(function() {
    $.ajax({url: `api/items-api.php${location.search}`, dataType: "json", success: function(item) {
            $(item).each(function() {
                const article = $("main article")

                const gameShort = "brandshortname" in this ? `${this["brandshortname"]} ` : ""

                const footer = document.createElement("footer")
                footer.className = "text-center"

                const button = addToCartButton("btn btn-primary w-100", "Aggiungi al carrello", this["itemid"])

                const content = 
                `
                <header class="p-1">
                    <div class="row">
                        <div class="col-12 col-md-4 order-md-2 text-center">
                            <img src="${this["itemimg"]}" class="img-fluid rounded" alt="Immagine del prodotto" />
                        </div>
                        <div class="col-12 col-md-8 order-md-1 p-2">
                            <h3>${gameShort}${this["itemname"]}</h3>
                            ${"brandname" in this ? `<p class="lead">${this["categoryname"]} per ${this["brandname"]}</p>` : ""}
                            <p class="lead">${formatItemPrice(this)}</p>
                            <p class="m-0">${formatItemAvailability(this["itemstock"])}</p>
                        </div>
                    </div>
                </header>
                <section class="border-bottom py-2">
                    <p class="fs-6 m-0">${this["itemdescription"]}</p>
                </section>
                <section class="gap-1 py-2">
                    <p class="m-0">Creato da ${this["itemcreator"]}</p>
                    ${"itempublisher" in this ? `<p class="m-0">Edito da ${this["itempublisher"]}</p>` : ""}
                </section>
                `

                $(footer).append(button)
                $(article).append(content)
                $(article).append(footer)
            })
        }
    })
})
