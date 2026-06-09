// active link
const current_url = window.location.href
const nav_link = document.querySelectorAll(".sidebar ul")

nav_link.forEach(link =>{
if(link.href === current_url){
    link.classList.add("active")
}
})