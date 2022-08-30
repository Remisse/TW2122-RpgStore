const ulClass = "list-group list-group-vertical-sm list-group-horizontal-md gap-1 px-0"
const liClass = "list-group-item list-group-item-action d-flex flex-fill align-items-center justify-content-center my-2 btn btn-primary rounded"
const linkClass = "stretched-link fs-5 font-weight-bold text-decoration-none text-on-primary text-center"

$(document).ready(function() {
    appendBrands("main section:first-of-type", ulClass, liClass, linkClass)
    appendCategories("main", "main section:nth-of-type(2)", ulClass, liClass, linkClass)
})
