/*
    Code taken from https://www.kirupa.com/html5/setting_css_styles_using_javascript.htm
*/

document.addEventListener("DOMContentLoaded", function(event) {
    // document.documentElement returns the root Element 
    // which has the propery data-theme
    document.documentElement.setAttribute("data-theme", "dark");

    // Get our button switcher
    var toggleButton = document.getElementById("theme-switch");

    // When our button gets clicked
    toggleButton.onclick = function() {
        // Get the current selected theme, on the first run
        // it should be `light`
        var currentTheme = document.documentElement.getAttribute("data-theme");

        // Switch between `dark` and `light`
        var switchToTheme = currentTheme === "dark" ? "light" : "dark"
        
        if (switchToTheme === "dark"){
            toggleButton.innerHTML = "Light Mode"
        }else{
            toggleButton.innerHTML = "Dark Mode"
        }

        // Set our currenet theme to the new one
        document.documentElement.setAttribute("data-theme", switchToTheme);
    }
});