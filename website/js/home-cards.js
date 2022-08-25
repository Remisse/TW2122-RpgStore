$(document).ready(function() {
    appendBrands(
        "section > section:first-of-type > div > div", 
        "list-group list-group-vertical-sm list-group-horizontal-md justify-content-evenly px-0",
        "list-group-item card text-center align-self-center col-12 col-md-3 my-2"
    )
    appendMainCategories(
        "section > section:last-of-type > div > div", 
        "list-group list-group-vertical-sm list-group-horizontal-md justify-content-evenly px-0",
        "list-group-item card text-center align-self-center col-12 col-md-3 my-2"
    )
})
