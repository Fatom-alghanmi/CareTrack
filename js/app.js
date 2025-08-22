document.addEventListener("DOMContentLoaded", () => {
    const modeSwitch = document.querySelector("#modeSwitch");
    if (!modeSwitch) return;
  
    // Load saved theme
    const currentTheme = localStorage.getItem("theme");
    if (currentTheme === "dark") {
      document.body.setAttribute("data-theme", "dark");
      modeSwitch.checked = true;
    }
  
    // Toggle theme
    modeSwitch.addEventListener("change", () => {
      if (modeSwitch.checked) {
        document.body.setAttribute("data-theme", "dark");
        localStorage.setItem("theme", "dark");
      } else {
        document.body.setAttribute("data-theme", "light");
        localStorage.setItem("theme", "light");
      }
    });
  });
  