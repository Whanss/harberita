document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        var sidebar = document.querySelector(".fi-sidebar");
        if (!sidebar) return;

        var gridSvgHtml = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width:1.1rem;height:1.1rem;display:inline-block;vertical-align:middle"><rect x="3" y="3" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.5"/></svg>';

        function replaceToggleIcons() {
            document.querySelectorAll(
                ".fi-topbar-open-sidebar-btn svg, " +
                ".fi-sidebar-close-overlay-btn svg, " +
                ".fi-sidebar-header button svg, " +
                ".fi-topbar nav button svg"
            ).forEach(function (svg) {
                if (!svg.getAttribute("data-replaced")) {
                    svg.outerHTML = gridSvgHtml;
                }
            });
        }

        replaceToggleIcons();
        document.addEventListener("livewire:navigated", replaceToggleIcons);
        setTimeout(replaceToggleIcons, 800);

        new MutationObserver(function () {
            var cls = sidebar.className;
            var isCollapsed = cls.includes("fi-collapsed") || cls.includes("-translate-x");

            sidebar.querySelectorAll(".fi-sidebar-item-label, .fi-sidebar-group-label").forEach(function (el) {
                el.style.opacity = isCollapsed ? "0" : "";
                el.style.maxWidth = isCollapsed ? "0" : "";
                el.style.transform = isCollapsed ? "translateX(-8px)" : "";
            });

            sidebar.querySelectorAll(".fi-sidebar-item-button").forEach(function (el) {
                el.style.justifyContent = isCollapsed ? "center" : "";
                el.style.paddingLeft = isCollapsed ? "0" : "";
                el.style.paddingRight = isCollapsed ? "0" : "";
            });
        }).observe(sidebar, { attributes: true, attributeFilter: ["class", "style"] });

    }, 300);
});
