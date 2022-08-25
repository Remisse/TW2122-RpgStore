$(document).ready(function() {
    $.ajax({url: `api/items-api.php${location.search}`, dataType: "json", type: "get", success: function(data) {
        const container = $("main section > div")

        $(data).each(function() {
            const buyButtonStatus = this["itemstock"] === 0 ? "disabled" : ""

            const article = document.createElement("article")
            article.className = "col-12 col-md-4 col-lg-3 card my-2 px-3"
            article.innerHTML = 
            `
                <header>
                    <p><img src="${this["itemimg"]}" class="card-img-top" alt="" /></p>
                    <h6 class="card-title px-2">${this["brandshortname"]} ${this["itemname"]}</h5>
                </header>
                <div class="card-body">
                    <p class="card-text mb-0">${formatItemPrice(this)}</p>
                    <p class="card-text">${formatItemAvailability(this)}</p>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" ${buyButtonStatus}>
                        <img src="svg/cart-plus.svg" alt="Aggiungi al carrello" />
                    </button>
                </div>
            `
            container.append(article);
        })
    }})
})
