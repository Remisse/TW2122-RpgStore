$(document).ready(function() {
    const dropdown = $("nav:nth-of-type(2) > div > ul");
    const button = $("nav:nth-of-type(2) > div > a");
    console.log(button)

    $(button).on("click", function() {
        $(dropdown).append(getSpinnerElement());

        $.ajax({url: "api/notifications.api.php", dataType: "json", success: function(data) {
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
            }
        }});
    });
});
