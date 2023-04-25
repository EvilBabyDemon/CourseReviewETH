<div id="header">
    <h1><a href="https://n.ethz.ch/~lteufelbe/coursereview/" onFocus="if(this.blur)this.blur()">CourseReview</a></h1>
</div>
<div id="menu">
    <a class="button" href="https://n.ethz.ch/~lteufelbe/coursereview/add/<?php echo $course_url; ?>" onFocus="if(this.blur)this.blur()">
        <svg xmlns="http://www.w3.org/2000/svg" class="button-icon" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Add
    </a>
    <a class="button" href="https://n.ethz.ch/~lteufelbe/coursereview/edit/" onFocus="if(this.blur)this.blur()">
        <svg xmlns="http://www.w3.org/2000/svg" class="button-icon" viewBox="0 0 20 20" fill="currentColor">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
        </svg>
        Edit
    </a>
</div>

<svg style="display: none;">
    <symbol viewBox="0 0 24 24" id="moon">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
    </symbol>
    <symbol viewBox="0 0 24 24" id="sun">
        <circle cx="12" cy="12" r="5"></circle>
        <line x1="12" y1="1" x2="12" y2="3"></line>
        <line x1="12" y1="21" x2="12" y2="23"></line>
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
        <line x1="1" y1="12" x2="3" y2="12"></line>
        <line x1="21" y1="12" x2="23" y2="12"></line>
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
    </symbol>
</svg>
<!--- Light mode button --->
<button class="color-mode_btn light--hidden" aria-label="Toggle light mode">
    <svg aria-hidden="true">
        <use href="#sun"></use>
    </svg>
</button>

<!--- Dark mode button --->
<button class="color-mode_btn dark--hidden" aria-label="Toggle dark mode">
    <svg aria-hidden="true">
        <use href="#moon"></use>
    </svg>
</button>
<script>
    document.body.classList.add("light-theme", "dark-theme");
    document.body.classList.toggle("light-theme");
    document.body.classList.toggle("dark-theme");
    const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");

    const currentTheme = localStorage.getItem("theme");
    if (currentTheme == "dark") {
        document.body.classList.toggle("dark-theme");
    } else if (currentTheme == "light") {
        document.body.classList.toggle("light-theme");
    }

    function toggleColorMode() {
        console.log("click");
        var theme = document.body.classList.contains("dark-theme") ? "light" : "dark";
        document.body.classList.toggle("dark-theme");
        document.body.classList.toggle("light-theme");
        localStorage.setItem("theme", theme);
    }
    const toggleColorButtons = document.querySelectorAll(".color-mode_btn");
    toggleColorButtons.forEach(btn => {
        btn.addEventListener("click", toggleColorMode);
    });
</script>
<?php
$user_id = $_SERVER["uniqueID"];
?>