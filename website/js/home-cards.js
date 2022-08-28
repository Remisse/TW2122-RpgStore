const ulClass = "list-group list-group-vertical-sm list-group-horizontal-md gap-1 px-0"
const liClass = "list-group-item list-group-item-action d-flex flex-fill border rounded align-items-center justify-content-center my-2"
const linkClass = "stretched-link lead"

$(document).ready(function() {
    appendBrands("main section:first-of-type", ulClass, liClass, linkClass)
    appendMainCategories("main section:last-of-type", ulClass, liClass, linkClass)
})
