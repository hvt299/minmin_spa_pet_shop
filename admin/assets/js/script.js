// function toggleSidebar() {
//     document.getElementById("sidebar-admin").classList.toggle("collapsed");
// }

// document.addEventListener("DOMContentLoaded", function () {
//     const submenuParents = document.querySelectorAll(".has-submenu > a");

//     submenuParents.forEach(link => {
//         link.addEventListener("click", function (e) {
//             e.preventDefault();

//             const parentLi = this.parentElement;
//             parentLi.classList.toggle("open");
//         });
//     });
// });

const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");
const toggleBtn = document.getElementById("toggleSidebar");

const isMobile = () => window.innerWidth <= 768;

function closeAllSubmenus(except = null) {
    document.querySelectorAll(".sidebar__item--open").forEach((el) => {
        if (el !== except) el.classList.remove("sidebar__item--open");
    });
    document.querySelectorAll(".sidebar__submenu-popup").forEach((p) => (p.style.display = "none"));
}

toggleBtn.addEventListener("click", () => {
    if (isMobile()) {
        sidebar.classList.toggle("sidebar--open");
        overlay.classList.toggle("overlay--active");
    } else {
        sidebar.classList.toggle("sidebar--collapsed");
    }
});

overlay.addEventListener("click", () => {
    sidebar.classList.remove("sidebar--open");
    overlay.classList.remove("overlay--active");
});

document.querySelectorAll(".sidebar__item--has-submenu").forEach((item) => {
    const link = item.querySelector(".sidebar__link");
    const submenu = item.querySelector(".sidebar__submenu");
    const popup = item.querySelector(".sidebar__submenu-popup");

    link.addEventListener("click", (e) => {
        e.stopPropagation();
        const collapsed = sidebar.classList.contains("sidebar--collapsed");

        if (isMobile()) {
            // Accordion behavior on mobile
            if (item.classList.contains("sidebar__item--open")) {
                item.classList.remove("sidebar__item--open");
            } else {
                closeAllSubmenus(item);
                item.classList.add("sidebar__item--open");
            }
        } else if (collapsed) {
            // Popup submenu when collapsed (desktop only)
            closeAllSubmenus(item);
            const rect = item.getBoundingClientRect();
            popup.innerHTML = submenu.innerHTML;
            popup.style.top = `${rect.top}px`;
            popup.style.display = "flex";
        } else {
            // Normal accordion desktop
            if (item.classList.contains("sidebar__item--open")) {
                item.classList.remove("sidebar__item--open");
            } else {
                closeAllSubmenus(item);
                item.classList.add("sidebar__item--open");
            }
        }
    });
});

document.addEventListener("click", () => closeAllSubmenus());

document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const themeToggle = document.getElementById('themeToggle');
    const icon = themeToggle?.querySelector('i');
    const savedTheme = localStorage.getItem('theme');

    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        if (icon) icon.classList.replace('fa-moon', 'fa-sun');
    }

    // Gọi updateChartTheme() 1 lần khi trang load
    window.addEventListener('load', () => {
        if (typeof updateChartTheme === 'function') updateChartTheme();
    });

    themeToggle?.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        const isDark = body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');

        if (icon) {
            icon.classList.replace(isDark ? 'fa-moon' : 'fa-sun', isDark ? 'fa-sun' : 'fa-moon');
        }

        if (typeof updateChartTheme === 'function') {
            updateChartTheme();
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const msg = params.get('msg');
    const success = params.get('success');
    const error = params.get('error');

    if (msg && (success || error)) {
        const toast = document.createElement('div');
        toast.className = 'toast ' + (success ? 'success' : 'error');
        toast.innerHTML = success ?
            `<i class="fas fa-check-circle"></i> ${msg}` :
            `<i class="fas fa-exclamation-circle"></i> ${msg}`;
        document.body.appendChild(toast);

        // Hiển thị
        setTimeout(() => toast.classList.add('show'), 100);

        // Tự ẩn sau 3 giây
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
});